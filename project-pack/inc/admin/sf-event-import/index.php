<?php 
/**
 * Salesforce Event import
 * 
 * @author Mike
 */

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
  if(isset($res['Id'])) {
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

  // Add parent product (Variable)
  $parent_id = ppsf_add_product_variable_by_event_id($res['Parent_Event__c']);

  // Add child product (Variation)
  $variation_id = ppsf_add_product_variation_by_event_id($res['Child_Event__c']);
}

function ppsf_find_product_exists_by_event_id($event_id) {
  $args = [
    'post_type'      => 'product_variation',
    'post_status'    => 'any',
    'posts_per_page' => -1,
    'meta_query'     => [
      [
        'key'     => '_sf_event_id',
        'value'   => $event_id,
        'compare' => '='
      ]
    ]
  ];

  return get_posts($args);
}

function ppsf_add_product_variable_by_event_id($event_id) {
  $eventData = ppsf_get_event($event_id);
  if(!isset($eventData['Id'])) {
    pp_log('ERROR: fn ppsf_add_product_variable_by_event_id | Parent event '. $res['Parent_Event__c'] .' not found ' . wp_json_encode($eventData));
    return false;
  }
}

function ppsf_add_product_variation_by_event_id($event_id, $product_parent_id) {
  $eventData = ppsf_get_event($event_id);
  if(!isset($eventData['Id'])) {
    pp_log('ERROR: fn ppsf_add_product_variation_by_event_id | Children event '. $res['Child_Event__c'] .' not found ' . wp_json_encode($eventData));
    return false;
  }
}