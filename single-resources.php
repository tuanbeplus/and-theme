<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WP_Bootstrap_Starter
 */

get_header();

global $post;

$showOnThisPage = get_field('show_on_this_page', $post->ID);
$onThisPageCustomLinks = get_field('on_this_page_custom_links', $post->ID);
$sidebarCta = get_field('sidebar_cta', $post->ID);
$showOnPageMainCta = get_field('main_sidebar_cta', $post->ID);
$program_id = get_field('stepping_into_program_id', 'options');
// Get custom_page_sidebar meta data
$custom_page_sidebar = get_post_meta($post->ID, 'custom_page_sidebar', true);
$menu_title = !empty($custom_page_sidebar['menu_title']) ? $custom_page_sidebar['menu_title'] : 'On this page';
$is_custom_menu = isset($custom_page_sidebar['is_custom_menu']) ? $custom_page_sidebar['is_custom_menu'] : '0';
$menu_items = isset($custom_page_sidebar['menu_items']) ? $custom_page_sidebar['menu_items'] : array();

$current_post_type = get_post_type();

?>    
	<section id="primary">

        <div class="container">
            <div class="row">
                <div class="col-12 main-area title-area">
                    <div class="container">
                        <?php if($current_post_type == 'resources'): ?>
                            <a href="/resources/" class="back-to-previous-page">
                                <span class="material-icons">arrow_back</span>
                                <span class="text">Back to all resources</span>
                            </a>
                        <?php endif; ?>
                        <h1><?php echo get_the_title(); ?></h1>
                    </div>
                </div>
            </div>
        </div>

        <?php
            if( have_rows('page_builder') ):
                // loop through the rows of data
                while ( have_rows('page_builder') ) : the_row();

                    get_template_part('components/hero-with-shape');

                endwhile;

            else :
                // no layouts found
            endif;
        ?>
        <?php if ($showOnThisPage == 'no'): ?>
            <!-- Sidetab -->
            <div class="sidetab <?php echo $sidebarCta; ?>">
                <?php if($sidebarCta == 'internship'): ?>
                <a href="https://andau.force.com/forms/s/andforms?formtype=stepping_into_application&programid=<?php echo $program_id; ?>" class="internship">
                    <img src="<?php echo AND_IMG_URI.'mail.svg'; ?>" alt="Mail icon"/>
                    <span class="text">Apply for an internship</span>
                </a>
                <?php endif; ?>
                <?php if($sidebarCta == 'opportunites'): ?>
                <a href="/students-jobseekers/" class="opportunities">
                    <img src="<?php echo AND_IMG_URI.'opportunities.svg'; ?>" alt="Hear about opportunities"/>
                    <span class="text">Hear about opportunities</span>
                </a>
                <?php endif; ?>
                <?php if($sidebarCta == 'latest-news'): ?>
                    <p>Get the latest news</p>
                    <li>
                        <a href="https://www.tfaforms.com/4931557" target="_blank" class="circle">
                            <span class="material-icons">arrow_forward</span><span class="text">Sign up to our Newsletter</span>
                        </a>
                    </li>
                    <li class="pink">
                        <a href="mailto:info@and.org.au" target="_blank" class="circle">
                            <img src="<?php echo AND_IMG_URI.'relation.svg'; ?>" alt="Relationship Manager"/><span class="text">Contact your Relationship Manager</span>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if($sidebarCta !== 'internship' && $sidebarCta !== 'opportunites' && $sidebarCta !== 'latest-news' && $sidebarCta !== 'none' && !isset($_COOKIE['lgi'])): ?>
                <a href="<?php echo get_field('become_member_cta','option'); ?>" class="member">
                    <img src="<?php echo AND_IMG_URI.'member-icon-cta.svg'; ?>" alt="Member icon" />
                    <span class="text">Become a member</span>
                </a>
                <?php endif; ?>
            </div>
            <!-- /Sidetab -->
        <?php endif; ?>

        <div class="container holder">
            <div class="row content-with-sidebar">
            <?php 
                // Show On This Page
                $links = '';
                if($showOnThisPage == 'yes'):
                    if(!empty($onThisPageCustomLinks)):
                        foreach($onThisPageCustomLinks as $customLinks):
                            if($customLinks['external'] == 'yes') {
                                $external = 'external';
                            } else {
                                $external = '';
                            }
                            $links .= '<li class="'.$external.'">
                                        <a href="'.$customLinks['link'].'" class="circle dark-red">
                                            <span class="material-icons">arrow_forward</span><span class="text">'.$customLinks['text'].'</span>
                                        </a>
                                    </li>';

                        endforeach;
                    endif;
                    ?>
                    <div class="col-md-12 col-lg-3 on-this-page sidebar <?php echo ($is_custom_menu == true) ? 'custom' : 'default'; ?>">
                        <div class="inner resource-sidebar-inner">
                            <div class="on-this-page-container">
                                <h2><?php echo $menu_title; ?></h2>
                                <ul>
                                    <!-- Custom links -->
                                    <?php echo $links; ?>
                                    <!-- /Custom links -->
    
                                    <?php if ($is_custom_menu == true): ?>
                                        <?php if (!empty($menu_items)): ?>
                                            <!-- Custom menu items -->
                                            <?php foreach ($menu_items as $index => $item): ?>
                                                <?php if (isset($item['custom_item'])): ?>
                                                <li>
                                                    <a href="#point-<?php echo $index; ?>" id="<?php echo $index; ?>" class="circle dark-red">
                                                        <span class="material-icons">arrow_forward</span>
                                                        <span class="text"><?php echo $item['custom_item']; ?></span>
                                                    </a>
                                                </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            <!-- /Custom menu items -->
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <?php if($showOnPageMainCta == 'internship'){ ?>
                                    <li class="yellow cta">
                                        <a href="https://andau.force.com/forms/s/andforms?formtype=stepping_into_application&programid=<?php echo $program_id; ?>">
                                            <img src="<?php echo AND_IMG_URI.'mail.svg'; ?>" alt="Mail icon"/>
                                            <span class="text">Apply for an internship</span>
                                        </a>
                                    </li>
                                    <?php } else if($showOnPageMainCta == 'apply-mentee') { ?>
                                    <li class="member cta">
                                        <a href="<?php echo get_field('mentee_cta','option'); ?>">
                                            <img src="<?php echo AND_IMG_URI.'member-icon.svg'; ?>" alt="Member icon"/>
                                            <span class="text">Apply to be a mentee</span>
                                        </a>
                                    </li>
                                    <?php } else if($showOnPageMainCta == 'become-member' && !isset($_COOKIE['lgi'])) { ?>
                                    <li class="member cta">
                                        <a href="<?php echo get_field('become_member_cta','option'); ?>">
                                            <img src="<?php echo AND_IMG_URI.'member-icon.svg'; ?>" alt="Member icon"/><span class="text">Become a member</span>
                                        </a>
                                    </li>
                                    <?php } else { ?>
                                        <li class="cta"></li>
                                <?php } ?>
                                </ul>
                            </div>
                            <?php if(!in_array($showOnPageMainCta, ['none', 'internship', 'apply-mentee', 'become-member'])) { ?>
                            <div class="member-login-cta-wrapper sidebar-member-login-cta-wrapper">
                            <div class="sidebar-member-cta">
                                <?php if (!isset($_COOKIE['lgi'])) : ?>
                                <h2>Are you a member?</h2>
                                <p>Login to see member exclusive content. <a href="/join-us/our-members/">Find out if you’re a member.</a></p>
                                <span class="member-login-cta"> <a href="/login" class="btn btn-primary">Log in</a> </span>
                                <?php else : ?>
                                <h2>Welcome back!</h2>
                                <p>You are logged in - browse resources to see member exclusive content.</p>
                                <?php endif; ?>
                            </div>

                            </div>
                        <?php } ?>
                        </div>
                    </div>                    
                <?php endif; ?>

                <?php
                if( have_rows('page_builder') ):
                    echo '<div class="col-md-12 col-lg-9 main-area">';
                    // loop through the rows of data
                    while ( have_rows('page_builder') ) : the_row();
                        get_template_part('components/video');
                        get_template_part('components/image');
                        get_template_part('components/image-carousel');
                        get_template_part('components/quote-with-citation');
                        get_template_part( 'components/page-tiles' );
                        get_template_part( 'components/single-column-content' );
                        get_template_part( 'components/wp-data-feeds' );
                        get_template_part( 'components/sf-data-feeds' );
                        get_template_part( 'components/membership-table' );

                    endwhile;
                    echo '</div>';
                else :
                    // no layouts found
                endif;
                ?>
            </div>

            <div class="row">
                <?php
                    $parentDetails = getParentDetails();
                    $parentId = isset($parentDetails->ID) ? $parentDetails->ID : '';
                    $paretnUrl = get_permalink($parentId) ?? '';
                    $parentTitle = isset($parentDetails->post_title) ? $parentDetails->post_title : '';

                    if( have_rows('page_builder') ):
                        // loop through the rows of data
                        while ( have_rows('page_builder') ) : the_row();

                            get_template_part( 'components/team-members' );
                            get_template_part( 'components/sources-horizontal' );
                            get_template_part( 'components/form-embed' );
                            get_template_part( 'components/our-members' );
                            get_template_part( 'components/content-with-image' );

                        endwhile;
                    else :
                        // no layouts found
                    endif;
                ?>
                <?php if ((!empty($parentId) && !empty($paretnUrl)) && ($current_post_type != 'resources')): ?>
                <section class="col-12 back-page bottom">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-md-6 text-content-block">
                                <a href="<?php echo $paretnUrl; ?>" class="btn circle dark-red">
                                    <span class="material-icons">arrow_back</span>
                                    <span class="text"><?php echo $parentTitle; ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
                <?php endif; ?>
            </div>

        </div>


        <?php
            if( have_rows('page_builder') ):
                echo '<div class="related-content-wrapper">';
                // loop through the rows of data
                while ( have_rows('page_builder') ) : the_row();
                    get_template_part( 'components/related-content' );
                endwhile;
                echo '</div>';
            else :
                // no layouts found
            endif;
        ?>
	</section><!-- #primary -->

<?php 
get_footer();
