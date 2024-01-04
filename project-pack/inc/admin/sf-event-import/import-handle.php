<?php 
/**
 * Import Product Handle
 */

function ppsf_event_create_product_parent($data) {
  $product_sfid = $data['Id'];
  $pID = ppsf_event_check_product_parent_exists($product_sfid);

  // is exists
  if($pID !== false) return $pID;

  // not exists, create new
  $default = [
    'Description' => '',
    'Family' => '',
    'Id' => '',
    'Name' => '',
    'ProductCode' => '',
    'Woocommerce_Description__c' => '',
  ];
  $_args = wp_parse_args($data, $default); 
  $product = new WC_Product_Variable();
  // Name and Description
  $product->set_name($_args['Name']);
  $product->set_description($_args['Woocommerce_Description__c']);

  if(!empty($_args['ProductCode'])) {
    $product->set_sku('ProductCode');
  } 

  // Event attribute
  $attribute = new WC_Product_Attribute();
  $attribute->set_name('Events');
  $attribute->set_options([]);
  $attribute->set_position(0);
  $attribute->set_visible(1);
  $attribute->set_variation(1);
  
  $product->set_attributes([$attribute]); 
  $product->save();

  // log 
  pp_log('Message: Added product variable successfully #' . $product->get_id());

  update_post_meta($product->get_id(), '__sf_product_id', $_args['Id']); 
  update_post_meta($product->get_id(), '__sf_family', $_args['Family']); 
  update_post_meta($product->get_id(), '__sf_productcode', $_args['ProductCode']); 

  do_action( 'PPSF/after_add_product_parent_hook', $product, $product->get_id(), $_args);
  return $product->get_id();
}

function ppsf_event_check_product_child_exists_by_wpeventid($WpEventId, $productParentId, $s_metafield = 'wp_parent_event_id') {
  $args = [
    'post_type'      => 'product_variation',
    'post_status'    => 'any',
    'posts_per_page' => 1,
    'post_parent__in'=> [$productParentId],
    'meta_query'     => [
      [
        'key'         => $s_metafield,
        'value'       => $WpEventId,
        'compare'     => '='
      ]
    ]
  ];

  $res = get_posts($args);
  return ($res && count($res) > 0) ? $res[0]->ID : false; 
}

function ppsf_event_check_product_child_exists_by_junctionid($productParentId, $junction_id) {
  $args = [
    'post_type'      => 'product_variation',
    'post_status'    => 'any',
    'posts_per_page' => 1,
    'post_parent__in'=> [$productParentId],
    'meta_query'     => [
      [
        'key'         => '__junctions_id',
        'value'       => $junction_id,
        'compare'     => '='
      ]
    ]
  ];

  $res = get_posts($args);
  return ($res && count($res) > 0) ? $res[0]->ID : false; 
}

function ppsf_event_add_product_child($data, $productParentId, $prices = []) {
  $default = [
    // 'DurationInMinutes' => '',
    // 'Id' => '',
    // 'Remaining_Seats__c' => '',
    'Subject' => '',
    // 'Total_Number_of_Seats__c' => '',
    // 'WhatId' => '',
    // 'WhoId' => '',
    // 'Workshop_Event_Date_Text__c' => '',
    // 'Workshop_Times__c' => '',
    '__event_type' => '',
    '__junctions_id' => '',
    'WpEventId' => '',
  ];
  $_args = wp_parse_args($data, $default);
  
  $base_price_id = ppsf_base_Pricebook2_base_price_id();
  $found_key = array_search($base_price_id, array_column($prices, 'Pricebook2Id'));
  $UnitPrice = '';

  if($found_key !== false) {
    $UnitPrice = floatval($prices[$found_key]['UnitPrice']);
  }
  // wp_send_json( [$UnitPrice] );

  $WpEventId = $_args['WpEventId'];
  $opt_name = $_args['Subject'];
  $__junctions_id = $_args['__junctions_id'];
  $__event_type = $_args['__event_type'];
  $Total_Number_of_Seats__c = (int) $_args['Total_Number_of_Seats__c'];
  $Remaining_Seats__c = (int) $_args['Remaining_Seats__c'];
  $stock_quantity = ($Total_Number_of_Seats__c - $Remaining_Seats__c);

  $mapFields = [
    '__PARENT__' => 'wp_parent_event_id',
    '__CHILDREN__' => 'wp_child_event_id',
    '' => 'wp_parent_event_id',
  ];

  $update_field = $mapFields[$__event_type];

  if(!$update_field) {
    return [
      'error' => true,
      'message' => __('Update field wrong!')
    ];
  }

  $WpProductChildId = ppsf_event_check_product_child_exists_by_wpeventid($WpEventId, $productParentId, $update_field);
  
  // Product child exists
  if($WpProductChildId !== false) {
    return $WpProductChildId;
  }

  // Check if Junction_Workshop_Event__c type
  if(in_array($__event_type, ['__PARENT__', '__CHILDREN__'])) {
    $WpProductChildId = ppsf_event_check_product_child_exists_by_junctionid($productParentId, $__junctions_id);
    
    if($WpProductChildId !== false) {
      update_post_meta($WpProductChildId, $update_field, $WpEventId);
      return $WpProductChildId;
    }
  }
  // End check if Junction_Workshop_Event__c type

  ppwc_event_add_product_attr_opts($productParentId, $opt_name);

  $variation = new WC_Product_Variation();
  $variation->set_parent_id($productParentId);
  $variation->set_attributes(['events' => $opt_name]);
  $variation->set_name($opt_name);   

  // set price $UnitPrice
  if($UnitPrice) {
    $variation->set_regular_price($UnitPrice);
  } 
  

  $variation->set_manage_stock(true); 
  $variation->set_stock_quantity((int) $stock_quantity); 

  $variation->save(); 

  do_action('PPSF:AFTER_IMPORT_VARIATION', $variation->get_id(), $productParentId, $prices);

  pp_log('Message: Added product variation successfully #' . $variation->get_id());

  // Update meta fields
  $meta_fields = apply_filters( 'PPSF/event_import_meta_fields_filter', [
    $update_field => $WpEventId,
    '__junctions_id' => $__junctions_id,
    // 'wp_child_event_id' => $_args['wp_child_event_id'], 
  ], $_args); 

  foreach($meta_fields as $name => $value) {
    update_post_meta($variation->get_id(), $name, $value);
  }

  do_action( 'PPSF/after_add_variation_hook', $variation, $variation->get_id(), $_args);
  return $variation->get_id();
}

function ppwc_event_add_product_attr_opts($pid, $name) {
  $attributes = get_post_meta($pid, '_product_attributes', true);

  // add new item opt
  $eventOptions = explode(' | ', $attributes['events']['value']);
  array_push($eventOptions, $name);

  $attributes['events']['value'] = implode(' | ', $eventOptions);
  update_post_meta($pid, '_product_attributes', $attributes);
}

function ppsf_event_check_product_parent_exists($product_sfid) {
  $res = get_posts([
    'post_type' => 'product',  
    'post_status' => 'any',
    'meta_query' => [
      [
        'key' => '__sf_product_id', // meta key name here
        'value' => $product_sfid, 
        'compare' => '=',
      ]
    ]
  ]);

  return ($res && count($res) > 0) ? $res[0]->ID : false;
}

function ppsf_event_check_wpevent_exists_by_sfevent_id($sfevent_id) {
  $_posts = get_posts([
    'post_type' => 'sf-event',
    'status' => 'any',
    'meta_query' => [
      [
        'key'   => 'sf_event_id',
        'value' => $sfevent_id,
        'compare' => '='
      ]
    ]
  ]);

  return (($_posts && count($_posts)) > 0 ? $_posts[0]->ID : false);
}

function ppsf_event_import_sfevent_to_wpevent_cpt($eventData = []) {
  $sfEventId = $eventData['Id'];
  $WpEventId = ppsf_event_check_wpevent_exists_by_sfevent_id($sfEventId);

  // is exists
  if($WpEventId !== false) return $WpEventId;

  $event_id = wp_insert_post([
    'post_type' => 'sf-event',
    'post_title' => wp_strip_all_tags($eventData['Subject']),
    'post_status' => 'publish',
    'post_content' => isset($eventData['Description']) ? $eventData['Description'] : ''
  ]);

  $custom_fields = apply_filters('PPSF/EVENT_CUSTOM_FIELDS_FILTER', [
    'sf_event_id' => $eventData['Id'],
    'workshop_event_date_text__c' => $eventData['Workshop_Event_Date_Text__c'],
    'workshop_times__c' => $eventData['Workshop_Times__c'],
    'total_number_of_seats__c' => $eventData['Total_Number_of_Seats__c'],
    'remaining_seats__c' => $eventData['Remaining_Seats__c'],
    'whatid' => $eventData['WhatId'],
    'whoid' => $eventData['WhoId'],
    'startdatetime' => $eventData['StartDateTime'],
    'enddatetime' => $eventData['EndDateTime']
  ], $eventData);

  foreach($custom_fields as $name => $value) {
    update_post_meta($event_id, $name, $value);
  }

  pp_log('Message: Insert sf event #' . $eventData['Id'] . ' => wp event id #' . $event_id);

  return $event_id;
}

function ppsf_event_product_import($data) {
  // wp_send_json($data);
  $WooProductParentId = ppsf_event_create_product_parent($data);
  $__prices = $data['__prices'];
  $result = [
    'parent' => [
      'product_sfid' => $data['Id'],
      'woo_product_parent_id' => $WooProductParentId,
    ],
    'childrens' => []
  ];

  if(isset($data['__events']) && count($data['__events']) > 0) {
    foreach($data['__events'] as $eItem) {
      if($eItem['__ready_import'] == 'false') {
        continue;
      }

      $WpEventId = ppsf_event_import_sfevent_to_wpevent_cpt($eItem);
      $eItem['WpEventId'] = $WpEventId;
      $WpProductChildId = ppsf_event_add_product_child($eItem, $WooProductParentId, $__prices); 

      array_push($result['childrens'], [
        'event_sfid' => $eItem['Id'],
        'wp_event_id' => $WpEventId,
        'woo_product_child_id' => $WpProductChildId, 
      ]);
    }
  }

  return $result;
}

function ppsf_event_get_wpevent_data_by_id($WpEventId) {
  // $p = get_post($WpEventId);
  return [
    'sf_event_id' => get_post_meta($WpEventId, 'sf_event_id', true),
    'workshop_event_date_text__c' => get_post_meta($WpEventId, 'workshop_event_date_text__c', true),
    'workshop_times__c' => get_post_meta($WpEventId, 'workshop_times__c', true),
    'total_number_of_seats__c' => get_post_meta($WpEventId, 'total_number_of_seats__c', true),
    'remaining_seats__c' => get_post_meta($WpEventId, 'remaining_seats__c', true),
    'startdatetime' => get_post_meta($WpEventId, 'StartDateTime', true),
    'enddatetime' => get_post_meta($WpEventId, 'EndDateTime', true),
    'whatid' => get_post_meta($WpEventId, 'whatid', true),
    'whoid' => get_post_meta($WpEventId, 'whoid', true),
  ];
}

function ppsf_event_get_all_child_products_by_parent_id($productParentId) {
  $args = [
    'post_type'      => 'product_variation',
    'post_status'    => 'any',
    'posts_per_page' => -1,
    'post_parent__in'=> [$productParentId],
  ];

  $products = get_posts($args);
  if(!$products || count($products) == 0) return [];

  $result = [];
  foreach($products as $pItem) {
    $WpEventParentId = get_post_meta($pItem->ID, 'wp_parent_event_id', true);
    $WpEventChildId = get_post_meta($pItem->ID, 'wp_child_event_id', true);
    $eventParent = ppsf_event_get_wpevent_data_by_id($WpEventParentId);
    $eventChild = ppsf_event_get_wpevent_data_by_id($WpEventChildId);

    array_push($result, [
      'event_sfid' => $eventParent['sf_event_id'],
      'event_child_sfid' => $eventChild['sf_event_id'],
      'wp_event_id' => $WpEventParentId,
      'wp_event_child_id' => $WpEventChildId,
      'woo_product_child_id' => $pItem->ID, 
      'event_edit_url' => get_edit_post_link($WpEventParentId, '&'),
      'event_child_edit_url' => get_edit_post_link($WpEventChildId, '&'),
    ]);
  }

  return $result;
}

function ppsf_event_validate_products_import_exists() {
  $products = get_posts([
    'post_type' => 'product',  
    'post_status' => 'any',
    'meta_query' => [
      [
        'key' => '__sf_product_id', // meta key name here
        'compare' => 'EXISTS',
      ]
    ]
  ]);

  if(!$products || count($products) == 0) return [];
  $result = [];

  foreach($products as $key => $pItem) {
    array_push($result, [
      'parent' => [
        'product_sfid' => get_post_meta($pItem->ID, '__sf_product_id', true),
        'woo_product_parent_id' => $pItem->ID,
        'product_edit_url' => get_edit_post_link($pItem->ID, '&'),
      ],
      'childrens' => ppsf_event_get_all_child_products_by_parent_id($pItem->ID),
    ]);
  }

  return $result;
}

add_action('wp_ajax_pp_ajax_ppsf_event_validate_products_import_exists', 'pp_ajax_ppsf_event_validate_products_import_exists');
add_action('wp_ajax_nopriv_pp_ajax_ppsf_event_validate_products_import_exists', 'pp_ajax_ppsf_event_validate_products_import_exists');

function pp_ajax_ppsf_event_validate_products_import_exists() {
  wp_send_json(ppsf_event_validate_products_import_exists());
}

add_action('wp_ajax_pp_ajax_ppsf_event_product_import', 'pp_ajax_ppsf_event_product_import');
add_action('wp_ajax_nopriv_pp_ajax_ppsf_event_product_import', 'pp_ajax_ppsf_event_product_import');

function pp_ajax_ppsf_event_product_import() {
  $productData = $_POST['data'];
  $res = ppsf_event_product_import($productData);
  wp_send_json($res);
}

add_action( 'init', function() {
  if(!isset($_GET['__test_event_import'])) return;
  // $WpEventId, $productParentId
  var_dump(ppsf_event_validate_products_import_exists());
} );

/**
 * Add Category (Proccess import) 
 */
function pp_find_cat_by_name($name, $cat = '') {
  $cat = get_term_by('name', $name, $cat);

	if($cat) {
		return $cat->term_id;
	}

	return 0;
}

add_action('PPSF/after_add_product_parent_hook', 'ppsf_set_product_category', 20, 3);

function ppsf_set_product_category($product, $product_id, $args) {
  $catName = $args['Family'];
  if(empty($catName)) return;

  $catID = pp_find_cat_by_name($catName, 'product_cat');
  if(!$catID) {
    $term = wp_insert_term(
      $catName, // the term 
      'product_cat' // the taxonomy
    );
    $catID = $term['term_id'];
  }

  wp_set_object_terms($product_id, $catID, 'product_cat');
  pp_log('Message: Added product '. $product_id .' to category ' . $catName . '('. $catID .')');
}
/**
 * End add Category (Proccess import) 
 */