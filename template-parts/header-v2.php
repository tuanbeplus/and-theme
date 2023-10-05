<?php
/**
 * Template part for displaying Header version 2
 *
 *@since 10/2023
 *
 */

$path = $_SERVER['REQUEST_URI'];
$area = '';
$logo_url = get_field('header_template', 'option')['logo_img'];

if($path == '/global-footer.php') {
    $area = 'global-footer';
}
if($path == '/global-header.php') {
    $area = 'global-header';
}
?>

<header class="site-header version-2 <?php echo $area; ?>" role="banner">
    <div class="container">
        <nav class="navbar navbar-expand-xl p-0">
            <div class="navbar-brand">
                <a href="<?php echo esc_url( home_url( '/' )); ?>" class="desktop">
                    <img src="<?php echo $logo_url; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                </a>
                <a href="<?php echo esc_url( home_url( '/' )); ?>" class="mobile">
                    <img src="<?php echo $logo_url; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
                </a>
            </div>
            <button class="hamburger hamburger--spring" type="button" data-target="#primary-menu-wrap" aria-controls="primary-menu-wrap" aria-expanded="false" aria-label="Toggle navigation">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                    <span class="text">Menu</span>
                </span>
            </button>
            <?php 
                // Group Login/logout buttons
                pp_header_button_actions(); 
            ?>
            <?php
                wp_nav_menu(array(
                    'theme_location'    => 'primary',
                    'container'       => 'div',
                    'container_id'    => 'main-nav',
                    'container_class' => 'collapse navbar-collapse justify-content-end',
                    'menu_id'         => false,
                    'menu_class'      => 'navbar-nav',
                    'depth'           => 3,
                    'fallback_cb'     => 'wp_bootstrap_navwalker::fallback',
                    'walker'          => new wp_bootstrap_navwalker()
                ));
            ?>
        </nav>
    </div>
    <div class="template-form-search-genrenal"> 
        <?php get_template_part( 'searchform-autocomplete') ?> 
    </div>
</header><!-- #masthead -->