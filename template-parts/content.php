<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */

?>

<?php if(!is_single()): ?>
<article id="post-<?php the_ID(); ?>" class="col-12 col-md-6 the-card">
    <div class="inner">
        <?php the_post_thumbnail(); ?>
        <div class="detail">
            <p class="caption">Life without barriers</p>
            <?php
            if ( is_single() ) :
                the_title( '<h1 class="entry-title">', '</h1>' );
            else :
                the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' );
            endif;
            ?>
            <p class="date"><?php echo get_the_date('D d M Y'); ?></p>
            <a href="<?php echo the_permalink(); ?>" class="btn circle red">
                <span class="material-icons">arrow_forward</span>
            </a>
        </div>
    </div>
</article><!-- #post-## -->
<?php else: ?>
        <?php
            // check if the flexible content field has rows of data
            if( have_rows('page_builder') ):
                    
                // loop through the rows of data
            while ( have_rows('page_builder') ) : the_row();

                if( get_row_layout() == 'hero' ):
                    $title = get_sub_field('title');
                    $image = get_sub_field('image');
                    echo '<section class="hero">
                            <div class="container">
                                <div class="row">
                                    <div class="col-11 col-md-7 title">
                                        <h1>'.$title.'</h1>
                                    </div>
                                </div>
                            </div>
                            <div class="image" style="background-image:url('.$image['url'].');">
                                <img src="'.$image['url'].'" alt="'.$img['alt'].'" />
                            </div>
                        </section>';
                endif;

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
                        if(isset($urlParts[4]) && $urlParts[4] !== '') {
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
                                            <span class="text">'.ucwords(str_replace('-', ' ', $linkText)) .'</span>
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
                                            <div class="col-md-3 col-lg-6"></div>
                                            <div class="col-md-9 col-lg-6 overlay-text">
                                                '.$overlayText.'
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>';
                    }
                    
                endif;

                if( get_row_layout() == 'services' ):
                    $textContent = get_sub_field('text_content');
                    $textLink = get_sub_field('text_link');
                    $cards = get_sub_field('cards');

                    $cardsHtml = '';
                    foreach($cards as $card) {
                        $cardsHtml .= '<div class="col-md-6 the-card">
                                            <div class="inner">
                                                <img src="'.$card['card_image']['url'].'" alt="'.$card['card_image']['alt'].'"/>
                                                <div class="detail">
                                                    <h3>'.$card['title'].'</h3>
                                                    <a href="'.$card['link']['url'].'" class="btn circle">
                                                        <span class="material-icons">arrow_forward</span>
                                                    </a>
                                                </div>
                                            </div>
                                    </div>';
                    }
                    echo '<section class="our-services">
                            <div class="container">
                                <div class="row ">
                                    <div class="col-12 col-md-6 col-lg-5 text-content-block">
                                        '.$textContent.'
                                        <a href="'.$textLink['url'].'" class="btn circle">
                                            <span class="material-icons">arrow_forward</span>
                                            <span class="text">'.$textLink['title'].'</span>
                                        </a>
                                    </div>
                                    <div class="col-12 col-md-6 offset-lg-1 col-lg-6 cards services">
                                        <div class="row">
                                            '.$cardsHtml.'
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>';
                endif;

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
                    $overBanner = get_sub_field('over_banner');
                    $graphic = get_sub_field('graphic');

                    if($overBanner == 'yes') {
                        $extraClass = 'over-banner';
                    } else {
                        $extraClass = '';
                    }
                    if($graphic !== null && $graphic !== false) {
                        echo '<script type="text/javascript">
                                jQuery(function(){
                                    jQuery("body").addClass("side-arrow '.$extraClass.'");
                                });
                        </script>';
                    }

                    echo '<script type="text/javascript">
                                jQuery(function(){
                                    jQuery("body").addClass("side-arrow '.$extraClass.'");
                                });
                        </script>';
                endif;

                if( get_row_layout() == 'single_column_content' ):
                    $content = get_sub_field('content');
                    $contentPosition = get_sub_field('content_position');
                    $alignLeft = get_sub_field('align_left');
            
                    if($contentPosition == 'right') {
                        $html = '<div class="col-12 col-md-4 col-lg-5 text-content-block left"></div>
                                <div class="col-12 col-md-8 col-lg-7 text-content-block right">
                                    '.$content.'
                                </div>';
                    } else {
                        $html = '';
                        if($alignLeft == 'no') {
                            $html .= '<div class="col-12 col-md-4 col-lg-3"></div>';
                        }
                        $html .= '
                                <div class="col-12 col-md-8 col-lg-7 text-content-block">
                                    '.$content.'
                                </div>';
                    }
            
                    echo '<section class="single-column-content">
                            <div class="container">
                                <div class="row">
                                    '.$html.'
                                </div>
                            </div>
                        </section>';
                endif;

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

                if( get_row_layout() == 'sources_horizontal' ):
                    $leftTopContent = get_sub_field('left_top_content');
                    $leftSourcesList = get_sub_field('left_sources_list');
                    $rightTopContent = get_sub_field('right_top_content');
                    $rightSourcesList = get_sub_field('right_sources_list');

                    echo '<section class="sources source-list">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-6 text-content-block">
                                        '.$leftTopContent.'
                                        <div class="row">
                                            <div class="col-12 text-content-block">
                                                '.$leftSourcesList.'
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-6 text-content-block">
                                        '.$rightTopContent.'
                                        <div class="row">
                                            <div class="col-12 text-content-block">
                                                '.$rightSourcesList.'
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>';

                endif;

                if( get_row_layout() == 'content_horizontal' ):
                    $leftContent = get_sub_field('left_content');
                    $rightContent = get_sub_field('right_content');

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

                endif;

                if( get_row_layout() == 'back_page' ):
                    $link = get_sub_field('link');

                    echo '<section class="back-page">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12 col-md-6 text-content-block">
                                        <a href="'.$link['url'].'" class="btn circle dark-red">
                                            <span class="material-icons">arrow_back</span>
                                            <span class="text">'.$link['title'].'</span>
                                        </a>
                                    </div>
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
                                            <img src="/wp-content/themes/and-theme/assets/imgs/event.jpg" />
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
                                            <img src="/wp-content/themes/and-theme/assets/imgs/event.jpg" />
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
                                        <img src="/wp-content/themes/and-theme/assets/imgs/frame.png" alt="" />
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

                if( get_row_layout() == 'page_tiles' ):
                    $tiles = get_sub_field('tiles');
                    $tilesHtml = '';
                    foreach($tiles as $tile) {
                        if(isset($tile['link'])) {
                            $link = $tile['link'];
                        } else {
                            $link = '';
                        }
                        $tilesHtml .= '<div class="col-md-6 col-lg-4 the-card">
                                            <div class="inner">
                                                <img src="'.$tile['image']['url'].'" alt="'.$tile['image']['alt'].'"/>
                                                <div class="detail" data-mh="my-group">
                                                    '.$tile['content'].'
                                                </div>
                                                <a href="'.$link.'" class="btn circle dark-red">
                                                    <span class="material-icons">arrow_forward</span>
                                                </a>
                                            </div>
                                    </div>';
                    }
                    echo '<section class="page-tiles">
                            <div class="container">
                                <div class="row ">
                                    <div class="col-12 cards tiles">
                                        <div class="row">
                                            '.$tilesHtml.'
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>';
                endif;

            endwhile;
                    
            else :
                // no layouts found
            endif;
        ?>
<?php endif; ?>