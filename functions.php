<?php

/**
 * WP Bootstrap Starter functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WP_Bootstrap_Starter
 */

{
/**
 * Project pack
 */
require( get_stylesheet_directory() . '/project-pack/functions.php' );
}

if (!function_exists('wp_bootstrap_starter_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function wp_bootstrap_starter_setup()
	{
		/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on WP Bootstrap Starter, use a find and replace
	 * to change 'wp-bootstrap-starter' to the name of your theme in all the template files.
	 */
		load_theme_textdomain('wp-bootstrap-starter', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
		add_theme_support('title-tag');

		/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'primary' => esc_html__('Primary', 'wp-bootstrap-starter'),
		));

		/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
		add_theme_support('html5', array(
			'comment-form',
			'comment-list',
			'caption',
		));

		// Set up the WordPress core custom background feature.
		add_theme_support('custom-background', apply_filters('wp_bootstrap_starter_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)));

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		function wp_boostrap_starter_add_editor_styles()
		{
			add_editor_style('custom-editor-style.css');
		}
		add_action('admin_init', 'wp_boostrap_starter_add_editor_styles');
	}
endif;
add_action('after_setup_theme', 'wp_bootstrap_starter_setup');


/**
 * Add Welcome message to dashboard
 */
function wp_bootstrap_starter_reminder()
{
	$theme_page_url = 'https://afterimagedesigns.com/wp-bootstrap-starter/?dashboard=1';

	if (!get_option('triggered_welcomet')) {
		$message = sprintf(
			__('Welcome to WP Bootstrap Starter Theme! Before diving in to your new theme, please visit the <a style="color: #fff; font-weight: bold;" href="%1$s" target="_blank">theme\'s</a> page for access to dozens of tips and in-depth tutorials.', 'wp-bootstrap-starter'),
			esc_url($theme_page_url)
		);

		printf(
			'<div class="notice is-dismissible" style="background-color: #6C2EB9; color: #fff; border-left: none;">
                        <p>%1$s</p>
                    </div>',
			$message
		);
		add_option('triggered_welcomet', '1', '', 'yes');
	}
}
add_action('admin_notices', 'wp_bootstrap_starter_reminder');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function wp_bootstrap_starter_content_width()
{
	$GLOBALS['content_width'] = apply_filters('wp_bootstrap_starter_content_width', 1170);
}
add_action('after_setup_theme', 'wp_bootstrap_starter_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function wp_bootstrap_starter_widgets_init()
{
	register_sidebar(array(
		'name'          => esc_html__('Sidebar', 'wp-bootstrap-starter'),
		'id'            => 'sidebar-1',
		'description'   => esc_html__('Add widgets here.', 'wp-bootstrap-starter'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	register_sidebar(array(
		'name'          => esc_html__('Footer 1', 'wp-bootstrap-starter'),
		'id'            => 'footer-1',
		'description'   => esc_html__('Add widgets here.', 'wp-bootstrap-starter'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	register_sidebar(array(
		'name'          => esc_html__('Footer 2', 'wp-bootstrap-starter'),
		'id'            => 'footer-2',
		'description'   => esc_html__('Add widgets here.', 'wp-bootstrap-starter'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
	register_sidebar(array(
		'name'          => esc_html__('Footer 3', 'wp-bootstrap-starter'),
		'id'            => 'footer-3',
		'description'   => esc_html__('Add widgets here.', 'wp-bootstrap-starter'),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));
}
add_action('widgets_init', 'wp_bootstrap_starter_widgets_init');


/**
 * Enqueue scripts and styles.
 */
function wp_bootstrap_starter_scripts()
{
	wp_enqueue_script('save-data-entries', get_template_directory_uri() . '/assets/js/save_data_entries.js',array('jquery'), '1.0.0', true);
	// load bootstrap css
	if (get_theme_mod('cdn_assets_setting') === 'yes') {
		wp_enqueue_style('wp-bootstrap-starter-bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css');
		wp_enqueue_style('wp-bootstrap-starter-fontawesome-cdn', 'https://use.fontawesome.com/releases/v5.10.2/css/all.css');
	} else {
		wp_enqueue_style('wp-bootstrap-starter-bootstrap-css', get_template_directory_uri() . '/inc/assets/css/bootstrap.min.css');
		wp_enqueue_style('wp-bootstrap-starter-fontawesome-cdn', get_template_directory_uri() . '/inc/assets/css/fontawesome.min.css');
	}
	// load bootstrap css
	// load AItheme styles
	// load WP Bootstrap Starter styles
	wp_enqueue_style('wp-bootstrap-starter-style', get_stylesheet_uri());

	wp_enqueue_script('jquery');

	// Internet Explorer HTML5 support
	wp_enqueue_script('html5hiv', get_template_directory_uri() . '/inc/assets/js/html5.js', array(), '3.7.0', false);
	wp_script_add_data('html5hiv', 'conditional', 'lt IE 9');

	// load bootstrap js
	if (get_theme_mod('cdn_assets_setting') === 'yes') {
		wp_enqueue_script('wp-bootstrap-starter-popper', 'https://cdn.jsdelivr.net/npm/popper.js@1/dist/umd/popper.min.js', array(), '', true);
		wp_enqueue_script('wp-bootstrap-starter-bootstrapjs', 'https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js', array(), '', true);
	} else {
		wp_enqueue_script('wp-bootstrap-starter-popper', get_template_directory_uri() . '/inc/assets/js/popper.min.js', array(), '', true);
		wp_enqueue_script('wp-bootstrap-starter-bootstrapjs', get_template_directory_uri() . '/inc/assets/js/bootstrap.min.js', array(), '', true);
	}
	wp_enqueue_script('wp-bootstrap-starter-themejs', get_template_directory_uri() . '/inc/assets/js/theme-script.min.js', array(), '', true);
	wp_enqueue_script('wp-bootstrap-starter-skip-link-focus-fix', get_template_directory_uri() . '/inc/assets/js/skip-link-focus-fix.min.js', array(), '20151215', true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	if ( is_page_template('page-templates/quiz-template.php') || is_page_template('page-templates/quiz-html-template.php') ){
		wp_enqueue_style('quiz-css', get_template_directory_uri() . '/assets/scss/custom.css?r=' . rand());
	}

	if ( is_page_template('page-templates/quiz-html-template.php') ) {
		wp_enqueue_style('quiz-html', get_template_directory_uri() . '/assets/scss/quiz-custom.css?r=' . rand());
		wp_enqueue_script('jquery-validate', get_template_directory_uri() . '/assets/js/jquery.validate.min.js',array('jquery'), '1.0.1', true);
		wp_enqueue_script('quiz-html-js', get_template_directory_uri() . '/assets/js/quiz-html-template.js',array('jquery'), '1.0.1', true);
	}

	if ( is_page_template('page-templates/quiz-template.php') ) {
		wp_enqueue_script('quiz-js', get_template_directory_uri() . '/assets/js/quiz-template.js',array('jquery'), '1.0.1', true);
		wp_localize_script( 'quiz-js', 'PJ_Global', apply_filters( 'pj/wp_localize_script/PJ_Global', [
       'ajax_url' => admin_url( 'admin-ajax.php' ),
       'user_info' => wp_get_current_user(),
    ] ) );
	}

	wp_enqueue_style('app-css', get_template_directory_uri() . '/assets/css/app.css?r=' . rand());
	wp_enqueue_style('custom-css', get_template_directory_uri() . '/assets/css/custom.css?r=' . rand());
	wp_enqueue_style('jquery-ui', '//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css');

	// jQuery UI
	wp_enqueue_script('jquery-ui', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js',array('jquery'), '', true);

	// Owl Carousel
	wp_enqueue_style('owl-carousel', get_template_directory_uri() . '/assets/owl-carousel/owl.carousel.css?r=' . rand());
	wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/assets/owl-carousel/owl.carousel.js', array('jquery-ui'), rand(), true);

	// Custom JS
	wp_enqueue_script('custom-script', get_template_directory_uri() . '/assets/js/custom.js',array('jquery-ui'), rand(), true);

}
add_action('wp_enqueue_scripts', 'wp_bootstrap_starter_scripts');


/**
 * Admin enqueue scripts and stylesheet
 */
function and_admin_enqueue_scripts()
{
    wp_enqueue_style('admin-style', get_template_directory_uri() . '/assets/css/admin.css?r=' . rand());
}
add_action('admin_enqueue_scripts', 'and_admin_enqueue_scripts');


/**
 * Add Preload for CDN scripts and stylesheet
 */
function wp_bootstrap_starter_preload($hints, $relation_type)
{
	if ('preconnect' === $relation_type && get_theme_mod('cdn_assets_setting') === 'yes') {
		$hints[] = [
			'href'        => 'https://cdn.jsdelivr.net/',
			'crossorigin' => 'anonymous',
		];
		$hints[] = [
			'href'        => 'https://use.fontawesome.com/',
			'crossorigin' => 'anonymous',
		];
	}
	return $hints;
}

add_filter('wp_resource_hints', 'wp_bootstrap_starter_preload', 10, 2);


function wp_bootstrap_starter_password_form()
{
	global $post;
	$label = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);
	$o = '<form action="' . esc_url(site_url('wp-login.php?action=postpass', 'login_post')) . '" method="post">
    <div class="d-block mb-3">' . __("To view this protected post, enter the password below:", "wp-bootstrap-starter") . '</div>
    <div class="form-group form-inline"><label for="' . $label . '" class="mr-2">' . __("Password:", "wp-bootstrap-starter") . ' </label><input name="post_password" id="' . $label . '" type="password" size="20" maxlength="20" class="form-control mr-2" /> <input type="submit" name="Submit" value="' . esc_attr__("Submit", "wp-bootstrap-starter") . '" class="btn btn-primary"/></div>
    </form>';
	return $o;
}
add_filter('the_password_form', 'wp_bootstrap_starter_password_form');

define( 'AND_IMG_URI', get_template_directory_uri() . '/assets/imgs/');

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load plugin compatibility file.
 */
require get_template_directory() . '/inc/plugin-compatibility/plugin-compatibility.php';

/**
 * Custom functions Rest API
 */
require get_template_directory() . '/inc/rest-api.php';

/**
 * Custom functions Hook
 */
require get_template_directory() . '/inc/hook.php';

/**
 * Custom page sidebar functions
 */
require get_template_directory() . '/inc/custom-page-sidebar.php';

/**
 * Load custom WordPress nav walker.
 */
if (!class_exists('wp_bootstrap_navwalker')) {
	require_once(get_template_directory() . '/inc/wp_bootstrap_navwalker.php');
}


add_filter('get_the_archive_title', function ($title) {
	if (is_category()) {
		$title = single_cat_title('', false);
	} elseif (is_tag()) {
		$title = single_tag_title('', false);
	} elseif (is_author()) {
		$title = '<span class="vcard">' . get_the_author() . '</span>';
	} elseif (is_tax()) { //for custom post types
		$title = sprintf(__('%1$s'), single_term_title('', false));
	} elseif (is_post_type_archive()) {
		$title = post_type_archive_title('', false);
	}
	return $title;
});


add_filter("gform_ajax_spinner_url", "spinner_url", 10, 2);
function spinner_url($image_src, $form)
{
	return AND_IMG_URI."form-load.gif";
}


add_filter('upload_mimes', 'doublee_add_custom_mime_types');
function doublee_add_custom_mime_types($mimes)
{
	return array_merge($mimes, array(
		'doc' => 'application/msword',
		'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
	));
}

function getParentDetails()
{
	if (is_single()) {
		global $post;
		$postType = get_post_type($post);
		$postType = str_replace('_', '-', $postType);
		$page = get_page_by_path($postType);
		$colourScheme = get_field('colour_scheme', $page->ID);
	}

	return $page;
}

function campaigns_metaboxes()
{
	global $wp_meta_boxes;
	add_meta_box('postfunctiondiv', __('SF Data Test'), 'new_campaign_metaboxes_html', 'new_campaign', 'normal', 'high');
}
add_action('add_meta_boxes_new_campaign', 'campaigns_metaboxes');

function new_campaign_metaboxes_html()
{
	global $post;
	$custom = get_post_custom($post->ID);
	$campaignId = isset($custom["CampaignId"][0]) ? $custom["CampaignId"][0] : '';
	$campaignName = isset($custom["CampaignName"][0]) ? $custom["CampaignName"][0] : '';
?>
	<label>Campaign ID:</label><input name="CampaignId" value="<?php echo $campaignId; ?>"> <br /> <br />
	<label>Campaign Name:</label><input name="CampaignName" value="<?php echo $campaignName; ?>">
<?php
}

function campaigns_save_post()
{
	if (empty($_POST)) return; //why is prefix_teammembers_save_post triggered by add new?
	global $post;
	update_post_meta($post->ID, "CampaignId", $_POST["CampaignId"]);
	update_post_meta($post->ID, "CampaignName", $_POST["CampaignName"]);
}

add_action('save_post_new_campaign', 'campaigns_save_post');


/**
 * Load in the post types
 */

require get_template_directory() . '/post-types/about-us.php';
require get_template_directory() . '/post-types/how-we-can-help-you.php';
require get_template_directory() . '/post-types/resources.php';
require get_template_directory() . '/post-types/campaigns.php';
require get_template_directory() . '/post-types/news-and-events.php';
require get_template_directory() . '/post-types/join-us.php';
require get_template_directory() . '/post-types/students-and-jobseekers.php';

/**
 * Login to Salesforce to get a Session Token using CURL
 */
function doLogin()
{

	$state = $_SESSION['state'];

	// Set the POST url to call
	$postURL = 'https://login.salesforce.com/services/oauth2/token';

	$fields = $_POST;

	// Header options
	$headerOpts = array('Content-type: application/x-www-form-urlencoded');

	// Create the params for the POST request from the supplied fields
	$params = "";

	foreach ($fields as $key => $value) {
		$params .= $key . '=' . $value . '&';
	}

	$params = rtrim($params, '&');

	var_dump($params);


	// Open the connection
	$ch = curl_init();

	// Set the url, number of POST vars, POST data etc
	curl_setopt($ch, CURLOPT_URL, $postURL);
	curl_setopt($ch, CURLOPT_POST, count($fields));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headerOpts);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Execute POST
	$result = curl_exec($ch);


	// Close the connection
	curl_close($ch);



	//record the results into state
	$typeString = gettype($result);
	$resultArray = json_decode($result, true);

	$state->error = $resultArray["error"];
	$state->errorDescription = $resultArray["error_description"];


	var_dump($state->error);

	// If there are any errors return false
	if ($state->error != null) {
		die('error');

		return false;
	}

	$state->instanceURL = $resultArray["instance_url"];
	$state->token = $resultArray["access_token"];

	// If we are logging in via an Authentication Code, we want to store the
	// resulting Refresh Token
	if (!$isViaRefreshToken) {
		$state->refreshToken = $resultArray["refresh_token"];
	}

	// Extract the user Id
	if ($resultArray["id"] != null) {
		$trailingSlashPos = strrpos($resultArray["id"], '/');

		$state->userId = substr($resultArray["id"], $trailingSlashPos + 1);
	}

	// verify the signature
	$baseString = $resultArray["id"] . $resultArray["issued_at"];
	$signature = base64_encode(hash_hmac('SHA256', $baseString, getClientSecret(), true));

	if ($signature != $resultArray["signature"]) {
		$state->error = 'Invalid Signature';
		$state->errorDescription = 'Failed to verify OAUTH signature.';

		return false;
	}

	// Debug that we've logged in via the appropriate method
	echo "<pre>Logged in " . ($isViaRefreshToken ? "via refresh token" : "via authorisation code") . "</pre>";

	return true;
}
add_action('wp_ajax_nopriv_doLogin', 'doLogin');
add_action('wp_ajax_doLogin', 'doLogin');

/**
 * Enables the HTTP Strict Transport Security (HSTS)
 */
add_action( 'send_headers', 'and_add_security_header', 99 );
function and_add_security_header() {
    header( "Content-Security-Policy: upgrade-insecure-requests" );
    header( "Strict-Transport-Security: max-age=31536000" );
    header( "X-Xss-Protection: 1; mode=block" );
    header( "X-Frame-Options: SAMEORIGIN" );
    header( "X-Content-Type-Options: nosniff" );
    header( "Referrer-Policy: strict-origin-when-cross-origin" );
    header( "Permissions-Policy: geolocation=self" );
    header( "Access-Control-Allow-Origin: *" );
	if(get_the_ID() == '11' || get_the_ID() == '18192') {
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
	}
}

/**
 * Change Yoast sitemap path for AND
 */
function and_wpseo_stylesheet_url($s)
{
    return str_replace('/wp-content/plugins/wordpress-seo/css/main-sitemap.xsl', get_template_directory_uri().'/yoast/css/and-sitemap.xsl', $s);
}
add_filter('wpseo_stylesheet_url', 'and_wpseo_stylesheet_url');

/**
 * Add label to post thumbnail meta box
 */
function swd_admin_post_thumbnail_add_label($content, $post_id, $thumbnail_id)
{
	$post = get_post($post_id);
	$content .= '<strong>Please upload image at 433px x 262px</strong>';
	return $content;

	return $content;
}
add_filter('admin_post_thumbnail_html', 'swd_admin_post_thumbnail_add_label', 10, 3);


// Function get cookie ajax
add_action( 'wp_ajax_get_cookie_share', 'get_cookie_share' );
add_action( 'wp_ajax_nopriv_get_cookie_share', 'get_cookie_share' );
function get_cookie_share(){
   $cookie = array();
   // Time of user's visit
   $visit_time = date('F j, Y g:i a');
   unset($_POST['action']);
   // Check if cookie is already set

   $cookies = array(
  	 'clientid'
   );
   $resetcookie=0;

   foreach ($cookies as $k) {
  	 if(isset($_POST[$k]) && $_POST[$k]) {
  		 if(!$resetcookie){
          clearCookies($cookies);
          $resetcookie = 1;
        }
  		 setcookie($k,  $_POST[$k] , time()+(60*10));
  		 $cookie[$k] = $_POST[$k];
  	 }else{
  		 if(isset($_COOKIE[$k]) && $_COOKIE[$k]) {
  			 $cookie[$k] = $_COOKIE[$k];
  		 }
  	 }
   }
   if($_POST['clientid']){
  	 $cookie['clientid'] = $_POST['clientid'];
  	 //setcookie('clientid',  $cid , time()+(60*10));
   }
   wp_send_json($cookie);
}
function clearCookies($cookies) {
    $past = time() - 3600;
    foreach ( $cookies as $key => $value ) {
       $value = '';
        setcookie( $key, $value, $past );
        setcookie( $key, $value, $past, '/' );
    }
}

add_action( 'gform_after_submission_9', 'access_entry_via_field', 10, 2 );
function access_entry_via_field( $entry, $form ) {
		echo "<div id='wrapperTablePrint'>";
			?>
			<style media="screen">
			#printTable {
			 border-radius: 16px;
			 background: #dfdfdf;
			 overflow: hidden;
			 border: 1px solid #dfdfdf;
			}
			#printTable .heading {
			 font-size: 14px;
			 font-weight: bold;
			 text-shadow: 0 1px 0 #fff;
			 text-align: left;
			 padding: 12px;
			}
			#printTable .label {
			 font-weight: 700;
			 background-color: #eaf2fa;
			 border-bottom: 1px solid #fff;
			 line-height: 150%;
			 padding: 7px 7px;
			}
			#printTable .label td {
			 width: 100% !important;
			}
			#printTable .value {
			 border-bottom: 1px solid #dfdfdf;
			 padding: 7px 7px 7px 40px;
			 line-height: 150%;
			 background: #fff;
			}
			</style>
			<?php
			echo "<table class='ss-print widefat fixed entry-detail-view' id='printTable'>";
				$title_form = $form['title'];
				$id_entry = $entry['id'];

				echo "<thead class='heading'>". $title_form ." : # ". $id_entry;
					echo '<th id="details">'. $title_form .' : Entry #  '. $id_entry .'</th>';
					echo '<th style="width:auto; font-size:10px; text-align: right;"></th>';
				echo "</thead>";

		    foreach ( $form['fields'] as $field ) {
					// print_r( $field );
					$label = $field->label;
					$value = rgar( $entry, (string) $field->id );

					if ( !empty($value)) {
						echo "<tr class='label entry-view-field-name'><td>". $label ."</td></tr>";
						echo "<tr class='value entry-view-field-value'><td>&nbsp;&nbsp;&nbsp;". $value ."</td></tr>";
					}
		    }

			echo "</table>";
		echo "</div>";
}

function and_create_entry_table(){
	 global $wpdb;
	 $table_name_sp = $wpdb->prefix . 'and_data_entries_gform';
	 $charset_collate = $wpdb->get_charset_collate();

	 require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	 $sql =
	 "CREATE TABLE IF NOT EXISTS {$table_name_sp} (
	 id bigint(20) NOT NULL AUTO_INCREMENT,
	 username longtext DEFAULT NULL,
	 data longtext DEFAULT NULL,
	 date datetime NOT NULL,
	 PRIMARY KEY  (id)
	 ) " . $charset_collate;
	 dbDelta( $sql );

	 $row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM $table_name_sp.COLUMNS
	 WHERE table_name = $table_name_sp AND column_name = 'step'"  );
	 if(empty($row)){
   	$wpdb->query("ALTER TABLE $table_name_sp ADD step INT(10) NULL");
	 }
}
// add_action('init', 'and_create_entry_table');

// add_action('wp_ajax_control_data_entry_gform', 'control_data_entry_gform'); // wp_ajax_{action}
// add_action('wp_ajax_nopriv_control_data_entry_gform', 'control_data_entry_gform'); // wp_ajax_nopriv_{action}
function control_data_entry_gform(){
	global $wpdb;
	$table_name = $wpdb->prefix. 'and_data_entries_gform';

	$arrayEntry = $_POST['arrayEntry'];

	echo "<pre>";
	print_r($arrayEntry);
	echo "</pre>";

	$insertData = $wpdb->insert( $table_name,
		array(
			'username' => $_POST['userId'],
			'data' => serialize($arrayEntry),
			'date' => current_time( 'mysql', 1 ),
			'step' => $_POST['step'],
		),
		array(
			'%s',
			'%s',
		)
	);

	die();
}

// add_action('wp_ajax_and_apply_data_to_form', 'and_apply_data_to_form'); // wp_ajax_{action}
// add_action('wp_ajax_nopriv_and_apply_data_to_form', 'and_apply_data_to_form'); // wp_ajax_nopriv_{action}
function and_apply_data_to_form(){
	global $wpdb;
	$username = $_POST['usernameId'];

  $table_name = $wpdb->prefix. "and_data_entries_gform";
  $lastArr = $wpdb->get_results( "SELECT * FROM $table_name WHERE username = '{$username}' ORDER BY date DESC LIMIT 1" );
	// print_r($results);
	// $lastArr = $results;
	// print_r($lastArr);
	if ( !empty($lastArr) ) {

		$arrSend = [];
	  foreach ($lastArr as $key => $value) {
			// $arrSend['id']       = $value->id;
	    $arrSend['username'] = $value->username;
	    // $arrSend['data']     = $value->data;
			// $arrSend['data']     = json_encode(unserialize($value->data));
	    // $arrSend['date']     = $value->date;
			$arrSend['step']     = $value->step;
	  }

		wp_send_json($arrSend);

	}

	die();
}

// add_action('wp_ajax_and_clear_data_gform', 'and_clear_data_gform'); // wp_ajax_{action}
// add_action('wp_ajax_nopriv_and_clear_data_gform', 'and_clear_data_gform'); // wp_ajax_nopriv_{action}
function and_clear_data_gform(){
	$userID = $_POST['userID'];

	global $wpdb;
	$table_name = $wpdb->prefix. "and_data_entries_gform";
	$wpdb->delete( $table_name, [ 'username' => $userID ], [ '%s' ] );

	die();
}

// add_action('wp_ajax_and_save_data_next_step', 'and_save_data_next_step'); // wp_ajax_{action}
// add_action('wp_ajax_nopriv_and_save_data_next_step', 'and_save_data_next_step'); // wp_ajax_nopriv_{action}
function and_save_data_next_step(){
	$username = $_POST['userId'];
	$data     = $_POST['data'];
	$num_step = $_POST['currentPage'];

	global $wpdb;
	$table_name = $wpdb->prefix. 'and_data_entries_gform';

	if ( $username != '' || $username != null ) {
		$insertData = $wpdb->insert( $table_name,
			array(
				'username' => $username,
				'data' => serialize($data),
				'date' => current_time( 'mysql', 1 ),
				'step' => $num_step,
			),
			array(
				'%s',
				'%s',
			)
		);
	}

	die();
}

/**
 * [list_searcheable_acf list all the custom fields we want to include in our search query]
 * @return [array] [list of custom fields]
 */
function list_searcheable_acf(){
	$list_searcheable_acf = array(
		"page_builder",
		"single_column_content", 
		"content_with_image",
		"member_content",
		"content", 
		"hero_text",
		"member_table_features", 
		"table", 
		"member_pricing", 
		"content_with_image",
		"text_content",
	);
	return $list_searcheable_acf;
}

/**
 * [advanced_custom_search search that encompasses ACF/advanced custom fields and taxonomies and split expression before request]
 * @param  [query-part/string]      $where    [the initial "where" part of the search query]
 * @param  [object]                 $wp_query []
 * @return [query-part/string]      $where    [the "where" part of the search query as we customized]
 */

add_filter( 'posts_search', 'and_advanced_custom_search', 500, 2 );
function and_advanced_custom_search( $where, $wp_query ) {
	global $wpdb;

	if ( empty( $where ))
		return $where;

	// get search expression
	$terms = $wp_query->query_vars[ 's' ];

	// explode search expression to get search terms
	$exploded = explode( ' ', $terms );
	if( $exploded === FALSE || count( $exploded ) == 0 )
		$exploded = array( 0 => $terms );

	// reset search in order to rebuilt it as we whish
	$where = '';

	// get searcheable_acf, a list of advanced custom fields you want to search content in
	$list_searcheable_acf = list_searcheable_acf();
	foreach( $exploded as $tag ) :
		$where .= " 
			AND (
			(wp_posts.post_title LIKE '%$tag%')
			OR (wp_posts.post_content LIKE '%$tag%')
			OR EXISTS (
				SELECT * FROM wp_postmeta
					WHERE post_id = wp_posts.ID
					AND (";
		foreach ($list_searcheable_acf as $searcheable_acf) :
			if ($searcheable_acf == $list_searcheable_acf[0]):
				$where .= " (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
			else :
				$where .= " OR (meta_key LIKE '%" . $searcheable_acf . "%' AND meta_value LIKE '%$tag%') ";
			endif;
		endforeach;
			$where .= ")
			)
			OR EXISTS (
				SELECT * FROM wp_comments
				WHERE comment_post_ID = wp_posts.ID
				AND comment_content LIKE '%$tag%'
			)
			OR EXISTS (
				SELECT * FROM wp_terms
				INNER JOIN wp_term_taxonomy
				ON wp_term_taxonomy.term_id = wp_terms.term_id
				INNER JOIN wp_term_relationships
				ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id
				WHERE (
				taxonomy = 'post_tag'
					OR taxonomy = 'category'                
					OR taxonomy = 'myCustomTax'
				)
				AND object_id = wp_posts.ID
				AND wp_terms.name LIKE '%$tag%'
			)
		)";
	endforeach;
	return $where;
}


/**
 * Get posts array for component
 * 
 * @param string $post_type		Post type name
 * @param int $number_posts		Number posts to show
 * @param string $order			order ASC or DESC
 * 
 */
function get_posts_grid_component($post_type, $number_posts, $order) {
	$posts = array();

	$args = array(
		'post_type' => $post_type,
		'posts_per_page' => $number_posts,
		'post_status' => 'publish',
		'orderby' => 'date',
		'order' => $order,
	);
	$posts_obj = get_posts($args);
	foreach ($posts_obj as $post) {
		$posts[] = $post->ID;
	}
	wp_reset_postdata();

	return $posts;
}

/**
 * Get loop posts card item
 * 
 * @param int $post_type		Post type name
 * @param array $posts			Array posts ID
 * 
 */
function get_template_posts_card($post_type, $posts) {
	set_query_var( 'post_type', $post_type);
	set_query_var( 'posts', $posts);
	if (isset($post_type) && isset($posts)) {
		get_template_part('template-parts/posts-grid/posts-card-loop');
		get_template_part('template-parts/posts-grid/posts-card-carousel');
	}
}
