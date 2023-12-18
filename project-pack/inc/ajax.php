<?php 
/**
 * Ajax 
 * 
 * @version 1.0.0
 * @since 1.0.0
 */

function pp_get_product_card_style() {
  extract( $_POST );
  $args = [
    'paged' => (int) $data['currentPage'],
    'posts_per_page' => (int) $data['items'],
  ];

  /**
   * Search text
   */
  if(! empty($data['s'])) {
    $args['s'] = $data['s'];
  }

  /**
   * Filter by Category
   */
  $term_html = '';
  if(! empty($data['cat'])) {

    $term = get_term($data['cat'], 'product_cat');
    $term_html = "<div class=\"term-html\"><h2>$term->name</h2>" . wpautop($term->description) . "</div>";

    $args['tax_query'] = [
      [
        'taxonomy' => 'product_cat',                
        'field' => 'term_id',                    
        'terms' => $data['cat'],   
        'include_children' => true,         
        'operator' => 'IN'          
      ]
      ];
  }
  
  $query = pp_query_product($args);

  ob_start();
  if($data['append'] == "false") {
    pp_get_products_tag($query); // get all template and reset current page & max page
  } else {
    pp_get_products_loop_tag($query); // get only loop item for pagination loadmore
  }
  $content = ob_get_clean();

  wp_send_json( [
    'success' => true,
    'term_html' => $term_html,
    'content' => $content,
    'max_num_pages' => $query->max_num_pages,
    'append' => ($data['append'] == "true" ? true : false),
  ] );
}

add_action( 'wp_ajax_pp_get_product_card_style', 'pp_get_product_card_style' );
add_action( 'wp_ajax_nopriv_pp_get_product_card_style', 'pp_get_product_card_style' );

function pp_ajax_woo_update_qtt() {
  extract($_POST);
  global $woocommerce;
  $woocommerce->cart->set_quantity($data['cart_item_key'], (int) $data['qtt_number']); 
}

add_action( 'wp_ajax_pp_ajax_woo_update_qtt', 'pp_ajax_woo_update_qtt' );
add_action( 'wp_ajax_nopriv_pp_ajax_woo_update_qtt', 'pp_ajax_woo_update_qtt' );

function pp_ajax_woo_remove_cart_item() {
  extract($_POST);
  global $woocommerce;
  $woocommerce()->cart->remove_cart_item( $data['cart_item_key'] );
}

add_action( 'wp_ajax_pp_ajax_woo_remove_cart_item', 'pp_ajax_woo_remove_cart_item' );
add_action( 'wp_ajax_nopriv_pp_ajax_woo_remove_cart_item', 'pp_ajax_woo_remove_cart_item' );

function pp_ajax_woo_product_variation_add_to_cart() {
  extract($_POST);
  global $woocommerce;
  
  if($data['product'] && $data['variations'] && count($data['variations']) > 0) {
    foreach($data['variations'] as $variation) {
      $woocommerce->cart->add_to_cart( (int) $data['product'], 1, (int) $variation );
    }
  }

  wp_send_json( $_POST );
}

add_action( 'wp_ajax_pp_ajax_woo_product_variation_add_to_cart', 'pp_ajax_woo_product_variation_add_to_cart' );
add_action( 'wp_ajax_nopriv_pp_ajax_woo_product_variation_add_to_cart', 'pp_ajax_woo_product_variation_add_to_cart' );

function pp_ajax_product_loadmore() {
  extract( $_POST['data'] );
  $args = [
    'posts_per_page' => (int) $numberPerPage,
    'paged' => (int) $paged,
  ];

  if(isset($s) && $s != '') {
    $args['s'] = $s;
  }

  if(isset($termID) && $termID != '') {
    $args['tax_query'] = [
      [
        'taxonomy' => 'product_cat', 
        'field' => 'term_id', 
        'terms' => (int) $termID,   
        'include_children' => true,         
        'operator' => 'IN'  
      ]
      ];
  }

  $_query = pp_query_product($args);
  
  ob_start();
  pp_get_products_loop_tag($_query);
  $_html = ob_get_clean();

  wp_send_json( [
    'success' => true,
    'content' => $_html,
  ] );
}

add_action( 'wp_ajax_pp_ajax_product_loadmore', 'pp_ajax_product_loadmore' );
add_action( 'wp_ajax_nopriv_pp_ajax_product_loadmore', 'pp_ajax_product_loadmore' );

function pp_ajax_product_filter_v2() {
  // wp_send_json( $_POST );
  extract($_POST['data']);

  ob_start();
  echo pp_product_landing_load_init([
    's' => $s,
    'term' => (int) $term,
    'posts_per_page' => (int) $posts_per_page,
    'paged' => (int) $paged,
    'loadmore' => $loadmore
  ]);
  $content = ob_get_clean();

  wp_send_json( [
    'success' => true,
    'content' => $content
  ] );
}

add_action( 'wp_ajax_pp_ajax_product_filter_v2', 'pp_ajax_product_filter_v2' );
add_action( 'wp_ajax_nopriv_pp_ajax_product_filter_v2', 'pp_ajax_product_filter_v2' );

function pp_check_contact_inner_EventRelation($eventID = '', $contactID = '') {
  $res = ppsf_get_EventRelation_by_event_Id($eventID);
  if(!$res || count($res['records']) == 0) return false;
  $records = $res['records'];
  $found = array_search($contactID, array_column($records, 'RelationId'));
  return (($found === false) ? false : true);
}

function pp_ajax_find_contact_sf_by_email() {
  $email = $_POST['email'];
  $event_id = $_POST['event_id']; 
  $res = ppsf_find_contact_by_email($email);
  
  if(count($res['records']) > 0) {
    $firstContact = $res['records'][0];
    $AccountId = $firstContact['AccountId'];
    $firstContact['__Account_Data'] = !empty($AccountId) ? ppsf_get_account($AccountId) : '';
    wp_send_json( [
      'contact' => $firstContact,
      'joined' => pp_check_contact_inner_EventRelation($event_id, $firstContact['Id']),
    ] );
  } else {
    wp_send_json( [
      'contact' => '',
      'joined' => false,
    ] );
  }
}

add_action('wp_ajax_pp_ajax_find_contact_sf_by_email', 'pp_ajax_find_contact_sf_by_email');
add_action('wp_ajax_nopriv_pp_ajax_find_contact_sf_by_email', 'pp_ajax_find_contact_sf_by_email');

function pp_ajax_ppsf_add_new_contact() {
  $sf_user_metadata = pp_saleforce_current_user_metadata();
  $account_id_default = '0019q0000045pqRAAQ'; // Ausgrid
  $account_id = isset($sf_user_metadata['account_id']) ? $sf_user_metadata['account_id'] : $account_id_default;

  $fields = $_POST['fields'];
  $fields['AccountId'] = $account_id;
  $fields['RecordTypeId'] = '01228000000AX1yAAG';
  $newContact = ppsf_add_new_contact($fields);

  if($newContact && $newContact['success'] == true) {
    $c = ppsf_get_contact($newContact['id']);
    $c['__Account_Data'] = !empty($account_id) ? ppsf_get_account($account_id) : '';
    wp_send_json([
      'success' => true,
      'responses' => $newContact,
      'contact' => $c
    ]);
  } else {
    wp_send_json([
      'success' => false,
      'responses' => $newContact,
      'contact' => ''
    ]);
  }
}

add_action('wp_ajax_pp_ajax_ppsf_add_new_contact', 'pp_ajax_ppsf_add_new_contact');
add_action('wp_ajax_pp_ajax_ppsf_add_new_contact', 'pp_ajax_ppsf_add_new_contact');

function pp_ajax_save_attendees_in_cart() {
  global $woocommerce;
  $cart = $woocommerce->cart->cart_contents;

  foreach ( $cart as $cart_item_key => $cart_item ) {
    if(!isset($_POST['contact_id'][$cart_item_key])) continue;

    $c_IDs = $_POST['contact_id'][$cart_item_key];
    $c_emails = $_POST['email'][$cart_item_key];
    $c_fnames = $_POST['firstname'][$cart_item_key];
    $c_lnames = $_POST['lastname'][$cart_item_key];
    $c_organisations = $_POST['organisation'][$cart_item_key];

    $woocommerce->cart->cart_contents[$cart_item_key]['__SF_CONTACT_IDS'] = $c_IDs;
    $__SF_CONTACT_FULL = [];

    foreach($c_IDs as $index => $id) {
      array_push($__SF_CONTACT_FULL, [
        'contact_id' => $id,
        'email' => $c_emails[$index],
        'firstname' => $c_fnames[$index],
        'lastname' => $c_lnames[$index],
        'account_id' => $c_organisations[$index]
      ]);
    }

    $woocommerce->cart->cart_contents[$cart_item_key]['__SF_CONTACT_FULL'] = $__SF_CONTACT_FULL;
    // wc_update_order_item_meta($cart_item_key, '__SF_CONTACT_FULL', $__SF_CONTACT_FULL);
  }
  $woocommerce->cart->set_session();

  wp_send_json([
    'success' => true,
  ]);
}

add_action('wp_ajax_pp_ajax_save_attendees_in_cart', 'pp_ajax_save_attendees_in_cart');
add_action('wp_ajax_nopriv_pp_ajax_save_attendees_in_cart', 'pp_ajax_save_attendees_in_cart');

function pp_ajax_save_attendees_to_order() {
  global $woocommerce;
  $order_id = $_POST['order_id'];
  $order = wc_get_order($order_id);
  $items = $order->get_items();
  $cart = $woocommerce->cart->cart_contents;

  foreach ( $items as $item_key => $item ) {
    if(!isset($_POST['contact_id'][$item_key])) continue;
    
    $course_information = wc_get_order_item_meta($item_key, 'course_information', true);
    $eventID = $course_information['event_parent']['sf_event_id'];

    $c_IDs = $_POST['contact_id'][$item_key];
    $c_emails = $_POST['email'][$item_key];
    $c_fnames = $_POST['firstname'][$item_key];
    $c_lnames = $_POST['lastname'][$item_key];
    $c_organisations = $_POST['organisation'][$item_key];
    $c_relation_ids = $_POST['relation_id'][$item_key];

    $__SF_CONTACT_FULL = [];

    foreach($c_IDs as $index => $id) {
      $r_id = $c_relation_ids[$index];
      if(!empty($r_id)) continue;
      
      $res = ppsf_add_EventRelation($eventID, $id); // and_create_an_event_relation_on_salesforce($eventID, $id);
      $relation_id = isset($res['id']) ? $res['id'] : '';
      
      $item_data = [
        'contact_id' => $id,
        'email' => $c_emails[$index],  
        'firstname' => $c_fnames[$index],
        'lastname' => $c_lnames[$index],
        'account_id' => $c_organisations[$index],
        'relation_id' => $relation_id,
      ]; 

      // wp_send_json($item_data);
      pp_add_attendees_order($order_id, $item_data);
      pp_log('----------------------------- '. "\n" .'Event ID: ' . $eventID . "\n" . 'Event Data: ' . wp_json_encode($item_data) . "\n" . '-----------------------------');
    }
  }
  
  wp_send_json([
    'success' => true,
  ]);
}

add_action('wp_ajax_pp_ajax_save_attendees_to_order', 'pp_ajax_save_attendees_to_order');
add_action('wp_ajax_nopriv_pp_ajax_save_attendees_to_order', 'pp_ajax_save_attendees_to_order');

function pp_ajax_remove_slot_attendees() {
  // wp_send_json($_POST);

  // Remove from Salesforce
  ppsf_delete_EventRelation_record($_POST['rid']);

  // Remove from order
  pp_remove_attendees_order($_POST['oid'], $_POST['rid']);

  wp_send_json([
    'success' => true,
  ]); 
}

add_action('wp_ajax_pp_ajax_remove_slot_attendees', 'pp_ajax_remove_slot_attendees');
add_action('wp_ajax_nopriv_pp_ajax_remove_slot_attendees', 'pp_ajax_remove_slot_attendees');