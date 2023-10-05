<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WP_Bootstrap_4
 */

get_header(); ?>


<?php
// check if the flexible content field has rows of data
if( have_rows('page_builder') ):

   $counter = 0;

    // loop through the rows of data
   while ( have_rows('page_builder') ) : the_row();

   $counter++;


    if( get_row_layout() == 'hero_with_breadcrumb' ):
        $title = get_sub_field('title');
        $image = get_sub_field('image');
        $overlay = get_sub_field('overlay');
        $overlayText = get_sub_field('overlay_text');
        echo '<div class="breadcrumbs-top">
                    <div class="container">';
               bcn_display($return = false, $linked = true, $reverse = false, $force = false);
        echo ' </div>
        </div>';


        if($overlay !== 'yes') {
            $urlParts = explode('/', $_SERVER['REQUEST_URI']);
            if($image == false) {
                $heroClass = 'no-image';
            } else {
                $heroClass = '';
            }

            if(isset($urlParts[5]) && $urlParts[5] !== '') {
                $link = $urlParts[1].'/'.$urlParts[2].'/'.$urlParts[3].'/'.$urlParts[4];
                $linkText = $urlParts[4];
            } elseif(isset($urlParts[4]) && $urlParts[4] !== '') {
                $link = $urlParts[2].'/'.$urlParts[3];
                $linkText = $urlParts[3];
            } else if(isset($urlParts[3]) && $urlParts[3] !== '') {
                $link = $urlParts[1].'/'.$urlParts[2];
                $linkText = $urlParts[2];
            } else {
                $link = $urlParts[1];
                $linkText = $urlParts[1];
            }

            echo '<section class="hero breadcrumbs '.$heroClass.'">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-4 col-lg-3 backlink">
                            <a href="/'.$link.'/" class="btn circle">
                                <span class="material-icons">arrow_back</span>
                                <span class="text">'.ucfirst(str_replace('-', ' ', $linkText)).'</span>
                            </a>
                        </div>
                        <div class="col-12 col-md-8 col-lg-8 title">
                            <h1>'.$title.'</h1>
                        </div>
                    </div>
                </div>
                <div class="image" style="background-image:url('.$image['url'].');">
                    <img src="'.$image['url'].'" alt="'.$img['alt'].'" />
                </div>
            </section>';
        } else {
            echo '<section class="hero breadcrumbs overlays">
                    <div class="image" style="background-image:url('.$image['url'].');">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 col-lg-6"></div>
                                <div class="col-md-6 col-lg-6 overlay-text">
                                    '.$overlayText.'
                                </div>
                            </div>
                        </div>
                    </div>
                </section>';
        }

    endif;

    if( get_row_layout() == 'hero_text' ):
        $heroText = get_sub_field('hero_text');
        $image = get_sub_field('image');
        echo '<section class="hero-text">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-8 title">
                            '.$heroText.'
                        </div>
                    </div>
                </div>
            </section>';
    endif;

    // if( get_row_layout() == 'services' ):
    //     $textContent = get_sub_field('text_content');
    //     $textLink = get_sub_field('text_link');
    //     $cards = get_sub_field('cards');

    //     $cardsHtml = '';
    //     foreach($cards as $card) {
    //         $cardsHtml .= '<div class="col-md-6 the-card">
    //                             <div class="inner">
    //                                 <img src="'.$card['card_image']['url'].'" alt="'.$card['card_image']['alt'].'"/>
    //                                 <div class="detail">
    //                                     <h3>'.$card['title'].'</h3>
    //                                     <a href="'.$card['link']['url'].'" class="btn circle">
    //                                         <span class="material-icons">arrow_forward</span>
    //                                     </a>
    //                                 </div>
    //                             </div>
    //                        </div>';
    //     }
    //     echo '<section class="our-services">
    //             <div class="container">
    //                 <div class="row ">
    //                     <div class="col-12 col-md-6 col-lg-5 text-content-block">
    //                         '.$textContent.'
    //                         <a href="'.$textLink['url'].'" class="btn circle">
    //                             <span class="material-icons">arrow_forward</span>
    //                             <span class="text">'.$textLink['title'].'</span>
    //                         </a>
    //                     </div>
    //                     <div class="col-12 col-md-6 offset-lg-1 col-lg-6 cards services">
    //                         <div class="row">
    //                             '.$cardsHtml.'
    //                         </div>
    //                     </div>
    //                 </div>
    //             </div>
    //         </section>';
    // endif;

    if( get_row_layout() == 'asset_with_content' ):
        $asset = get_sub_field('asset');
        $textContent = get_sub_field('content');
        $textLink = get_sub_field('text_link');

        if(isset($textLink['url'])) {
            $linkHtml = '<a href="'.$textLink['url'].'" class="btn circle">
                            <span class="material-icons">arrow_forward</span>
                            <span class="text">'.$textLink['title'].'</span>
                        </a>';
        } else {
            $linkHtml = '';
        }

        echo '<section class="content-with-asset">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-6 text-content-block">
                            '.$textContent.'
                            '.$linkHtml.'
                        </div>
                        <div class="col-12 col-md-6">
                            <img src="'.$asset['url'].'" alt="'.$asset['alt'].'"/>
                            <div class="caption">
                                '.$asset['caption'].'
                            </div>
                        </div>
                    </div>
                </div>
            </section>';
    endif;

    if( get_row_layout() == 'rectangle_with_content' ):
        $rectangle1Figure = get_sub_field('rectangle_1_figure');
        $rectangle1Text = get_sub_field('rectangle_1_text');
        $textContent = get_sub_field('content');
        $textLink = get_sub_field('text_link');
        $side = get_sub_field('side');

        if($side == 'left') {
            $textClass = 'col-md-6 col-lg-5';
            $recClass = 'col-md-6 col-lg-6 offset-lg-1';
        } else {
            $textClass = 'col-md-6';
            $recClass = 'col-md-6';
        }


        echo '<section class="rectangle-with-content '.$side.'">
                <div class="container">
                    <div class="row">
                        <div class="col-12 '.$textClass.' text-content-block">
                            '.$textContent.'
                        </div>
                        <div class="col-12 '.$recClass.' rectangles">
                            <div class="rectangle-1">
                                <span class="figure">
                                    '.$rectangle1Figure.'
                                </span>
                                <span class="text">
                                    '.$rectangle1Text.'
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>';
    endif;

    if( get_row_layout() == 'side_graphic' ):
        $content = get_sub_field('content');
        $overBanner = get_sub_field('over_banner');

        if($overBanner == 'yes') {
            $extraClass = 'side-arrow over-banner';
        } else {
            $extraClass = '';
        }
        echo '<script type="text/javascript">
                    jQuery(function(){
                        jQuery("body").addClass("'.$extraClass.'");
                    });
              </script>';
    endif;

    get_template_part( 'components/single-column-content' );

    if( get_row_layout() == 'sources' ):
        $textContent = get_sub_field('top_content');
        $textLink = get_sub_field('text_link');
        $sourcesList = get_sub_field('sources_list');

        $sourcesHtml = '';
        foreach($sourcesList as $source) {
            $sourcesHtml .= '<div class="col-md-4">
                                    '.$source['content'].'
                            </div>';
        }
        echo '<section class="sources">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-8 col-lg-6 text-content-block">
                            '.$textContent.'
                            <a href="'.$textLink['url'].'" class="btn circle">
                                <span class="material-icons">arrow_forward</span>
                                <span class="text">'.$textLink['title'].'</span>
                            </a>
                        </div>
                    </div>
                    <div class="row source-list">
                        '. $sourcesHtml.'
                    </div>
                </div>
            </section>';

        echo '<section class="back-page">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-8 col-lg-6 text-content-block">
                        <a href="#" class="btn circle">
                            <span class="material-icons">arrow_back</span>
                            <span class="text">Resources</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>';
    endif;


    if( get_row_layout() == 'content_horizontal' ):
        $leftContent = get_sub_field('left_content');
        $rightContent = get_sub_field('right_content');

        echo '<section class="content-horizontal">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6 text-content-block left">
                            '.$leftContent.'
                        </div>';
        if($rightContent !== '') {
            echo '<div class="col-12 col-md-6 col-lg-6 text-content-block right">
                '.$rightContent.'
            </div>';
        }
        echo '
                    </div>
                </div>
            </section>';

    endif;



    if( get_row_layout() == 'resources' ):
        $rectangle1Figure = get_sub_field('rectangle_1_figure');
        $rectangle1Text = get_sub_field('rectangle_1_text');
        $rectangle2Figure = get_sub_field('retangle_2_figure');
        $rectangle2Text = get_sub_field('rectangle_2_text');
        $textContent = get_sub_field('content');
        $textLink = get_sub_field('text_link');

        echo '<section class="resources">
                <div class="container">
                    <div class="row switch-mobile">
                        <div class="col-12 col-md-4 rectangles">
                            <div class="rectangle-1">
                                <span class="figure">
                                    '.$rectangle1Figure.'
                                </span>
                                <span class="text">
                                    '.$rectangle1Text.'
                                </span>
                            </div>
                            <div class="rectangle-2">
                                <span class="figure">
                                    '.$rectangle2Figure.'
                                </span>
                                <span class="text">
                                    '.$rectangle2Text.'
                                </span>
                            </div>
                        </div>
                        <div class="col-12 offset-md-2 col-md-6 text-content-block">
                            '.$textContent.'
                            <a href="'.$textLink['url'].'" class="btn circle">
                                <span class="material-icons">arrow_forward</span>
                                <span class="text">'.$textLink['title'].'</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>';
    endif;

    if( get_row_layout() == 'case_study' ):
        $title = get_sub_field('title');
        $link = get_sub_field('link');
        $image = get_sub_field('image');

        echo '<section class="case-study">
                <div class="container">
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4 overlay-block">
                            <p class="caption">Case Study</p>
                            <h2>'.$title.'</h2>
                            <a href="'.$link['url'].'" class="btn circle white">
                                <span class="material-icons">arrow_forward</span>
                                <span class="text">'.$link['title'].'</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="image" style="background-image:url('.$image['url'].');">
                    <img src="'.$image['url'].'" alt="'.$img['alt'].'" />
                </div>
            </section>';
    endif;

    if( get_row_layout() == 'latest_news' ):

        echo '<section class="latest-news">
                <div class="container">
                    <div class="row titles">
                        <div class="col-md-6 text">
                            <h2>Latest news</h2>
                        </div>
                        <div class="col-md-6 link text-right d-none d-md-inline">
                            <a href="#" class="btn circle">
                                <span class="material-icons">arrow_forward</span>
                                <span class="text">All news</span>
                            </a>
                        </div>
                    </div>
                    <div class="row cards">
                        <div class="col-12 col-md-4 the-card">
                            <div class="inner">
                                <div class="detail">
                                    <p class="caption">Life without barriers</p>
                                    <h3>15 years on: Stepping Into Alumni’s full circle moment</h3>
                                    <p class="date">Tue 23 June 2020</p>
                                    <a href="'.$card['link']['url'].'" class="btn circle">
                                        <span class="material-icons">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 the-card">
                            <div class="inner">
                                <div class="detail">
                                    <p class="caption">Life without barriers</p>
                                    <h3>15 years on: Stepping Into Alumni’s full circle moment</h3>
                                    <p class="date">Tue 23 June 2020</p>
                                    <a href="'.$card['link']['url'].'" class="btn circle">
                                        <span class="material-icons">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 the-card">
                            <div class="inner">
                                <div class="detail">
                                    <p class="caption">Life without barriers</p>
                                    <h3>15 years on: Stepping Into Alumni’s full circle moment</h3>
                                    <p class="date">Tue 23 June 2020</p>
                                    <a href="'.$card['link']['url'].'" class="btn circle">
                                        <span class="material-icons">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 link-footer d-block d-md-none">
                            <a href="#" class="btn circle">
                                <span class="material-icons">arrow_forward</span>
                                <span class="text">All news</span>
                            </a>
                        </div>
                    </div>

                </div>
            </section>';
    endif;


    if( get_row_layout() == 'upcoming_events' ):

        echo '<section class="upcoming-events">
                <div class="container">
                    <div class="row titles">
                        <div class="col-md-6 text">
                            <h2>Upcoming events</h2>
                        </div>
                        <div class="col-md-6 link text-right d-none d-md-inline">
                            <a href="#" class="btn circle">
                                <span class="material-icons">arrow_forward</span>
                                <span class="text">All events</span>
                            </a>
                        </div>
                    </div>
                    <div class="row cards">
                        <div class="col-12 col-md-6 the-card">
                            <div class="inner">
                                <img src="/wp-content/themes/and/assets/imgs/event.jpg" />
                                <div class="detail">
                                    <div class="row info">
                                        <div class="col-md-6">
                                            <span class="number">
                                                27
                                            </span>
                                            <span class="month">
                                                AUG <br/>
                                                2020
                                            </span>
                                        </div>
                                        <div class="col-md-6 time text-right">
                                            <span>12pm-1:30pm</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Session 1: Supporting employees with disability through new ways of working</h3>
                                        </div>
                                    </div>
                                    <a href="#" class="btn circle">
                                        <span class="material-icons">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 the-card">
                            <div class="inner">
                                <img src="/wp-content/themes/and/assets/imgs/event.jpg" />
                                <div class="detail">
                                    <div class="row info">
                                        <div class="col-6 col-md-6">
                                            <span class="number">
                                                27
                                            </span>
                                            <span class="month">
                                                AUG <br/>
                                                2020
                                            </span>
                                        </div>
                                        <div class="col-6 col-md-6 time text-right">
                                            <span>12pm-1:30pm</span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h3>Session 1: Supporting employees with disability through new ways of working</h3>
                                        </div>
                                    </div>
                                    <a href="#" class="btn circle">
                                        <span class="material-icons">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 link d-block d-md-none">
                            <a href="#" class="btn circle">
                                <span class="material-icons">arrow_forward</span>
                                <span class="text">All events</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>';
    endif;

    if( get_row_layout() == 'about_us' ):
        $textContent = get_sub_field('text_content');
        $textLink = get_sub_field('text_link');
        echo '<section class="about-us component">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 image">
                            <img src="/wp-content/themes/and/assets/imgs/frame.png" alt="Frame image" />
                        </div>
                        <div class="col-md-6 col-lg-6 text-content-block">
                            '.$textContent.'
                            <a href="'.$textLink['url'].'" class="btn circle">
                                <span class="material-icons">arrow_forward</span>
                                <span class="text">'.$textLink['title'].'</span>
                            </a>
                        </div>
                    </div>
                </div>
            </section>';
    endif;


    get_template_part('components/hero');
    get_template_part('components/hero-with-shape');
    get_template_part('components/content-with-image');
    get_template_part('components/page-tiles');
    get_template_part('components/quotes');
    get_template_part('components/back-page');
    get_template_part('components/wp-data-feeds');
    get_template_part('components/resources-data-feed');
    get_template_part('components/form-embed');
    get_template_part('components/form-search');
    get_template_part('components/slides');
    get_template_part('components/text-blocks');
    get_template_part('components/posts-grid');


endwhile;

else :
    // no layouts found
endif;

get_footer();
