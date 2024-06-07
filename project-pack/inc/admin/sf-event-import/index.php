<?php 
/**
 * Salesforce Event import
 * 
 * @author Mike
 */
require( __DIR__ . '/import-handle.php' );

add_action('init', 'pp_register_sfevent_cpt');
function pp_register_sfevent_cpt() {
  $args = [
    'public'    => true,
    'label'     => __( 'SF Events', 'pp' ),
    'menu_icon' => 'dashicons-block-default',
    'supports'  => ['title', 'editor']
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
      <!-- React render -->
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
add_action('wp_ajax_nopriv_pp_ajax_get_sf_junctions_object', 'pp_ajax_get_sf_junctions_object');
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

  $product_exists = ppsf_check_product_create_by_junction_exists($junction_id);
  if($product_exists == false) {
    # Create variable product
    $product_parent_id = ppwc_event_create_variable_product([
      'junction_id' => $junction_id,
      'name' => $parent_event['Subject'],
    ]);

    # Create variation product
    ppwc_event_add_variation_product([
      'name' => $parent_event['Subject'],
      'wp_parent_event_id' => $wp_parent_event_id, 
      'wp_child_event_id' => $wp_child_event_id
    ], $product_parent_id);

    return $product_parent_id;
  } else {
    pp_log('Message: Product exists #' . $product_exists);
    return $product_exists;
  }
}

function ppsf_check_product_create_by_junction_exists($junction_id) {
  $res = get_posts([
    'post_type' => 'product',  
    'post_status' => 'any',
    'meta_query' => [
      [
        'key' => '_junction_id', // meta key name here
        'value' => $junction_id, 
        'compare' => '=',
      ]
    ]
  ]);

  return ($res && count($res) > 0) ? $res[0]->ID : false;
}

function ppsf_add_event($event_data) {
  $event_id = ppsf_find_event_by_sfevent_id($event_data['Id']);
  if($event_id == false) {
    // add new event
    $event_id = wp_insert_post([
      'post_type' => 'sf-event',
      'post_title' => wp_strip_all_tags($event_data['Subject']),
      'post_status' => 'publish',
    ]);

    $custom_fields = apply_filters('PPSF/EVENT_CUSTOM_FIELDS_FILTER', [
      'sf_event_id' => $event_data['Id'],
      'workshop_event_date_text__c' => $event_data['Workshop_Event_Date_Text__c'],
      'workshop_times__c' => $event_data['Workshop_Times__c'],
      'total_number_of_seats__c' => $event_data['Total_Number_of_Seats__c'],
      'remaining_seats__c' => $event_data['Remaining_Seats__c'],
      'startdatetime' => $event_data['StartDateTime'],
      'enddatetime' => $event_data['EndDateTime']
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

  return (($_posts && count($_posts)) > 0 ? $_posts[0]->ID : false);
}

function ppwc_get_all_events() {
  $events = get_posts([
    'post_type' => 'sf-event',
    'status' => 'any',
    'posts_per_page' => -1,
  ]);
  
  return ($events && count($events) > 0) ? array_map(function($e) {
    $p = (object) [
      'ID' => $e->ID,
      'post_status' => $e->post_status,
      'post_title' => $e->post_title,
      'sf_event_id' => get_post_meta($e->ID, 'sf_event_id', true),
      'workshop_event_date_text__c' => get_post_meta($e->ID, 'workshop_event_date_text__c', true),
      'workshop_times__c' => get_post_meta($e->ID, 'workshop_times__c', true),
      'total_number_of_seats__c' => get_post_meta($e->ID, 'total_number_of_seats__c', true),
      'remaining_seats__c' => get_post_meta($e->ID, 'remaining_seats__c', true),
      'startdatetime' => get_post_meta($e->ID, 'StartDateTime', true),
      'enddatetime' => get_post_meta($e->ID, 'EndDateTime', true),
    ];

    return $p;
  }, $events) : false;
}

add_action('wp_ajax_pp_ajax_get_all_events_imported', 'pp_ajax_get_all_events_imported');
add_action('wp_ajax_nopriv_pp_ajax_get_all_events_imported', 'pp_ajax_get_all_events_imported');

function pp_ajax_get_all_events_imported() {
  wp_send_json(ppwc_get_all_events());
}

function ppwc_get_all_product_events() {
  $products = get_posts([
    'post_type' => 'product',  
    'post_status' => 'any',
    'meta_query' => [
      [
        'key' => '_junction_id', // meta key name here
        'compare' => 'EXISTS',
      ]
    ]
  ]);

  return ($products && count($products) > 0) ? array_map(function($p) {
    $_product = wc_get_product( $p->ID );
    $variable = $_product->is_type('variable') ? true : false;
    $item = (object) [
      'ID' => $p->ID,
      'post_status' => $p->post_status,
      'post_title' => $p->post_title,
      '__is_product_variable' => $variable,
      '__sf_junction_id' => get_post_meta($p->ID, '_junction_id', true),
      '__product_edit_url' => get_edit_post_link($p->ID, ''),
    ];
    
    if($variable == true) {
      $_children_ids = $_product->get_children();
      if($_children_ids && count($_children_ids) > 0) {
        $item->__product_childrens = $_children_ids;
        foreach($_children_ids as $variation_id) {
          $wp_parent_event_id = get_post_meta($variation_id, 'wp_parent_event_id', true);
          $wp_child_event_id = get_post_meta($variation_id, 'wp_child_event_id', true);

          if(!empty($wp_parent_event_id) || !empty($wp_child_event_id)) {
            $item->__wp_event_parent_id = $wp_parent_event_id;
            $item->__wp_event_children_id = $wp_child_event_id;
            break;
          }
        }
      }
      
    }

    return $item;
  }, $products) : false;
}

function ppwc_product_sfevent_validate_import() {
  $events = ppwc_get_all_events();
  $products = ppwc_get_all_product_events();

  return $products ? array_map(function($p) use ($events) {
    
    if($events) {
      $_events = (array) $events;

      $p->__wp_event_parent_admin_url = get_edit_post_link($p->__wp_event_parent_id, '');
      $p->__wp_event_child_admin_url = get_edit_post_link($p->__wp_event_children_id, '');

      $e_parent_found_key = array_search($p->__wp_event_parent_id, array_column($_events, 'ID'));
      $e_child_found_key = array_search($p->__wp_event_children_id, array_column($_events, 'ID'));
      $p->__sf_event_parent_id = $events[$e_parent_found_key]->sf_event_id;
      $p->__sf_event_child_id = $events[$e_child_found_key]->sf_event_id;
    }

    return $p;
  }, $products) : false;
}

function ppwc_ajax_product_sfevent_validate_import() {
  wp_send_json(ppwc_product_sfevent_validate_import());
}

add_action('wp_ajax_ppwc_ajax_product_sfevent_validate_import', 'ppwc_ajax_product_sfevent_validate_import');
add_action('wp_ajax_nopriv_ppwc_ajax_product_sfevent_validate_import', 'ppwc_ajax_product_sfevent_validate_import');

function pp_get_event_data_by_id($eID) {
  $e = get_post($eID);
  return [
    'ID' => $e->ID,
    'post_status' => $e->post_status,
    'post_title' => $e->post_title,
    'sf_event_id' => get_post_meta($e->ID, 'sf_event_id', true),
    'workshop_event_date_text__c' => get_post_meta($e->ID, 'workshop_event_date_text__c', true),
    'workshop_times__c' => get_post_meta($e->ID, 'workshop_times__c', true),
    'total_number_of_seats__c' => get_post_meta($e->ID, 'total_number_of_seats__c', true),
    'remaining_seats__c' => get_post_meta($e->ID, 'remaining_seats__c', true),
    'startdatetime' => get_post_meta($e->ID, 'startdatetime', true),
    'enddatetime' => get_post_meta($e->ID, 'enddatetime', true),
  ];
}

add_action('wp_ajax_pp_ajax_get_events', 'pp_ajax_get_events');
add_action('wp_ajax_nopriv_pp_ajax_get_events', 'pp_ajax_get_events');

function pp_ajax_get_events() {
  wp_send_json(ppsf_get_events());
}

add_action('wp_ajax_pp_ajax_get_all_products', 'pp_ajax_get_all_products');
add_action('wp_ajax_nopriv_pp_ajax_get_all_products', 'pp_ajax_get_all_products');

function pp_ajax_get_all_products() {
  wp_send_json(ppsf_get_all_products());
}

function pp_prepare_data_import_events() {
  $all_events = ppsf_get_events();
  $all_products = ppsf_get_all_products();
  $all_junctions = ppsf_get_junctions();
  // wp_send_json($all_junctions);
  // wp_send_json( [$all_events['records'], $all_products['records']] );
  $product_need_import = [];

  foreach($all_products['records'] as $p) {
    $pid = $p['Id'];
    // $found = array_search($pid, array_column($all_events['records'], 'WhatId'));
    $eIDs = array_filter($all_events['records'], function($eItem) use ($pid) {
      return $eItem['WhatId'] == $pid;
    });

    if($eIDs && count($eIDs) > 0) {

      array_map(function($eItem) use ($all_junctions) {

        if(count($all_junctions['records'])) {
          // check event parent or child here.
        }

        return $eItem;
      }, $eIDs);

      $p['__events'] = $eIDs;
      $product_need_import[$pid] = $p;
    }
  }

  return apply_filters('PPSF:PRODUCT_NEED_IMPORT', $product_need_import);
}

add_filter('PPSF:PRODUCT_NEED_IMPORT', function($SF_Product2_import) {
  $Pricebook2_data = ppsf_get_Pricebook2();
  $Pricebook2 = $Pricebook2_data['records'];

  $SF_Product2_import = array_map(function($p) use($Pricebook2) {
    $prices = ppsft_get_PricebookEntry_by_Product2ID($p['Id']);
    $records = isset($prices['records']) ? $prices['records'] : [];
    $p['__prices'] = array_map(function($r) use($Pricebook2) {
      $found_key = array_search($r['Pricebook2Id'], array_column($Pricebook2, 'Id'));

      return [
        'Id' => $r['Id'],
        'Pricebook2Id' => $r['Pricebook2Id'],
        'Product2Id' => $r['Product2Id'],
        'UnitPrice' => $r['UnitPrice'],
        'Name' => $r['Name'],
        'Pricebook2' => isset($Pricebook2[$found_key]) ? $Pricebook2[$found_key] : [],
      ];
    }, $records);
    return $p;
  }, $SF_Product2_import);

  return $SF_Product2_import;
});

add_action('wp_ajax_pp_ajax_prepare_data_import_events', 'pp_ajax_prepare_data_import_events');
add_action('wp_ajax_nopriv_pp_ajax_prepare_data_import_events', 'pp_ajax_prepare_data_import_events');

function pp_ajax_prepare_data_import_events() {
  wp_send_json(pp_prepare_data_import_events());
}

add_action('wp_ajax_pp_ajax_get_EventRelation_by_event_Id', 'pp_ajax_get_EventRelation_by_event_Id');
add_action('wp_ajax_nopriv_pp_ajax_get_EventRelation_by_event_Id', 'pp_ajax_get_EventRelation_by_event_Id');

function pp_ajax_get_EventRelation_by_event_Id() {
  $eventId = $_POST['event_id'];
  $EventRelation = ppsf_get_EventRelation_by_event_Id($eventId);
  
  $records = array_map(function($item) {
    $item['account_info'] = ppsf_get_contact($item['RelationId']);
    return $item;
  }, $EventRelation['records']);

  wp_send_json($records); 
}

# For test
add_action( 'init', function() {
  // if (($_GET['test'] == 'test')) { 
  //   $sf_pricebook2_id = '01sOn0000001iOnIAI';
  //   $sf_product2_id = '01tOn000003dWntIAE';
  //   echo "<pre>";
  //   // print_r(ppsft_get_PricebookEntry_by_Product2ID('01tOn000003dWntIAE'));
  //   print_r(ppsf_get_unitprice_from_pricebook_entry($sf_pricebook2_id, $sf_product2_id));
  //   // print_r(get_post_meta(42585, 'product_role_based_price_PRIMARY_MEMBERS', true));
  //   // update_post_meta( 42585, 'product_role_based_price_PRIMARY_MEMBERS', '100' );
  //   echo "</pre>";
  // }
  if(!isset($_GET['test_import'])) return;

  // $junction_id = 'a1y9h000000Ar6HAAS'; 
  // print_r(ppsf_check_product_create_by_junction_exists($junction_id));
  // ppsf_import_by_junction_id($junction_id);
  // echo '<pre>'; print_r(ppwc_product_sfevent_validate_import()); echo '</pre>';
  // echo 'TEST import...!'; 

  // $__role_based_pricing = get_field('__role-based_pricing', 'option');
  // print_r($__role_based_pricing);
} );
# End for test