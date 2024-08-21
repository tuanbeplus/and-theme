<?php
function fn_wp_user_exists($email = '') {
    return email_exists($email);
}
  
function fn_salesforce_access_token() {
    return get_field('salesforce_api_access_token', 'option');
}
  
function fn_get_salesforce_user($sf_uid = 0) {
    $access_token = fn_salesforce_access_token();
    $ver = get_field('salesforce_api_version', 'option');
    $endpoint = get_field('salesforce_endpoint_url', 'option');
    $url = $endpoint .'/services/data/'. $ver .'/sobjects/User';

    $result = wp_remote_post($url . '/' . $sf_uid, [
      'method' => 'GET',
      'headers' => [
        // 'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $access_token
      ],
    ]);
  
    return json_decode(wp_remote_retrieve_body($result), true); 
}

function fn_set_role_by_profileid($user_id, $profile_id) {
    $roleObject = pp_get_role_item($profile_id, 'code');
    if(!$roleObject) return;
  
    $wp_user_object = new WP_User($user_id);
    $wp_user_object->set_role($roleObject['name']); // set role
}

function fn_update_customer_billing($userId, $args = []) {
    if ( ! empty( $args ) ) {
        foreach ( $args as $key => $value ) {
            update_user_meta( $userId, $key, $value );
        }
    }
}

/**
 * Sync Salesforce User to WordPress
 *
 * @param int $user_id The Salesforce user ID.
 * @param string $access_token The Salesforce access token.
 */
function fn_sync_user($user_id = 0, $access_token = '') {
    // Retrieve Salesforce user data
    $userData = fn_get_salesforce_user($user_id);

    // Extract relevant data from the user data array
    $Username           = $userData['Username'];
    $LastName           = $userData['LastName'];
    $FirstName          = $userData['FirstName'];
    $Email              = $userData['Email'];
    $Street             = $userData['Street'];
    $City               = $userData['City'];
    $State              = $userData['State'];
    $PostalCode         = $userData['PostalCode'];
    $Country            = $userData['Country'];
    $Address            = $userData['Address'];
    $Phone              = $userData['Phone'];
    $CompanyName        = $userData['CompanyName'];
    $AccountId          = $userData['AccountId'];
    $ContactId          = $userData['ContactId'];
    $Members__c         = $userData['Members__c'];
    $Non_Members__c     = $userData['Non_Members__c'];
    $Primary_Members__c = $userData['Primary_Members__c'];

    // Determine the appropriate ProfileId based on membership status
    $ProfileId = '';
    if ($Members__c) {
        $ProfileId = get_field('members_id', 'option');
    } elseif ($Primary_Members__c) {
        $ProfileId = get_field('primary_members_id', 'option');
    } elseif ($Non_Members__c) {
        $ProfileId = get_field('non_members_id', 'option');
    }

    // Check if the user already exists in WordPress
    $WP_UserID = fn_wp_user_exists($Email);
    $accountInfo = ppsf_get_account($AccountId);

    // Change Account data to handle error
    if (!empty($accountInfo)) {
        $accountInfo['Bread_Winner__BW_Account_Status__c'] = 'dev_changed_this_status';
    }

    if ($WP_UserID !== false) {
        // User exists, ensure Salesforce user ID is updated
        if (!metadata_exists('user', $WP_UserID, '__salesforce_user_id')) {
            update_user_meta($WP_UserID, '__salesforce_user_id', $user_id);
        }
        // Update billing information
        fn_update_customer_billing($WP_UserID, [
            'billing_first_name' => $FirstName ?? '',
            'billing_last_name'  => $LastName ?? '',
            'billing_company'    => $accountInfo['Name'] ?? '',
        ]);
    }
    else {
        // User does not exist, create a new user
        $WP_UserID = wp_create_user($Username, wp_generate_password(), $Email);

        // Update user information
        wp_update_user([
            'ID' => $WP_UserID,
            'first_name' => $FirstName,
            'last_name' => $LastName,
        ]);

        // Update user metadata
        update_user_meta($WP_UserID, '__salesforce_user_id', $user_id);

        // Update billing information
        fn_update_customer_billing($WP_UserID, [
            'billing_first_name' => $FirstName ?? '',
            'billing_last_name'  => $LastName ?? '',
            'billing_company'    => $accountInfo['Name'] ?? '',
            'billing_email'      => $Email ?? '',
            'billing_phone'      => $Phone ?? '',
            'billing_address_1'  => $accountInfo['BillingStreet'] ?? '',
            'billing_city'       => $accountInfo['BillingCity'] ?? '',
            'billing_country'    => $accountInfo['BillingCountry'] ?? '',
            'billing_postcode'   => $accountInfo['BillingPostalCode'] ?? '',
            'billing_state'      => $accountInfo['BillingState'] ?? '',
        ]);
    }

    // Update additional user metadata
    update_user_meta($WP_UserID, 'salesforce_contact_id', $ContactId);
    update_user_meta($WP_UserID, '__salesforce_account_id', $AccountId);
    update_user_meta($WP_UserID, '__salesforce_profile_id', $ProfileId);
    update_user_meta($WP_UserID, '__salesforce_access_token', $access_token);
    update_user_meta($WP_UserID, '__salesforce_user_meta', wp_json_encode($userData));
    update_user_meta($WP_UserID, '__salesforce_account_json', wp_json_encode($accountInfo));

    // Update user role based on ProfileId
    fn_set_role_by_profileid($WP_UserID, $ProfileId);

    // Log the user in
    wp_set_current_user($WP_UserID); 
    wp_set_auth_cookie($WP_UserID);
}

function sf_http_request( $url, $data, $headers = array(), $method = 'GET', $options = array() ) {
    // Build the request, including path and headers. Internal use.
    /*
     * Note: curl is used because wp_remote_get, wp_remote_post, wp_remote_request don't work. Salesforce returns various errors.
     * There is a GitHub branch attempting with the goal of addressing this in a future version: 
     * https://github.com/MinnPost/object-sync-for-salesforce/issues/94
    */
    $curl = curl_init();
    curl_setopt( $curl, CURLOPT_URL, $url );
    curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
    if ( false !== $headers ) {
      curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
    } else {
      curl_setopt( $curl, CURLOPT_HEADER, false );
    }
    if ( 'POST' === $method ) {
      curl_setopt( $curl, CURLOPT_POST, true );
      curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
    } elseif ( 'PATCH' === $method || 'DELETE' === $method ) {
      curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, $method );
      curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );
    }
    $json_response = curl_exec( $curl ); // this is possibly gzipped json data
    $code          = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

    if ( ( 'PATCH' === $method || 'DELETE' === $method ) && '' === $json_response && 204 === $code ) {
      // delete and patch requests return a 204 with an empty body upon success for whatever reason
      $data = array(
        'success' => true,
        'body'    => '',
      );
      curl_close( $curl );
  
      $result = array(
        'code' => $code,
      );
  
      $return_format = isset( $options['return_format'] ) ? $options['return_format'] : 'array';
  
      switch ( $return_format ) {
        case 'array':
          $result['data'] = $data;
          break;
        case 'json':
          $result['json'] = wp_json_encode( $data );
          break;
        case 'both':
          $result['json'] = wp_json_encode( $data );
          $result['data'] = $data;
          break;
      }
  
      return $result;
    }
  
    if ( ( ord( $json_response[0] ) == 0x1f ) && ( ord( $json_response[1] ) == 0x8b ) ) {
      // skip header and ungzip the data
      $json_response = gzinflate( substr( $json_response, 10 ) );
    }
    $data = json_decode( $json_response, true ); // decode it into an array
  
    curl_close( $curl );
  
    $result = array(
      'code' => $code,
    );
  
    $return_format = isset( $options['return_format'] ) ? $options['return_format'] : 'array';
  
    switch ( $return_format ) {
      case 'array':
        $result['data'] = $data;
        break;
      case 'json':
        $result['json'] = $json_response;
        break;
      case 'both':
        $result['json'] = $json_response;
        $result['data'] = $data;
        break;
    }
  
    return $result;
}
  
function sf_request_token( $code ) {

    $sf_client_id = get_field('salesforce_members_login_client_id', 'option');
    $sf_client_secret = get_field('salesforce_members_login_client_secret', 'option');
    $sf_callback_url = get_field('salesforce_callback_url', 'option');
    $sf_community_url = get_field('salesforce_community_url', 'option');

    $data = array(
        'code'          => $code,
        'grant_type'    => 'authorization_code',
        'client_id'     => $sf_client_id,
        'client_secret' => $sf_client_secret,
        'redirect_uri'  => $sf_callback_url,
    );
  
    $url      = $sf_community_url .'/forms/services/oauth2/token';
    $headers  = array(
        // This is an undocumented requirement on SF's end.
        //'Content-Type'  => 'application/x-www-form-urlencoded',
        'Accept-Encoding' => 'Accept-Encoding: gzip, deflate',
    );
    $response = sf_http_request( $url, $data, $headers, 'POST' );
  
    $data = $response['data'];
    // Ensure all required attributes are returned. They can be omitted if the
    // OAUTH scope is inadequate.
    $required = array( 'access_token', 'id', 'instance_url' );
    foreach ( $required as $key ) {
        if ( ! isset( $data[ $key ] ) ) {
            return false;
        }
    }
  
    return $data;
}
  
function sf_user_info( $access_token ) {

    $sf_site_url = get_field('salesforce_site_url', 'option');
    $data = array(
        'access_token' => $access_token
    );
  
    $url      = $sf_site_url .'/services/oauth2/userinfo';
    $headers  = array(
        // This is an undocumented requirement on SF's end.
        //'Content-Type'  => 'application/x-www-form-urlencoded',
        'Accept-Encoding' => 'Accept-Encoding: gzip, deflate',
    );
    $response = sf_http_request( $url, $data, $headers, 'POST' );
  
    $data = $response['data'];
    // Ensure all required attributes are returned. They can be omitted if the
    // OAUTH scope is inadequate.
    $required = array( 'user_id' );
    foreach ( $required as $key ) {
        if ( ! isset( $data[ $key ] ) ) {
            return false;
        }
    }
  
    return $data;
}

/**
 * Salesforce Oauth Login by community URL
 * 
 */
function sf_oauth_login() {
    
    if( isset($_REQUEST['code']) && isset($_REQUEST['sfdc_community_url']) ){

        $data = sf_request_token( $_REQUEST['code'] );
        if($data){
            $user_info = sf_user_info( $data['access_token'] );
            if($user_info){
                //var_dump($user_info);
                setcookie('lgi', true, time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie('userId', $user_info['user_id'], time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie('sf_name', $user_info['name'], time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie('sf_user_email', $user_info['email'], time() + (86400 * 30), "/"); // 86400 = 1 day
                setcookie('sf_access_token', $data['access_token'], time() + (86400 * 30), "/"); // 86400 = 1 day

                // Check access token expiration & refresh
                and_sf_access_token_expired();

                // Sync user from Salesforce & login WP
                fn_sync_user($user_info['user_id'], $data['access_token']);

                ?><script>
                    if (localStorage) {
                        var redirect_url = localStorage.getItem('sf_login_redirect_url');
                        if (redirect_url) {
                            window.location.href = redirect_url;
                        }
                    }
                    else {
                        window.location.href = '/';
                    }
                </script><?php

            }else{
                ?><script>
                    window.location.href = '/';
                </script><?php
            }
        }else{
            ?><script>
                window.location.href = '/';
            </script><?php
        }
    }
}

/**
 * Remove COOKIE & redirect after logout
 * 
 */
add_action('wp_logout', function (){
    // Remove all Salesforce COOKIE
    setcookie('lgi', null, time() - 3600 * 24, '/');
    setcookie('userId', null, time() - 3600 * 24, '/');
    setcookie('sf_name', null, time() - 3600 * 24, '/');
    setcookie('sf_user_email', null, time() - 3600 * 24, '/');
    setcookie('sf_access_token', null, time() - 3600 * 24, '/');
  
    // Redirect to current page
    ?><script>
        if (localStorage) {
            var redirect_url = localStorage.getItem('sf_login_redirect_url');
            if (redirect_url) {
                window.location.href = redirect_url;
            }
        }
        else {
            window.location.href = '/';
        }
    </script><?php
    exit();
});

