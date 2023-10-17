<?php 
/**
 * Salesforce Event import
 * 
 * @author Mike
 */

add_action('init', 'pp_register_sfevent_cpt');
function pp_register_sfevent_cpt() {
  $args = [
    'public'    => true,
    'label'     => __( 'SF Events', 'pp' ),
    'menu_icon' => 'dashicons-block-default',
  ];
  register_post_type( 'sf-event', $args );
}

add_action('admin_menu', 'pp_register_sf_event_import_page');
function pp_register_sf_event_import_page() {
  add_submenu_page( 
    'edit.php?post_type=product',   //or 'options.php'
    __('SF Event Import', 'pp'),
    __('SF Event Import', 'pp'),
    'manage_options',
    'sf-event-import-page',
    'pp_event_import_page_callback',
  );
}

function pp_event_import_page_callback() {
  ?>
  <div id="PP_EVENT_IMPORT_PAGE">
    <h2><?php _e('Salesforce Event Import Page 📅', 'pp') ?></h2>
    <div class="container" id="PP_EVENT_IMPORT_PAGE_CONATAINER">

    </div>
  </div> <!-- #PP_EVENT_IMPORT_PAGE -->
  <?php
}

function pp_sf_object_item_is_error($data) {
  return (is_array($data) && $data[0]['errorCode']) ? true : $data;
}

function pp_get_full_event_import_data() {
  $junctions = ppsf_get_junctions();
  if(!isset($junctions['records']) || count($junctions['records']) == 0) {
    return $junctions;
  }

  $records =  array_map(function($item) {
    $item['parent_event_data'] = !empty($item['Parent_Event__c']) ? ppsf_get_event($item['Parent_Event__c']) : '';
    $item['child_event_data'] = !empty($item['Child_Event__c']) ? ppsf_get_event($item['Child_Event__c']) : '';
    return $item; 
  }, $junctions['records']);

  $junctions['records'] = $records;
  return $junctions;
}

add_action('wp_ajax_pp_ajax_get_sf_junctions_object', 'pp_ajax_get_sf_junctions_object');
add_action('wp_ajax_pp_ajax_get_sf_junctions_object', 'pp_ajax_get_sf_junctions_object');
function pp_ajax_get_sf_junctions_object() {
  wp_send_json(pp_get_full_event_import_data());
}

function ppsf_import_by_junction_id($junction_id) {
  $res = ppsf_get_junction_item($junction_id);

  # validate data
  if(!isset($res['Id'])) {
    pp_log('ERROR: fn ppsf_import_by_junction_id('. $junction_id .') => ' . wp_json_encode($res));
    return false;
  }

  if(empty($res['Parent_Event__c'])) {
    pp_log('ERROR: fn ppsf_import_by_junction_id | empty Parent_Event__c ' . wp_json_encode($res));
    return false;
  }

  if(empty($res['Child_Event__c'])) {
    pp_log('ERROR: fn ppsf_import_by_junction_id | empty Child_Event__c ' . wp_json_encode($res));
    return false;
  }
  # END validate data

  $parent_event = ppsf_get_event($res['Parent_Event__c']);
  $children_event = ppsf_get_event($res['Child_Event__c']);

  if(isset($parent_event['Id'])) {
    $wp_parent_event_id = ppsf_add_event($parent_event);
  } else {
    pp_log('ERROR: #' . $res['Parent_Event__c'] . ' not found!');
    return false;
  }

  if(isset($children_event['Id'])) {
    $wp_child_event_id = ppsf_add_event($children_event);
  } else {
    pp_log('ERROR: #' . $res['Child_Event__c'] . ' not found!');
    return false;
  }

  $product_parent_id = ppwc_event_create_variable_product([
    'junction_id' => $junction_id,
    'name' => $parent_event['Subject'],
  ]);

  ppwc_event_add_variation_product([
    'name' => $parent_event['Subject'],
    'wp_parent_event_id' => $wp_parent_event_id, 
    'wp_child_event_id' => $wp_child_event_id
  ], $product_parent_id);
}

function ppsf_add_event($event_data) {
  $event_id = ppsf_find_event_by_sfevent_id($event_data['Id']);
  if($event_id == false) {
    // add new event
    $event_id = wp_insert_post([
      'post_title' => wp_strip_all_tags($event_data['Subject']),
    ]);

    $custom_fields = apply_filters('PPSF/EVENT_CUSTOM_FIELDS_FILTER', [
      'sf_event_id' => $event_data['Id'],
      'workshop_event_date__c' => $event_data['Workshop_Event_Date__c'],
      'workshop_times__c' => $event_data['Workshop_Times__c'],
      'total_number_of_seats__c' => $event_data['Total_Number_of_Seats__c'],
      'remaining_seats__c' => $event_data['Remaining_Seats__c'],
    ], $event_data);

    foreach($custom_fields as $name => $value) {
      update_post_meta($event_id, $name, $value);
    }

    pp_log('Message: Insert sf event #' . $event_data['Id'] . ' => wp event id #' . $event_id);
  } else {
    pp_log('Message: Found sf event id #' . $event_data['Id'] . ' => wp event id #' . $event_id);
  }

  return $event_id;
}

function ppsf_find_event_by_sfevent_id($sf_event_id) {
  $_posts = get_posts([
    'post_type' => 'sf-event',
    'status' => 'any',
    'meta_query' => [
      [
        'key'   => 'sf_event_id',
			  'value' => $sf_event_id,
        'compare' => '='
      ]
    ]
  ]);

  return count($_posts) > 0 ? $_posts[0]->ID : false;
}

add_action( 'init', function() {
  if(!isset($_GET['test_import'])) return;

  ppsf_import_by_junction_id('a1y9h000000Ar6HAAS');
  echo 'TEST...!';
  // $test_id = 31055;
  // $_product = wc_get_product( $test_id );
  // echo '<pre>'; print_r($_product->get_attributes()); echo '</pre>'; die;

  // $tmpBk = get_post_meta($test_id, '_product_attributes',true);
  // echo '<pre>'; print_r($tmpBk); echo '</pre>';
  // $tmpBk['events']['value'] = $tmpBk['events']['value'] . '| Event add by PHP ';
  // update_post_meta($test_id, '_product_attributes', $tmpBk);
} );