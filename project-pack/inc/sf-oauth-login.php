<?php
/**
 * Get WP User ID by Salesforce User ID.
 *
 * @param string $sf_user_id The Salesforce user ID.
 * @return int|false The user ID on success, false on failure.
 * 
 */
function fn_wp_user_exists($sf_user_id = '') {
    // Query users based on the meta key __salesforce_user_id
    $wp_users = get_users(array(
        'meta_key'   => '__salesforce_user_id',
        'meta_value' => $sf_user_id,
        'number'     => 1, // Limit to 1 result for efficiency
        'fields'     => 'ID', // Only retrieve user IDs for efficiency
    ));
    // Check if any user is found and return the user ID or false
    if (!empty($wp_users) && isset($wp_users[0])) {
        return $wp_users[0]; // Return the user ID
    }
	else {
		return false; // User does not exist
	}
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
 * Sync Salesforce User & auto login to WordPress
 *
 * @param int $sf_user_id The Salesforce user ID.
 * @param string $access_token The Salesforce access token.
 */
function fn_sync_and_auto_login_user($sf_user_info=[], $access_token='') {

    $sf_user_id = $sf_user_info['user_id'] ?? 0;
    // Retrieve Salesforce user data
    $userData = ppsf_get_salesforce_user($sf_user_id);

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
    $WP_UserID = fn_wp_user_exists($sf_user_id);
    $accountInfo = ppsf_get_account($AccountId);

    // Change Account data to handle error
    if (!empty($accountInfo)) {
        $accountInfo['Bread_Winner__BW_Account_Status__c'] = 'dev_changed_this_status';
    }

    if ($WP_UserID !== false) {
        // Update user information
        wp_update_user([
            'ID' => $WP_UserID,
            'first_name' => $FirstName,
            'last_name' => $LastName,
            'user_email' => $Email,
        ]);
        // User exists, ensure Salesforce user ID is updated
        if (!metadata_exists('user', $WP_UserID, '__salesforce_user_id')) {
            update_user_meta($WP_UserID, '__salesforce_user_id', $sf_user_id);
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
        update_user_meta($WP_UserID, '__salesforce_user_id', $sf_user_id);
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
    update_user_meta($WP_UserID, '__salesforce_user_meta', wp_json_encode($userData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    update_user_meta($WP_UserID, '__salesforce_account_json', wp_json_encode($accountInfo, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    update_user_meta($WP_UserID, '__sf_last_updated_userinfo', current_time('mysql'));

    // Update user role based on ProfileId
    fn_set_role_by_profileid($WP_UserID, $ProfileId);

    // Log the user in
    wp_set_current_user($WP_UserID); 
    wp_set_auth_cookie($WP_UserID);

    // Handle WP login error
    if (is_wp_error($WP_UserID) || !is_user_logged_in() || get_current_user_id() != $WP_UserID) {
        $error_info = array(
            'wp_error' => $WP_UserID,
            'wp_error_message' => $WP_UserID->get_error_message(),
            'info' => $sf_user_info,
        );
        echo sf_login_error_messages('wp_login_failed', $error_info); // Alert message
        exit;
    }
}

/**
 * Build the HTTP request to Salesforce
 */
function sf_http_request( $url, $data, $headers = array(), $method = 'GET', $options = array() ) {
    /*
     * Build the request, including path and headers. Internal use.
     * Note: curl is used because wp_remote_get, wp_remote_post, wp_remote_request don't work. Salesforce returns various errors.
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

/**
 * Get Access Token of the Salesforce User
 */
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

/**
 * Get Salesforce User's info by access token
 */
function sf_get_user_info( $access_token ) {
    $sf_site_url = get_field('salesforce_site_url', 'option');
    $data = array(
        'access_token' => $access_token
    );
    $url      = $sf_site_url .'/services/oauth2/userinfo';
    $headers  = array(
        // This is an undocumented requirement on SF's end.
        'Accept-Encoding' => 'Accept-Encoding: gzip, deflate',
    );
    $response = sf_http_request( $url, $data, $headers, 'POST' );
  
    $data = $response['data'] ?? array();

    // Ensure required fields exist
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
 */
function sf_oauth_login() { 
    
    if( isset($_REQUEST['code']) && isset($_REQUEST['sfdc_community_url']) ){
        $response_code = $_REQUEST['code'] ?? '';

        $response_data = sf_request_token($response_code) ?? '';
        if (!isset($response_data) || empty($response_data)) {
            echo sf_login_error_messages('request_token_failed', array('info' => $response_code));
            exit;
        }

        $user_info = sf_get_user_info($response_data['access_token']) ?? '';
        if (!isset($user_info) || empty($user_info)) {
            echo sf_login_error_messages('user_info_not_found', array('info' => $response_data));
            exit;
        }

        setcookie('lgi', true, time() + (86400 * 30), "/", '', true, true);
        setcookie('userId', $user_info['user_id'], time() + (86400 * 30), "/", '', true, true);
        setcookie('sf_name', $user_info['name'], time() + (86400 * 30), "/", '', true, true);
        setcookie('sf_user_email', $user_info['email'], time() + (86400 * 30), "/", '', true, true);
        setcookie('sf_access_token', $data['access_token'], time() + (86400 * 30), "/", '', true, true);

        // Check access token expiration & refresh
        and_sf_access_token_expired();

        // Sync user from Salesforce & login WP
        fn_sync_and_auto_login_user($user_info, $data['access_token']);

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
    }
}

/**
 * Remove COOKIE & redirect after logout
 */
add_action('wp_logout', function (){
    // Remove all Salesforce cookies by setting them to expire in the past.
    $cookies = ['lgi', 'userId', 'sf_name', 'sf_user_email', 'sf_access_token'];
    foreach ($cookies as $cookie) {
        setcookie($cookie, '', time() - 3600, '/', '', true, true); // HttpOnly & Secure flags
    }
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

/**
 * Alert login error messages
 */
function sf_login_error_messages($error_name, $info=[]) {
    if (empty($error_name)) return;
    $wp_error_message = isset($info['wp_error_message']) ? $info['wp_error_message'] : "Authorization with ADN failed.";
    $messages = array(
        'request_token_failed' => "Your access token is invalid. Please try again or contact support.",
        'user_info_not_found'   => "We could not retrieve your user information. Please try again or contact support.",
        'wp_login_failed'       => $wp_error_message . " Please try again or contact support.",
    );
    $message = isset($messages[$error_name]) ? $messages[$error_name] : "Unknown error.";

    // Sanitize for JavaScript output
    $safe_message = esc_js($message);
    $redirect_url = esc_url(home_url('/?error=' . $error_name));

    // Log error to system
    fn_user_login_error_log($error_name, $info);

    return "<script>
        alert('Error: Unable to login. {$safe_message}');
        window.location.href = '{$redirect_url}';
    </script>";
}

/**
 * Function to log members' login errors.
 *
 * @param string $error_code Error code related to the login error.
 * @param array  $info       Additional information about the user.
 */
function fn_user_login_error_log($error_code = '', $info = []) {
    // Exit if required parameters are not provided.
    if (empty($error_code)) {
        return;
    }
    // Get the WordPress uploads directory.
    $upload_dir = wp_upload_dir();
    $log_dir_path = $upload_dir['basedir'] . '/users-login-logs';
    $log_file_path = $log_dir_path . '/login-error-logs-' . wp_date('m-Y') . '.log';

    // Create the logs directory if it doesn't exist.
    if (!file_exists($log_dir_path)) {
        if (!mkdir($log_dir_path, 0755, true) && !is_dir($log_dir_path)) {
            error_log('Failed to create log directory: ' . $log_dir_path);
            return;
        }
    }
    // Prepare the log message with the current timestamp.
    $log_message  = PHP_EOL;
    $log_message .= "[" . sanitize_text_field($error_code) . "] at [" . wp_date('d-m-Y H:i:s') . "]" . PHP_EOL;
    $log_message .= json_encode($info, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL;

    // Append the log message to the file.
    $result = file_put_contents($log_file_path, $log_message, FILE_APPEND | LOCK_EX);

    // Log to WordPress debug log if file writing fails.
    if ($result === false) {
        error_log('Failed to write to log file: ' . $log_file_path);
    }
}
