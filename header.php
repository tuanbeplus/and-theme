<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WP_Bootstrap_Starter
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<?php wp_head();

$colourScheme = get_field('colour_scheme');

if(is_single()) {
    global $post;
    $postType = get_post_type($post);
    if($postType == 'new_campaign') {
        $postType = 'news-and-events';
    }
    $postType = str_replace('_','-',$postType);
    $page = get_page_by_path( $postType );
    if (isset($page->ID)) {
        $colourScheme = get_field('colour_scheme', $page->ID);
    }
}

echo '<script type="text/javascript">
        jQuery(function(){
            jQuery("body").addClass("'.$colourScheme.'");
        });
    </script>';
?>
<link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css"
    />

<meta name="salesforce-community" content="https://andau.my.salesforce.com/forms">
<meta name="salesforce-client-id" content="3MVG9vVKBifrxMjZTfLHEjlAtOOtSC2lC_BqcPKespXcVMXHcMDoueMZc.jhqkOYH4looMT3IvcUwaC22QLLd">
<meta name="salesforce-mode" content="modal-callback">
<meta name="salesforce-forgot-password-enabled" content="true">
<meta name="salesforce-redirect-uri" content="https://www.and.org.au/sfcallback.php">
<meta name="salesforce-target" content=".invisible">
<meta name="salesforce-save-access-token" content="true">
<meta name="salesforce-login-handler" content="onLogin">
<meta name="salesforce-logout-handler" content="onLogout">

<!-- <link href="https://andau.my.salesforce.com/forms/servlet/servlet.loginwidgetcontroller?type=css&r=3242353425435" rel="stylesheet" type="text/css" />
<script src="https://andau.my.salesforce.com/forms/servlet/servlet.loginwidgetcontroller?type=javascript_widget&r=3242353425435" async defer></script> -->

<!-- Google Tag Manager -->
<script>
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NBXTMWQ');
</script>
<!-- End Google Tag Manager -->

<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-27868447-1');

  function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    }
    function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
    }
    function eraseCookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    }
    jQuery(document).on('click','.closer',function(e){
        $('.notice').remove();
    });
</script>
<style>
    @media (max-width: 767px) {
        .text-content-block {
            padding: 0 15PX!important;
        }
    }
    @media (max-width: 430px) {
        .closer {
            position: absolute;
            top: 14px;
        }
    }
</style>

</head>

<body onunload="removeCookies()" <?php body_class(); ?>>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NBXTMWQ" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<div id="page" class="site">

<!-- <script id="UsableNetSeal" data-color="dark" data-position="left" async="true" src="https://a11ystatus.usablenet.com/lv/and/6c89cc217d5b3efdeaa9328a94b157ed2ba13ef1d4/status"></script> -->

	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'wp-bootstrap-starter' ); ?></a>

    <?php if(!is_page_template( 'blank-page.php' ) && !is_page_template( 'blank-page-with-container.php' )): ?>

	<?php 
        $header_ver = get_field('header_template', 'option')['version'];
        if ($header_ver == 'version_1') {
            get_template_part('template-parts/header/header');
        }
        elseif ($header_ver == 'version_2') {
            get_template_part('template-parts/header/header-v2');
        }
    ?>
    <?php
        if(!is_front_page()):
        echo '<div class="breadcrumbs-top">
        <div class="container">';
        bcn_display($return = false, $linked = true, $reverse = false, $force = false);
        echo ' </div>
        </div>';
        endif;
    ?>
<script>

jQuery(document).on('click', '#sfid-login-button', function(e){
    jQuery('#sfid-username').focus();
})

function createCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {
    document.cookie = name +'=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}

function onLogin(identity) {

    jQuery('.buttons a#login').attr('href','javascript:SFIDWidget.logout()');
    jQuery('.buttons a#login span').text('Logout');

    jQuery('<a id="dashboard" href="/dashboard" class="btn-text change"><span>Dashboard</span></a>').insertBefore('.buttons a#login');

    if(jQuery('.dashboard').length > 1) {
        jQuery('.welcome h1').text('Welcome back, ' +identity.first_name);
    }
    if(getCookie('lgi') !== "true") {
             window.location = '/dashboard/';
        }

        createCookie('lgi', true);
        console.log(identity);
        if(identity) {
            createCookie('userId', identity.user_id);
        }
	}

	function showIdentityOverlay() {

		var lightbox = document.createElement('div');
	 	lightbox.className = "sfid-lightbox";
	 	lightbox.id = "sfid-login-overlay";
		lightbox.setAttribute("onClick", "SFIDWidget.cancel();");

		var wrapper = document.createElement('div');
	 	wrapper.id = "identity-wrapper";
		wrapper.onclick = function(event) {
		    event = event || window.event // cross-browser event

		    if (event.stopPropagation) {
		        // W3C standard variant
		        event.stopPropagation()
		    } else {
		        // IE variant
		        event.cancelBubble = true
		    }
		}

		var content = document.createElement('div');
	 	content.id = "sfid-content";

		var community = document.createElement('a');
		var commURL = document.querySelector('meta[name="salesforce-community"]').content;
		community.href = commURL;
		community.innerHTML = "Go to the Community";
		community.setAttribute("style", "float:left");
		content.appendChild(community);

		var logout = document.createElement('a');
	 	logout.href = "javascript:SFIDWidget.logout();SFIDWidget.cancel();";
		logout.innerHTML = "logout";
		logout.setAttribute("style", "float:right");
		content.appendChild(logout);

		var t = document.createElement('div');
	 	t.id = "sfid-token";
		t.className = "sfid-mb24";

		var p = document.createElement('pre');
	 	p.innerHTML = JSON.stringify(SFIDWidget.openid_response, undefined, 2);
		t.appendChild(p);

		content.appendChild(t);

		wrapper.appendChild(content);
		lightbox.appendChild(wrapper);

		document.body.appendChild(lightbox);
	}

</script>

<div id="content" class="site-content">
<?php endif; ?>
