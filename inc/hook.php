<?php

/**
 * Hook to Quick login Salesforce member to Debug
 * 
 */
add_action ('wp_head', function () {
    if(isset($_GET['sf_user_id'])) {
        $_COOKIE['lgi'] = true;
        $_COOKIE['userId'] = $_GET['sf_user_id'];
        $_COOKIE['sf_name'] = getUser($_GET['sf_user_id'])->records[0]->Name;
        $_COOKIE['sf_user_email'] = getUser($_GET['sf_user_id'])->records[0]->Email;
    }
});

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

/**
 * Upload a file to the media library using a URL.
 * 
 */
function azure_upload_from_url( $url, $title = null ) {
	require_once( ABSPATH . "/wp-load.php");
	require_once( ABSPATH . "/wp-admin/includes/image.php");
	require_once( ABSPATH . "/wp-admin/includes/file.php");
	require_once( ABSPATH . "/wp-admin/includes/media.php");

    // Hooks for handling default file uploads.
    add_filter( 'wp_generate_attachment_metadata', 'windows_azure_storage_wp_generate_attachment_metadata', 9, 2 );
    // Hook for handling blog posts via xmlrpc. This is not full proof check.
    add_filter( 'content_save_pre', 'windows_azure_storage_content_save_pre' );
    add_filter( 'wp_handle_upload_prefilter', 'windows_azure_storage_wp_handle_upload_prefilter' );
    // Hook for handling media uploads.
    add_filter( 'wp_handle_upload', 'windows_azure_storage_wp_handle_upload' );
    // Filter to modify file name when XML-RPC is used.
    add_filter( 'xmlrpc_methods', 'windows_azure_storage_xmlrpc_methods' );
	
	// Download url to a temp file
	$tmp = download_url( $url );
	if ( is_wp_error( $tmp ) ) return false;
	
	// Get the filename and extension ("photo.png" => "photo", "png")
	$filename = pathinfo($url, PATHINFO_FILENAME);
	$extension = pathinfo($url, PATHINFO_EXTENSION);
	
	// An extension is required or else WordPress will reject the upload
	if ( ! $extension ) {
		// Look up mime type, example: "/photo.png" -> "image/png"
		$mime = mime_content_type( $tmp );
		$mime = is_string($mime) ? sanitize_mime_type( $mime ) : false;
		
		// Only allow certain mime types because mime types do not always end in a valid extension (see the .doc example below)
		$mime_extensions = array(
			// mime_type         => extension (no period)
			'text/plain'         => 'txt',
			'text/csv'           => 'csv',
			'application/msword' => 'doc',
			'image/jpg'          => 'jpg',
			'image/jpeg'         => 'jpeg',
			'image/gif'          => 'gif',
			'image/png'          => 'png',
			'video/mp4'          => 'mp4',
		);
		
		if ( isset( $mime_extensions[$mime] ) ) {
			// Use the mapped extension
			$extension = $mime_extensions[$mime];
		}else{
			// Could not identify extension
			@unlink($tmp);
			return false;
		}
	}
	// Upload by "sideloading": "the same way as an uploaded file is handled by media_handle_upload"
	$args = array(
		'name' => "$filename.$extension",
		'tmp_name' => $tmp,
	);
	// Do the upload
	$attachment_id = media_handle_sideload( $args, 0, "$filename.$extension");
	// Cleanup temp file
	@unlink($tmp);
	// Error uploading
	if ( is_wp_error($attachment_id) ) return false;

    // remove MS Azure storage filter
    remove_filter( 'wp_generate_attachment_metadata', 'windows_azure_storage_wp_generate_attachment_metadata', 9);
    remove_filter( 'content_save_pre', 'windows_azure_storage_content_save_pre');
    remove_filter( 'wp_handle_upload_prefilter', 'windows_azure_storage_wp_handle_upload_prefilter');
    remove_filter( 'wp_handle_upload', 'windows_azure_storage_wp_handle_upload');
    remove_filter( 'xmlrpc_methods', 'windows_azure_storage_xmlrpc_methods');

	// Success, return attachment ID (int)
	return (int) $attachment_id;
}

function upload_azure_attachments_from_media($file_upload, $submission_id, $parent_id, $quiz_id) {
    
    $azure = new WP_Azure_Storage();
    $user_name = get_post_meta($submission_id, 'sf_user_name', true);
    $_COOKIE['sf_name'] = $user_name;
    $user_id = get_post_meta($submission_id, 'sf_user_id', true);
    $assessment_id = get_post_meta($submission_id, 'assessment_id', true);
    $organisation_id = get_post_meta($submission_id, 'organisation_id', true);
    $attachment_id = azure_upload_from_url($file_upload);

    if (isset($attachment_id)) {
        $attachment_path = wp_get_attachment_url($attachment_id);

        if (isset($attachment_path)) {
            $attachment_name = get_the_title($attachment_id);

            $inputs = array(
                'attachment_name' => $attachment_name,
                'attachment_path' => $attachment_path,
                'user_id' => $user_id,
                'user_name' => $user_name,
                'parent_id' => $parent_id,
                'quiz_id' => $quiz_id,
            );
            $conditions = array(
                'attachment_id' => $attachment_id,
                'assessment_id' => $assessment_id,
                'organisation_id' => $organisation_id,
            );

            // Insert attachment data row to Azure table
            $insert_table = $azure->insert_attachments_azure_storage(array_merge($inputs, $conditions));

            // Delete attachment in WP media
            $wp_media_deleted = wp_delete_attachment( $attachment_id, true );

            return array(
                'attachment_id' => $attachment_id, 
                'insert_row_id' => $insert_table, 
                'message' => 'Attachment has been uploaded', 
                'status' => true,                     
                'wp_media_deleted' => $wp_media_deleted->post_title,
            );
        }
    }
}

function upload_members_attchments_azure($submission_id)
{
    global $wpdb;
    $table = 'wp_user_quiz_submissions';
    $sql = "SELECT * FROM $table
            WHERE submission_id=$submission_id";
    $attachment_arr = array();

    $result = $wpdb->get_results($sql);

    foreach ($result as $row) {
        if (isset($row->attachment_ids)) {
            $parent_id = $row->parent_id;
            $quiz_id = $row->quiz_id;
            $attachment_ids = json_decode($row->attachment_ids, true);

            foreach ($attachment_ids as $item) {
                $attachment_id = $item['value'];
                $attachment_url = wp_get_attachment_url($attachment_id);

                if (isset($attachment_url)) {                    
                    if ($_GET['azure_action'] == 'upload') {
                        // print_r($attachment_url);
                        // print_r(upload_azure_attachments_from_media($attachment_url, $submission_id, $parent_id, $quiz_id));
                        // echo '<br>';
                    }
                    if ($_GET['azure_action'] == 'delete') {
                        // print_r($attachment_id);
                        // print_r(wp_delete_attachment( $attachment_id, true ));
                        // echo '<br>';
                    }
                }
            }
        }
    }

    if ($wpdb->last_error) {
        throw new Exception($wpdb->last_error);
    }
}

function get_old_attachments_exist()
{
    $args = array(
		'post_type' => 'attachment',
		'posts_per_page' => -1,
		'post_status' => 'any',
        'meta_key' => 'sf_user_id',
        'meta_value' => '0059q000000JiKoAAK',
        'meta_compare' => '=',
	);
	$attachments = get_posts($args);

    foreach ($attachments as $item) {
        echo wp_delete_attachment( $item->ID, true )->post_title;
        echo '<br>';
    }
    // return $attachments;
}

// if (($_GET['test'] == 'test')) {
//     echo "<pre>";
//     print_r();
//     echo "</pre>";
// }


