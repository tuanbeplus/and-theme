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

  // Add parent product (Variable)
  $parent_id = ppsf_add_product_variable_by_event_id($res['Parent_Event__c']);

  // Add child product (Variation)
  $variation_id_p = ppsf_add_product_variation_by_event_id($res['Parent_Event__c'], $parent_id);
  $variation_id_c = ppsf_add_product_variation_by_event_id($res['Child_Event__c'], $parent_id);

  pp_log('Product IDs: '. $parent_id .' & ' . $variation_id);
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

  // pp_log('INFO Parent Event: ' . wp_json_encode($eventData));
  return ppwc_create_variable_product([
    'sf_event_id' => $eventData['Id'],
    'name' => $eventData['Subject'],
  ]);
}

function ppsf_add_product_variation_by_event_id($event_id, $product_parent_id = 0) {
  if(empty($product_parent_id)) {
    pp_log('ERROR: ppsf_add_product_variation_by_event_id => $product_parent_id empty');
    return;
  }

  $eventData = ppsf_get_event($event_id);
  if(!isset($eventData['Id'])) {
    pp_log('ERROR: fn ppsf_add_product_variation_by_event_id | Children event '. $res['Child_Event__c'] .' not found ' . wp_json_encode($eventData));
    return false;
  } 

  // pp_log('INFO Children Event: ' . wp_json_encode($eventData));
  return ppwc_add_variation_product([
    'sf_event_id' => $eventData['Id'],
    'name' => $eventData['Subject'],
  ], (int) $product_parent_id);
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