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

/**
 * Hide single Members from front
 * 
 */
add_action( 'template_redirect', function () {
    if ( is_singular( 'members' ) ) {
        get_template_part( '404' );
        exit;
    }
});

/**
 * NoIndex for Saturn 
 * 
 */
add_action('wp_head', function () { 
    if ( is_singular('assessments') 
        || is_singular('submissions') 
        || is_singular('reports') 
        || is_singular('members') ) {
            return '<meta name="robots" content="noindex, follow">';
    }
});

/**
 * Get user's capabilities.
 *
 * @param  int|WP_User $user The user ID or object. Default is the current user.
 *
 * @return array             The user's capabilities or empty array if none or user doesn't exist.
 */
function and_get_user_capabilities( $user = null ) {

	$user = $user ? new WP_User( $user ) : wp_get_current_user();

	return array_keys( $user->allcaps );
}


// if ($_GET['test'] == 'test') {
//     echo "<pre>";
//     print_r(and_get_user_capabilities(12));
//     echo "</pre>";
// }

