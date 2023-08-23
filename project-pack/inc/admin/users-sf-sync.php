<?php 
/**
 * Users Salesforce Sync
 * 
 * @since 1.0.0
 * @author Mike
 */

function pp_user_register_column( $column ) {
  $column['pull_sf_user_data'] = __('Pull user data (from Salesforce)', 'pp');
  return $column;
}
add_filter( 'manage_users_columns', 'pp_user_register_column' );

function pp_user_column_value( $val, $column_name, $user_id ) {
  switch ($column_name) {
    case 'pull_sf_user_data' :
      $SF_UID = get_user_meta($user_id, '__salesforce_user_id', true);
      $salesforce_contact_id = get_user_meta($user_id, 'salesforce_contact_id', true);
      $SF_UID = $SF_UID ? $SF_UID : $salesforce_contact_id;
      if(!$SF_UID) return __('Not yet connect (Salesforce User)');

      ob_start();
      ?>
      <button
        title="<?php echo 'Pull user data #' . $SF_UID ?>" 
        class="button pp-pull-sf-user-data" 
        data-wpuid="<?php echo $user_id; ?>"
        data-sfuid="<?php echo $SF_UID; ?>">
        <?php _e('Pull User Data', 'pp') ?>
      </button>
      <br />
      <div>Last updated: <span class="ppsf-user-last-update"><?php echo get_user_meta( $user_id, '__sf_last_updated_userinfo', true ) ?></span></div>
      <?php
      return ob_get_clean();
  }

  return $val;
}
add_filter( 'manage_users_custom_column', 'pp_user_column_value', 10, 3 );

function pp_update_user_meta($userId, $args = []) {
  if ( ! empty( $args ) ) {
    foreach ( $args as $key => $value ) {
      update_user_meta( $userId, $key, $value );
    }
  }
}

function pp_set_role_by_profileid($user_id, $profile_id) {
  $roleObject = pp_get_role_item($profile_id, 'code');
  if(!$roleObject) return;

  $wp_user_object = new WP_User($user_id);
  $wp_user_object->set_role($roleObject['name']); // set role
}

function pp_user_table_row_update_fragment($wpuid) {
  $rootClasses = 'tr#user-' . $wpuid;
  $user_info = get_userdata( $wpuid );
  return apply_filters( 'pp/user_columns_update_fragment', [
    $rootClasses . ' td.column-name' => $user_info->display_name,
    $rootClasses . ' td.column-email' => sprintf('<a href="mailto:%1$s">%1$s</a>', $user_info->user_email),
    $rootClasses . ' td.column-role' => implode(', ', $user_info->roles),
    $rootClasses . ' span.ppsf-user-last-update' => get_user_meta( $wpuid, '__sf_last_updated_userinfo', true ),
  ], $wpuid );
}

function pp_ajax_request_sf_user_data() {
  // wp_send_json( $_POST );
  $response = ppsf_get_user($_POST['sfuid']);

  if(isset($response[0]) && isset($response[0]['errorCode'])) {
    wp_send_json( [
      'success' => false,
      'response' => $response[0],
    ] );
  }

  list(
    'Username' => $Username,
    'LastName' => $LastName,
    'FirstName' => $FirstName,
    'Email' => $Email, 
    'ProfileId' => $ProfileId,
    'Street' => $Street,
    'City' => $City,
    'State' => $State,
    'PostalCode' => $PostalCode,
    'Country' => $Country,
    'Address' => $Address,
    'Phone' => $Phone,
    'CompanyName' => $CompanyName,
    'AccountId' => $AccountId,
    'ContactId' => $ContactId,
  ) = $response ;

  $accountInfo = ppsf_get_account($AccountId);

  $wpuid = (int) $_POST['wpuid'];
  
  # Update user name
  wp_update_user([
    'ID' => $wpuid, 
    'first_name' => $FirstName,
    'last_name' => $LastName,
    'display_name' => $FirstName . ' ' . $LastName,
  ]);

  # Update user meta
  pp_update_user_meta($wpuid, [
    'billing_address_1' => $accountInfo['BillingStreet'] ?? '',
    // 'billing_address_2' => '',
    'billing_city' => $accountInfo['BillingCity'] ?? '',
    'billing_company' => $accountInfo['Name'] ?? '',
    'billing_country' => $accountInfo['BillingCountry'] ?? '',
    'billing_email' => $Email ?? '',
    'billing_first_name' => $FirstName ?? '',
    'billing_last_name' => $LastName ?? '',
    'billing_phone' => $Phone ?? '',
    'billing_postcode' => $accountInfo['BillingPostalCode'] ?? '',
    'billing_state' => $accountInfo['BillingState'] ?? '',
    '__sf_last_updated_userinfo' => current_time('mysql'), // last updated timestamp
    'salesforce_contact_id' => $ContactId ?? '',
    '__salesforce_account_id' => $AccountId ?? '',
    '__salesforce_account_json' => wp_json_encode( $accountInfo )
  ]);

  # Update user role
  pp_set_role_by_profileid($wpuid, $ProfileId);

  wp_send_json( [
    'success' => true, 
    'response' => [
      'User' => $response,
      'Account' => $accountInfo,
    ],
    'updated_columns' => pp_user_table_row_update_fragment($wpuid),
  ] );
} 

add_action('wp_ajax_pp_ajax_request_sf_user_data', 'pp_ajax_request_sf_user_data');
add_action('wp_ajax_nopriv_pp_ajax_request_sf_user_data', 'pp_ajax_request_sf_user_data');

function pp_user_custom_metadata_box($user) {
  pp_organisation_details_template($user->ID);
}

add_action('show_user_profile', 'pp_user_custom_metadata_box');
add_action('edit_user_profile', 'pp_user_custom_metadata_box');