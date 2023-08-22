<?php
function is_member_exist($user_id)
{
    $args = array(
		'post_type' => 'members',
		'posts_per_page' => 1,
		'post_status' => 'publish',
        'meta_key' => 'sf_user_id',
        'meta_value' => $user_id
	);
	$member = get_posts($args);
    $member_id = $member[0]->ID;

    return $member_id;
}

// Create Member SF by SF API
// add_action( 'init', 'create_members_sf' );
function create_members_sf()
{
    $all_user_sf = getAllUsers();

    foreach ($all_user_sf as $user) {

        $args = array(
            'fields' => 'ids',
            'post_type'   => 'members',
            'meta_query'  => array(
                array(
                    'key' => 'sf_user_id',
                    'value' => $user->Id,
                    'compare' => '=',    
                )
            )
        );
        $my_query = new WP_Query( $args );
        if( empty($my_query->have_posts()) ) {

            $post_id = wp_insert_post( array (
                'post_type' => 'members',
                'post_title' => $user->Name,
                'post_status' => 'publish',
                'post_author' => 5,
            ));

            if ($post_id) {
                // insert post meta
                update_post_meta($post_id, 'sf_user_id', $user->Id);
                update_post_meta($post_id, 'user_name', $user->Name);
                update_post_meta($post_id, 'email', $user->Email);
                update_post_meta($post_id, 'contact_id', $user->ContactId);
                update_post_meta($post_id, 'account_id', $user->AccountId);
                update_post_meta($post_id, 'profile_id', $user->ProfileId);
            }
        }
        wp_reset_query();
        wp_reset_postdata();
    }
}

function init_meta_boxes_members_admin_view()
{
    add_meta_box('assessments_purchased_view', 'Assessments', 'assessments_purchased_section_admin', 'members', 'normal', 'default');
}
add_action('admin_init', 'init_meta_boxes_members_admin_view');

function assessments_purchased_section_admin()
{
    get_template_part('members/assessments-purchased');
}

function member_admin_meta_box_save($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id) || get_post_type($post_id) != 'members')
        return;

    $dcr_list = $_POST['dcr_list'] ?? null;

    update_post_meta($post_id, 'dcr_list', $dcr_list);
}
// add_action('save_post', 'member_admin_meta_box_save');

// Exclude pages from wp search
function and_exclude_posts_from_search($query) {

    if ( !$query->is_admin && $query->is_search) {
        $query->set('post__not_in', array(18192) ); // id of page or post
    }

    return $query;
}
add_filter( 'pre_get_posts', 'and_exclude_posts_from_search' );

/**
 * Change WP Fusion auth URL to Sandbox
 * 
 */
function and_salesforce_auth_url( $url ) {

	return 'https://test.salesforce.com/services/oauth2/token';

}
add_filter( 'wpf_salesforce_auth_url', 'and_salesforce_auth_url' );


//========================

function fn_wp_user_exists($email = '') {
    return email_exists($email);
  }
  
  function fn_salesforce_access_token() {
    return get_field('salesforce_api_access_token', 'option');
  }
  
  function fn_get_salesforce_user($sf_uid = 0) {
    $access_token = fn_salesforce_access_token();
    $ver = 'v53.0';
    $endpoint = 'https://andau--datacolada.sandbox.my.salesforce.com/services/data/'. $ver .'/sobjects/User';
    $result = wp_remote_post($endpoint . '/' . $sf_uid, [
      'method' => 'GET',
      'headers' => [
        // 'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $access_token
      ],
    ]);
  
    return json_decode(wp_remote_retrieve_body($result), true); 
  }
  
  /**
   * Sync user salesforce to WordPress
   * 
   */
  function fn_sync_user($user_id = 0, $access_token = '') {
    $userData = fn_get_salesforce_user($user_id);
  
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
    ) = $userData ;
  
    $WP_UserID = fn_wp_user_exists($Email);
  
    if($WP_UserID !== false) {
      # exists
  
      if ( !metadata_exists( 'user', $WP_UserID, '__salesforce_user_id' ) ) {
        update_user_meta($WP_UserID, '__salesforce_user_id', $user_id );
      }
  
      if ( !metadata_exists( 'user', $WP_UserID, '__salesforce_profile_id' ) ) {
        update_user_meta($WP_UserID, '__salesforce_profile_id', $ProfileId );
      }
  
      update_user_meta($WP_UserID, '__salesforce_user_meta', wp_json_encode( $userData ));
      update_user_meta($WP_UserID, '__salesforce_access_token', $access_token);
    } else {
      # not exists (Add new)
  
      $WP_UserID = wp_create_user($Username, wp_generate_password(), $Email);
      $accountInfo = ppsf_get_account($AccountId);
  
      // Update role 
      fn_set_role_by_profileid($WP_UserID, $ProfileId);
  
      // Update information
      wp_update_user([
        'ID' => $WP_UserID, // this is the ID of the user you want to update.
        'first_name' => $FirstName,
        'last_name' => $LastName,
      ]);
  
      // Update billing information
      fn_update_customer_billing($WP_UserID, [
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
      ]);
      
      update_user_meta($WP_UserID, '__salesforce_user_id', $user_id );
      update_user_meta($WP_UserID, '__salesforce_profile_id', $ProfileId );
      update_user_meta($WP_UserID, '__salesforce_user_meta', wp_json_encode( $userData ));
      update_user_meta($WP_UserID, '__salesforce_access_token', $access_token);
  
      update_user_meta($WP_UserID, '__salesforce_account_id', $AccountId);
      update_user_meta($WP_UserID, '__salesforce_account_json', wp_json_encode( $accountInfo ));
    }
  
    # Auto login
    wp_set_current_user($WP_UserID); 
    wp_set_auth_cookie($WP_UserID);
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

    $sf_client_id = get_field('salesforce_client_id', 'option');
    $sf_client_secret = get_field('salesforce_client_secret', 'option');
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
    if( isset($_REQUEST['code']) && $_REQUEST['code'] ){
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

                fn_sync_user($user_info['user_id'], $data['access_token']);

                ?><script>
                    if (localStorage) {
                        var redirect_url = localStorage.getItem('sf_login_redirect_url');
                        if (redirect_url) {
                            window.location.href = redirect_url;
                        }
                    }
                </script><?php
                die;

                if (class_exists('WP_Assessment')) {
                    $wp_assessment = new WP_Assessment();
                    $sf_login_redirect_url = $wp_assessment->get_data_value_local_storage('sf_login_redirect_url');
            
                    if ($sf_login_redirect_url) {
                        ?><script type="text/javascript">
                            window.location.href = '<?php echo $sf_login_redirect_url ?>';
                        </script><?php
                        die;
                    }
                    else{
                        ?><script type="text/javascript">
                            window.location.href = '/dashboard';
                        </script><?php
                    }
                }
                else {
                    ?><script type="text/javascript">
                        window.location.href = '/dashboard';
                    </script><?php
                }

            }else{
                ?><script type="text/javascript">
                    window.location.href = '/';
                </script><?php
            }
        }else{
            ?><script type="text/javascript">
                window.location.href = '/';
            </script><?php
        }
    }
    
}
add_action( 'init', 'sf_oauth_login', 10);

// if ($_GET['test'] == 'test') {
//     echo "<pre>";
//     print_r(getObjTest());
//     echo "</pre>";
//     die;
// }


