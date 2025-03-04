<?php

/** 
 * Function to log Salesforce Webhook data
 */
function sf_log_data( $data ) {
  if (empty($data)) {
    return;
  }
  // Get the WordPress uploads directory.
  $upload_dir = wp_upload_dir();
  $log_dir_path = $upload_dir['basedir'] . '/sf-sync-logs';
  $log_file_path = $log_dir_path . '/sf-sync-logs-' . wp_date('m-Y') . '.log';

  // Create the logs directory if it doesn't exist.
  if (!file_exists($log_dir_path)) {
    if (!mkdir($log_dir_path, 0755, true) && !is_dir($log_dir_path)) {
      error_log('Failed to create log directory: ' . $log_dir_path);
      return;
    }
  }
  // Prepare the log message with the current timestamp.
  $log_message  = PHP_EOL;
  $log_message .= "[" . wp_date('d-m-Y H:i:s') . "]" . PHP_EOL;
  $log_message .= $data . PHP_EOL;

  // Append the log message to the file.
  $result = file_put_contents($log_file_path, $log_message, FILE_APPEND | LOCK_EX);

  // Log to WordPress debug log if file writing fails.
  if ($result === false) {
    error_log('Failed to write to log file: ' . $log_file_path);
  }
}

/**
 * Function to get Salesforce API Info
 */
function and_get_sf_api_info() {
  return [
    'endpoint' => get_field('salesforce_endpoint_url', 'option'),
    'version' => get_field('salesforce_api_version', 'option'),
    'access_token' => get_field('salesforce_api_access_token', 'option')
  ];
}

/** 
 * Function to push Event data from WP to Salesforce
 */
add_action('save_post', 'and_push_event_data_to_salesforce', 10 , 3);
function and_push_event_data_to_salesforce($post_id, $post, $update){

  if( $post->post_type == 'sf-event' && $post->post_status == "publish" ){
      $sf_event_id = get_field('sf_event_id', $post_id );

      if ( ! $sf_event_id ) return;

      // Get remaining seats to update
      $remaining_seats__c = get_field('remaining_seats__c', $post_id );
      $startdatetime = get_field('startdatetime', $post_id );
      $enddatetime = get_field('enddatetime', $post_id );

      // Get API info
      list(
        'endpoint' => $sf_endpoint_url,
        'version' => $sf_api_version,
        'access_token' => $sf_api_access_token
      ) = and_get_sf_api_info();
      
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $sf_endpoint_url.'/services/data/'.$sf_api_version.'/sobjects/Event/'.$sf_event_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
          "remaining_seats__c": "'.$remaining_seats__c.'",
          "StartDateTime": "'.$startdatetime.'",
          "EndDateTime": "'.$enddatetime.'"
        }',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Authorization: Bearer '.$sf_api_access_token,
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      // echo $response;

      $log_data = array(
        'post_id'            => $post_id ?? '',
        'sf_event_id'        => $sf_event_id ?? '',
        'Subject'            => get_the_title($post_id) ?? '',
        'remaining_seats__c' => $remaining_seats__c ?? '',
        'StartDateTime'      => $startdatetime ?? '',
        'EndDateTime'        => $enddatetime ?? '',
      );

      sf_log_data('[Event][PUSH]' . wp_json_encode($log_data));
  }
}

/**
 * Push event field data to salesforce
 */
add_action('updated_post_meta', 'and_push_event_meta_field_to_salesforce', 10, 4);
function and_push_event_meta_field_to_salesforce($meta_id, $post_id, $meta_key, $meta_value){

  $meta_key_lst = ['remaining_seats__c', 'startdatetime','enddatetime'];
  if( in_array($meta_key, $meta_key_lst) ){
      $sf_event_id = get_field('sf_event_id', $post_id );

      if ( ! $sf_event_id ) return;

      $field_key = '';

      switch ($meta_key) {
        case 'remaining_seats__c':
          $field_key = 'remaining_seats__c';
          break;

        case 'startdatetime':
          $field_key = 'StartDateTime';
          break;

        case 'enddatetime':
          $field_key = 'EndDateTime';
          break;
        
        default:
          # code...
          break;
      }

      // Get API info
      list(
        'endpoint' => $sf_endpoint_url,
        'version' => $sf_api_version,
        'access_token' => $sf_api_access_token
      ) = and_get_sf_api_info();
      
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $sf_endpoint_url.'/services/data/'.$sf_api_version.'/sobjects/Event/'.$sf_event_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
          "'.$field_key.'": "'.$meta_value.'"
        }',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Authorization: Bearer '.$sf_api_access_token,
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      // echo $response;
  }
}

/** 
 * Function to pull Event data from Salesforce to WP
 */
function and_pull_event_data_from_salesforce($event_fields){
  ob_start();
  $event_id = ppsf_find_event_by_sfevent_id($event_fields['Id']);
  if($event_id) {
    foreach($event_fields as $name => $value) {
      if ( $name == "Subject" ) {
        $post_args = [
          "ID" => (int) $event_id,
          "post_title" => $value
        ];
        wp_update_post($post_args);
        sf_log_data('[Event][PULL]' . wp_json_encode( $post_args ));
      } else {
        update_post_meta($event_id, strtolower($name), $value);
      }
    }
  }
  ob_get_clean();
}

/** 
 * Sync Product data from Salesforce to WP
 */
function and_set_category($product_id, $tax_title, $tax_slug){
  if (taxonomy_exists($tax_slug) === true) {
    if(isset($tax_title) && !empty($tax_title)){
      $term = term_exists(sanitize_title($tax_title), $tax_slug);
      
      if (is_array( $term )) {
        $term_id = (int)$term['term_id'];
        wp_update_term($term_id, $tax_slug, array('name' => trim(preg_replace('/\s+/', ' ', $tax_title))));
        $category_id = $term_id;
      } else {
        $newterm = wp_insert_term(trim(preg_replace('/\s+/', ' ', $tax_title)), $tax_slug, array('parent' => intval(0), 'slug' => sanitize_title($tax_title)));
        $category_id = $newterm['term_id'];
      }
    }

    wp_set_object_terms((int)$product_id, $category_id, $tax_slug);
  }
}

function and_update_product($product_id, $product_fields){
  $post_args = [
    "ID" => (int)$product_id,
    "post_title" => $product_fields['Name'],
    "post_content" => $product_fields['Woocommerce_Description__c'],
    "post_excerpt" => $product_fields['Description']
  ];
  
  // Update post
  $post_id = wp_update_post( $post_args );
  
  if ( is_wp_error( $post_id ) ) {
    sf_log_data('Update product ID ' . $post_id . ' failed!');
  } else {
    // Update Category
    and_set_category($post_id, $product_fields['Family'], 'product_cat');

    // Update Meta Fields
    update_post_meta($post_id, '_sku', $product_fields['ProductCode']);
    update_post_meta($post_id, '__sf_product_id', $product_fields['Id']);
    update_post_meta($post_id, '__sf_family', $product_fields['Family']);
    update_post_meta($post_id, '__sf_productcode', $product_fields['ProductCode']);
  }
}

function and_inset_product($product_fields){
  $post_args = [
    'post_status'  => 'publish',
    'post_title'   => trim(preg_replace('/\s+/', ' ', $product_fields['Name'])),
    "post_content" => $product_fields['Woocommerce_Description__c'],
    "post_excerpt" => $product_fields['Description'],
    'post_name'		 => sanitize_title($product_fields['Name']),
    'post_type'    => 'product'
  ];

  // Update post
  $post_id = wp_insert_post( $post_args );
    
  if ( is_wp_error( $post_id ) ) {
    sf_log_data('Insert product ID ' . $post_id . ' failed!');
  } else {
    // Update Category
    and_set_category($post_id, $product_fields['Family'], 'product_cat');

    // Update Meta Fields
    update_post_meta($post_id, '_sku', $product_fields['ProductCode']);
    update_post_meta($post_id, 'cfx_sales_id', $product_fields['Id']);
    update_post_meta($post_id, '__sf_product_id', $product_fields['Id']);
    update_post_meta($post_id, '__sf_family', $product_fields['Family']);
    update_post_meta($post_id, '__sf_productcode', $product_fields['ProductCode']);
  }
}

function and_find_product_by_sfproduct_id($sf_product_id) {
  $_posts = get_posts([
    'post_type' => 'product',
    'status' => 'any',
    'meta_query' => [
      [
        'key'   => 'cfx_sales_id',
			  'value' => $sf_product_id,
        'compare' => '='
      ]
    ],
  ]);

  return (($_posts && count($_posts)) > 0 ? $_posts[0]->ID : false);
}

function and_pull_product_data_from_salesforce($product_fields){
  ob_start();
  $product_id = and_find_product_by_sfproduct_id($product_fields['Id']);
  
  if($product_id) {
    // Update Product
    and_update_product($product_id, $product_fields);

  } else {
    // Create Product
    
    and_inset_product($product_fields);
  }

  ob_get_clean();
}

/**
 * Function to create an Event Relation on Salesforce
 */
function and_create_an_event_relation_on_salesforce($event_id, $relation_id) {

  if( $event_id && $relation_id ){
    // Get API info
    list(
      'endpoint' => $sf_endpoint_url,
      'version' => $sf_api_version,
      'access_token' => $sf_api_access_token
    ) = and_get_sf_api_info();
    
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => $sf_endpoint_url.'/services/data/'.$sf_api_version.'/sobjects/EventRelation',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS =>'{
        "EventId": "'.$event_id.'",
        "RelationId": "'.$relation_id.'",
        "IsInvitee": true
      }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer '.$sf_api_access_token,
      ),
    ));

    $response = curl_exec($curl);
    curl_close($curl);
    
    $data = json_decode($response, true);
    if ( $data['success'] ) {
      return array(
        'success' => 'true',
        'relation_id' => $data['id']
      );
    } else {
      return array(
        'success' => 'false',
        'message' => $data
      ); 
    }
  }
}

/**
 * Apply to sync event data when click button in backend
 */
add_action('acfe/fields/button/key=field_658bd082f0b27', 'and_event_acf_button_ajax_handle', 10, 2);
function and_event_acf_button_ajax_handle($field, $post_id){
    $eventID = get_field('sf_event_id', $post_id);

    $event_data = ppsf_get_event($eventID);
    $custom_fields = apply_filters('PPSF/EVENT_CUSTOM_FIELDS_FILTER', [
      //'sf_event_id' => $event_data['Id'],
      'workshop_event_date_text__c' => $event_data['Workshop_Event_Date_Text__c'],
      'workshop_times__c' => $event_data['Workshop_Times__c'],
      'total_number_of_seats__c' => $event_data['Total_Number_of_Seats__c'],
      'remaining_seats__c' => $event_data['Remaining_Seats__c'],
      'startdatetime' => $event_data['StartDateTime'],
      'enddatetime' => $event_data['EndDateTime']
    ], $event_data);

    foreach($custom_fields as $name => $value) {
      update_post_meta($post_id, $name, $value);
    }

    sf_log_data('[Event][MANUAL_PULL]' . wp_json_encode( $event_data ));
    
    wp_send_json_success("Success! Event has synced from Salesforce.");
    
}

/**
 * Add Event ID column to Admin table
 */
add_filter('manage_sf-event_posts_columns', 'custom_sf_event_admin_column');
function custom_sf_event_admin_column($columns)
{
  $columns['sf_event_id'] = 'Salesforce Event ID';
  $columns['sf_event_date'] = 'Event Date';
  return $columns;
}

add_action('manage_sf-event_posts_custom_column', 'custom_sf_event_admin_column_value', 10, 2);
function custom_sf_event_admin_column_value($column_key, $post_id): void
{
  // Salesforce Event ID column
  if ($column_key == 'sf_event_id') {
    $sf_event_id = get_field('sf_event_id', $post_id);
    if (isset($sf_event_id)) {
      echo $sf_event_id;
    }
  }

  // Event Date column
  if ($column_key == 'sf_event_date') {
    $workshop_event_date = get_field('workshop_event_date_text__c', $post_id);
    $workshop_times = get_field('workshop_times__c', $post_id);
    if (isset($workshop_event_date)) {
      echo $workshop_event_date;
    }
    if (isset($workshop_times)) {
      echo '<br>at ';
      echo $workshop_times;
    }
  }
}