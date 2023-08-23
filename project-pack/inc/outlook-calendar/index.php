<?php 
/**
 * MS Outlook Calendar
 * 
 * @since 1.0.0
 */

require_once(__DIR__ . '/options.php');

function pp_button_outlook_order_action( $actions, $order ){
  $isCourse = false;
  foreach ( $order->get_items() as $item_id => $item ) {
    $course_information = wc_get_order_item_meta($item_id, 'course_information', true);
    if($course_information) {
      $isCourse = true;
    }
  }

  if($isCourse) {
    $action_outlook = [
      'url'  => '#' . $order->get_id(),
      'name' => __( 'Add Outlook Calendar', 'pp' ),
    ];
  
    $actions['outlook'] = $action_outlook;
  }
  
  return $actions;
}

add_filter('woocommerce_my_account_my_orders_actions' , 'pp_button_outlook_order_action' ,10 , 2 );

add_filter( 'pp/script_data', function($data) {
  $currentUserID = get_current_user_id();

  # return if user non logged in
  if(!$currentUserID) return $data;

  # return token & refresh token
  $data['__ms_graph_token'] = get_user_meta($currentUserID, '__ms_graph_token', true);
  $data['__ms_graph_refresh_token'] = get_user_meta($currentUserID, '__ms_graph_refresh_token', true);
  $data['ms_client_id'] = get_field('ms_client_id', 'option');
  $data['ms_scope'] = get_field('ms_scope', 'option');
  $data['ms_client_secret'] = get_field('client_secret', 'option');
  $data['ms_return_url'] = get_site_url();
  return $data;
} );

add_action( 'init', function() {
  if(!isset($_GET['__action'])) return;

  switch($_GET['__action']) {
    case 'getmstoken':
      if(! isset($_GET['code'])) return;
      $endpoint = 'https://login.microsoftonline.com/consumers/oauth2/v2.0/token';
      $params = [
        'client_id' => get_field('ms_client_id', 'option'),
        'grant_type' => 'authorization_code', 
        'code' => $_GET['code'],
        'client_secret' => get_field('client_secret', 'option'),
        'redirect_uri' => get_site_url(),
      ]; 

      $result = wp_remote_post($endpoint, [
        'method'    => 'POST',
        'headers' => [
          'content-type' => 'application/x-www-form-urlencoded'
        ], 
        'body' => http_build_query($params)
      ]);

      $body = json_decode($result['body']);
      echo json_encode($body);
      die; 
      break;
  }
} );

function pp_get_course_info_by_order($orderID) {
  $order = new WC_Order($orderID);
  $result = [];
  foreach ( $order->get_items() as $item_id => $item ) {
    $course_information = wc_get_order_item_meta($item_id, 'course_information', true);
    if($course_information) {
      array_push($result, $course_information);
    }
  }

  return $result;
}

function pp_ajax_get_course_info_by_order() {
  $orderID = $_POST['order_id'];
  $courses = pp_get_course_info_by_order($orderID);

  if(!$courses) wp_send_json( [
    'success' => false,
    'message' => 'No courses found!',
  ] );

  $dataPushOutlookCalendar = array_map( function($_c, $_index) {
    $startDate = date('Y-m-d h:i:s', strtotime($_c['start_date']));
    $endDate = date('Y-m-d h:i:s', strtotime($_c['end_date']));

    ob_start();
    ?><h4><?php echo $_c['name'] ?></h4>
    <p>Start date: <?php echo $_c['start_date'] ?> <?php echo $_c['start_time'] ?></p> /
    <p>End date: <?php echo $_c['end_date'] ?> <?php echo $_c['end_time'] ?></p>
    <?php
    $HTML = ob_get_clean();

    return [
      "id" => (int) $_index + 1,  
      "method" => "POST",  
      "url" => "/me/events",  
      "body" => [
        "subject" => $_c['name'],  
        "body" => [
          "contentType" => "HTML",  
          "content" => $HTML, 
        ],
        "start" => [
          "dateTime" => $startDate,  
          "timeZone" => "Pacific Standard Time"  
        ],  
        "end" => [
          "dateTime" => $endDate,  
          "timeZone" => "Pacific Standard Time"  
        ],  
        "location" => [
          "displayName" => get_site_url(),
        ],  
        // "attendees" => [  
        //   [
        //     "emailAddress" => [
        //       "address" => "mike.beplus@gmail.com",  
        //       "name" => "Mike T"  
        //     ],  
        //     "type" => "required"  
        //   ]
        // ] 
      ],
      "headers" => [
        "Content-Type" => "application/json"  
      ] 
    ];
  }, $courses, array_keys($courses) );

  wp_send_json( [
    'success' => true, 
    'content' => $dataPushOutlookCalendar,
  ] );
}

add_action( 'wp_ajax_pp_ajax_get_course_info_by_order', 'pp_ajax_get_course_info_by_order' );
add_action( 'wp_ajax_nopriv_pp_ajax_get_course_info_by_order', 'pp_ajax_get_course_info_by_order' );