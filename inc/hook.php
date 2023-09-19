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

/**
 * Add Exclude from search field to Submit box
 *
 */
// add_action( 'post_submitbox_misc_actions', 'add_excluded_post_from_search_field');
function add_excluded_post_from_search_field($post)
{
    $value = get_post_meta($post->ID, 'exclude_post_from_search', true);
    ?>
    <div class="misc-pub-section misc-pub-section-last">
         <span id="exclude-post-from-search">
            <label>
                <input type="checkbox" 
                    <?php if ($value == true) echo 'checked'; ?>
                    value="1" 
                    name="exclude_post_from_search"/> 
                Exclude from Internal Search
            </label>
        </span>
    </div>
    <?php
}

/**
 * Save Exclude from search meta
 *
 */
// add_action('save_post', 'save_exclude_post_from_search');
function save_exclude_post_from_search($post_id)
{   
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return false;
    if ( !current_user_can( 'edit_page', $post_id ) ) return false;

    $is_exclude_from_search = $_POST['exclude_post_from_search'] ?? 0;
    update_post_meta($post_id, 'exclude_post_from_search', $is_exclude_from_search);
}

/**
 * Exclude posts from Search
 *
 */
add_filter( 'pre_get_posts', 'and_exclude_posts_from_search' );
function and_exclude_posts_from_search($query) {
    
    if ( !$query->is_admin && $query->is_search) {
        $query->set(
            'meta_query', array(
            'relation' => 'OR',
                array(
                    'key' => 'exclude_post_from_search',
                    'value' => '',
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key' => 'exclude_post_from_search',
                    'value' => 0,
                    'compare' => '=',
                ),
            ),
        );
    }

    return $query;
}

// if ($_GET['test'] == 'test') {
//     echo "<pre>";
//     echo "</pre>";
// }
