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

?>

	<section id="primary">

        <div class="container">
            <div class="row">
                <div class="col-12 main-area title-area">
                    <div class="container">
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

<?php if($showOnThisPage == 'no'): ?>
        <div class="sidetab <?php echo $sidebarCta; ?>">
            <?php if($sidebarCta == 'internship'): ?>
            <a href="https://andau.force.com/forms/s/andforms?formtype=stepping_into_application&programid=<?php echo $program_id; ?>" class="internship">
                <img src="/wp-content/themes/and/assets/imgs/mail.svg" alt="Mail icon"/>
                <span class="text">Apply for an internship</span>
            </a>
            <?php endif; ?>
            <?php if($sidebarCta == 'opportunites'): ?>
            <a href="/students-jobseekers/" class="opportunities">
                <img src="/wp-content/themes/and/assets/imgs/opportunities.svg" alt="Hear about opportunities"/>
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
                        <img src="/wp-content/themes/and/assets/imgs/relation.svg" alt="Relationship Manager"/><span class="text">Contact your Relationship Manager</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if($sidebarCta !== 'internship' && $sidebarCta !== 'opportunites' && $sidebarCta !== 'latest-news' && $sidebarCta !== 'none' && !isset($_COOKIE['lgi'])): ?>
            <a href="<?php echo get_field('become_member_cta','option'); ?>" class="member">
                <img src="/wp-content/themes/and/assets/imgs/member-icon-cta.svg" alt="Member icon" />
                <span class="text">Become a member</span>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="container holder">
            <div class="row">

                    <?php
                        if( have_rows('page_builder') ):
                            echo '<div class="col-md-12 col-lg-9 main-area">';
                            // loop through the rows of data
                            while ( have_rows('page_builder') ) : the_row();

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


                <?php if($showOnThisPage == 'yes'): $links = '';?>

                    <?php if(!empty($onThisPageCustomLinks)):
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
                <div class="col-md-12 col-lg-3 on-this-page sidebar">
                    <div class="inner">
                        <p>On this page</p>
                        <ul>
                            <?php echo $links; ?>
                            <?php if($showOnPageMainCta == 'internship'){ ?>
                            <li class="yellow cta">
                                <a href="https://andau.force.com/forms/s/andforms?formtype=stepping_into_application&programid=<?php echo $program_id; ?>">
                                    <img src="/wp-content/themes/and/assets/imgs/mail.svg" alt="Mail icon"/>
                                    <span class="text">Apply for an internship</span>
                                </a>
                            </li>
                            <?php } else if($showOnPageMainCta == 'apply-mentee') { ?>
                            <li class="member cta">
                                <a href="<?php echo get_field('mentee_cta','option'); ?>">
                                    <img src="/wp-content/themes/and/assets/imgs/member-icon.svg" alt="Member icon"/>
                                    <span class="text">Apply to be a mentee</span>
                                </a>
                            </li>
                            <?php } else if($showOnPageMainCta == 'become-member' && !isset($_COOKIE['lgi'])) { ?>
                            <li class="member cta">
                                <a href="<?php echo get_field('become_member_cta','option'); ?>">
                                    <img src="/wp-content/themes/and/assets/imgs/member-icon.svg" alt="Member icon"/><span class="text">Become a member</span>
                                </a>
                            </li>
                            <?php } else if($showOnPageMainCta !== 'none' && !isset($_COOKIE['lgi'])) { ?>
                            <li class="member cta">
                                <a href="<?php echo get_field('member_cta','option'); ?>">
                                    <img src="/wp-content/themes/and/assets/imgs/member-icon.svg" alt="Member icon"/><span class="text">Login for Member content</span>
                                </a>
                            </li>
                            <?php } else { ?>
                                 <li class="cta"></li>
                           <?php } ?>
                        </ul>
                    </div>
                </div>

               <?php
                    endif;
               $parentDetails = getParentDetails();

                    if( have_rows('page_builder') ):
                        // loop through the rows of data
                        while ( have_rows('page_builder') ) : the_row();

                            get_template_part( 'components/team-members' );
                            get_template_part( 'components/sources-horizontal' );
                            get_template_part('components/form-embed');
                            get_template_part('components/our-members');
                            get_template_part('components/content-with-image');

                        endwhile;
                    else :
                        // no layouts found
                    endif;
                ?>

               <section class="col-12 back-page bottom">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 col-md-6 text-content-block">
                                <a href="<?php echo get_permalink($parentDetails->ID); ?>" class="btn circle dark-red">
                                    <span class="material-icons">arrow_back</span>
                                    <span class="text"><?php echo $parentDetails->post_title; ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
	</section><!-- #primary -->

<?php
    get_footer();
