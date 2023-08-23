<?php 
/**
 * Helpers
 * 
 * @since 1.0.0
 * @version 1.0.0
 */

function pp_load_template($name, $require_once = false) {
  load_template( PP_DIR . '/templates/' . $name . '.php', $require_once );
}

function pp_the_shop_landing_template() {
  global $post;
  
  set_query_var( 'template_data', apply_filters( 'pp/shop_lading_template_data', [
    'title' => get_the_title( $post ),
    'feature_image_url' => get_field( 'feature_image', $post->ID ),
    'faqs' => get_field( 'faqs_loop', $post->ID ),
    'content' => get_field( 'shop_content', $post->ID ),
  ] ) );
  
  pp_load_template('shop-landing');
}

function pp_query_product($args = []) {
  $default = [
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => 6,
    'paged' => 1,
    'orderby' => 'date',
    'order' => 'DESC',
  ];

  $param = wp_parse_args($args, $default);
  return new WP_Query($param);
}

function pp_get_product_cats() {
  $args = array(
    'taxonomy'     => 'product_cat',
    'orderby'      => 'name',
  );

  return get_categories( $args );
}

function pp_the_product_single_template() {
  global $product;
  pp_load_template('product-single-content');
}

function pp_custom_addtocart_button_text( $text, $product ) {
  if( $product->is_type( 'variable' ) ){
    $text = __('Select slots', 'woocommerce');
  }
  return $text;
}

function pp_header_button_actions() {
  pp_load_template('header-button-actions');
}

function pp_product_landing_load_init($params = []) {
  $params = wp_parse_args($params, [
    'term' => '',
    's' => '',
    'posts_per_page' => 6,
    'paged' => 1,
    'loadmore' => true
  ]);

  $case = '';

  if($params['term']) {
    $case = 'PRODUCT_WITH_TERM_TEMPLATE';
  } else {
    $case = 'INIT_TEMPLATE';

    if($params['s']) {
      $case = 'PRODUCT_NO_TERM_WIDTH_KEYWORDS_TEMPLATE';
    }
  }
  // var_dump($params);
  // echo $case;

  switch ($case) {
    case 'PRODUCT_WITH_TERM_TEMPLATE':
      /**
       * This case 
       * Query product by termID & keywords if exists
       * Loadmore ajax
       */
      $term = get_term_by('ID', (int) $params['term'], 'product_cat');
      return pp_product_landing_v2_group_per_term_template($term, $params);
      break;

    case 'PRODUCT_NO_TERM_WIDTH_KEYWORDS_TEMPLATE':
      /**
       * This case 
       * Load all product don't group by term & filter by keywords
       * Loadmore ajax
       */
      return pp_product_landing_v2_no_term_template($params);
      break;

    case 'INIT_TEMPLATE':
      /**
       * Template default 
       * Group products by tearm
       * Loadmore product for each term section 
       */
      return pp_product_landing_v2_group_by_terms_template($params);
      break;
  }
}

function pp_product_landing_v2_no_term_template($params) {
  $_query = pp_query_product([
    'posts_per_page' => $params['posts_per_page'],
    'paged' => $params['paged'],
    's' => $params['s']]);

  ob_start();
  echo '<div 
    class="product-landing-v2-no-term"
    data-items="' . $params['posts_per_page'] . '"
    data-max-numpage="'. $_query->max_num_pages .'"
    data-current-page="1" >';
  pp_get_products_tag($_query);
  if ($params['loadmore'] && $_query->max_num_pages > 1) :
    echo '<button class="btn-loadmore">' . __('View more', 'pp') . '</button>';
  endif;
  echo '</div>';
  return ob_get_clean();
}

function pp_product_landing_v2_group_by_terms_template($params) {
  $terms = pp_get_product_cats();
  if(!$terms && count($terms) == 0) return;
  ob_start();
  foreach($terms as $index => $term) { 
    echo pp_product_landing_v2_group_per_term_template($term, $params);
  }
  $_html = ob_get_clean();
  return $_html;
}

function pp_product_landing_v2_group_per_term_template($term, $params) {
  ob_start();
  $term_html = "<div class=\"term-html-each\">
    <h2>$term->name</h2>" 
    . wpautop($term->description) . 
  "</div>";
  $_query = pp_query_product([
    'posts_per_page' => $params['posts_per_page'],
    'paged' => $params['paged'],
    's' => $params['s'],
    'tax_query' => [
      [
        'taxonomy' => 'product_cat',
        'field' => 'term_id',
        'terms' => $term->term_id,
        'include_children' => true,
        'operator' => 'IN'  
      ]
    ]]);
  echo '<div
    data-items="' . $params['posts_per_page'] . '"
    data-max-numpage="'. $_query->max_num_pages .'"
    data-current-page="1"
    data-term-id="' . $term->term_id . '" 
    id="TERM_'. $term->slug .'" 
    class="product-by-group-term">';
  echo $term_html;
  pp_get_products_tag($_query);
  if ($params['loadmore'] && $_query->max_num_pages > 1) :
    echo '<button class="btn-loadmore">' . __('View more', 'pp') . '</button>';
  endif;
  echo '</div> <!-- .product-by-group-term -->';
  return ob_get_clean();
}

function pp_saleforce_current_user_metadata() {
  $user = wp_get_current_user();
  if(!$user) return;

  return [
    'wp_user_id' => $user->ID,
    'sf_user_id' => get_user_meta($user->ID, '__salesforce_user_id', true),
    'sf_access_token' => get_user_meta($user->ID, '__salesforce_access_token', true),
    'sf_profile_id' => get_user_meta($user->ID, '__salesforce_profile_id', true),
    'sf_account_id' => get_user_meta($user->ID, '__salesforce_account_id', true),
    'email' => $user->user_email,
  ];
}

function pp_organisation_details_template($wp_user_id) {
  $sf_account_json = get_user_meta($wp_user_id, '__salesforce_account_json', true);
  $sf_contact_id = get_user_meta($wp_user_id, 'salesforce_contact_id', true);
  $sf_account_id = get_user_meta($wp_user_id, '__salesforce_account_id', true);
  $sf_account_data = json_decode($sf_account_json, true);
  $sf_contact_data = ppsf_get_contact($sf_contact_id);
  $sf_opportunity_data = ppsf_get_opportunity($sf_account_id);

  $sf_org_details_arr = array(
    'Name' => $sf_account_data['Name'],
    'Type' => $sf_account_data['Type'],
    'Membership Level' => $sf_account_data['Membership_Level__c'],
    'Member Hours Remaining' => $sf_account_data['Memb_Hours_Remain_Org__c'],
    'Membership Renewal Month' => $sf_account_data['Membership_Renewal_Month__c'],
    'Membership Status' => $sf_account_data['Membership_Status__c'],
  );

  $member_journey_arr = array(
    'Maintains_an_Employee_Network__c' => $sf_account_data['Maintains_an_Employee_Network__c'],
    'Recruitment_Review__c' => $sf_account_data['Recruitment_Review__c'],
    'Workplace_Adjustment_Policy_or_Procedure__c' => $sf_account_data['Workplace_Adjustment_Policy_or_Procedure__c'],
    'Accessibility_Action_Plan_in_place__c' => $sf_account_data['Accessibility_Action_Plan_in_place__c'],
  );

  $sf_contact_arr = array(
    'Contact name' => $sf_contact_data['Salutation'] .' '. $sf_contact_data['Name'],
    'Email' => '<a href="mailto:'. $sf_contact_data['Email'] .'">'. $sf_contact_data['Email'] .'</a>',
    'Phone number' => '<a href="tel:'. $sf_contact_data['Phone'] .'">'. $sf_contact_data['Phone'] .'</a>',
  );

  set_query_var( 'template_org_data', apply_filters( 'pp/org_details_template_data', [
    'org_details' => $sf_org_details_arr,
    'member_journey' => $member_journey_arr,
    'contact' => $sf_contact_arr,
    'opportunity' => $sf_opportunity_data['records'],
  ]));

  if (! empty($sf_account_data)) {
    pp_load_template('organisation-details');
  }
}



