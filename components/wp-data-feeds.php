<?php

if( get_row_layout() == 'wp_data_feeds' ):

    $feed = get_sub_field('feed');

    if($feed == 'news') {
        $args = array(
            'post_type' => 'news_and_events',
            'post_status' => 'publish',
            'posts_per_page' => 100,
            'tax_query' => array(
                array(
                    'taxonomy' => 'section',
                    'field' => 'term_id',
                    'terms' => array(26,27,28,29,30,31,32,33)
                )
            )
        );
    } else {
        $args = array(
            'post_type' => $feed,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        );
    }

    $the_query = new WP_Query( $args );
    $feedDatas = array();

    // The Loop
    if ( $the_query->have_posts() ) :
        while ( $the_query->have_posts() ) : $the_query->the_post();
            $feedDatas[] = get_the_ID();
        endwhile;
    endif;
    // Reset Post Data
    wp_reset_postdata();
    
    if($feed == 'news_and_events' || $feed == 'news') {
        $cardClass = 'col-lg-6';
    } else {
        $cardClass = 'col-lg-4';
    }
    $feedDataHtml = '';

    foreach($feedDatas as $feedData) {
        $feedImage = get_the_post_thumbnail_url($feedData);
        if (!$feedImage) {
            $feedImage = '/wp-content/uploads/2022/01/pexels-cottonbro-3401897-scaled.jpg';
        }

        $show = get_field('show_on_main', $feedData);
        if($show !== 'no' && $feed !== 'news') {
            $tilesClass = '';
            $feedDataHtml .= '<li class="col-md-12 '.$cardClass.' the-card">
                <div class="inner">
                    <img src="'.$feedImage.'" alt="feed image"/>
                    <div class="detail">
                        <h2><a href="'.get_permalink($feedData).'">'.get_the_title($feedData).'</a></h2>
                        <p>'.get_the_excerpt($feedData).'</p>
                    </div>
                    <div class="btn circle">
                        <span class="material-icons">arrow_forward</span>
                    </div>
                </div>
            </li>';
        }
        if($feed == 'news') {
            $tilesClass = 'news';
             $feedDataHtml .= '<li class="col-md-12 '.$cardClass.' the-card">
                <div class="inner">
                    <img src="'.$feedImage.'" alt="feed image" />
                    <div class="detail">
                    <p class="category">'.get_the_terms($feedData,'section')[0]->name.'</p>
                        <h2><a href="'.get_permalink($feedData).'">'.get_the_title($feedData).'</a></h2>
                        <p class="date">'.get_the_date('l d F Y', $feedData).'</p>
                    </div>
                    <a href="'.get_permalink($feedData).'" class="btn circle dark-red">
                        <span class="material-icons">arrow_forward</span>
                    </a>
                </div>
            </li>';
        }
    }

    echo '<section class="page-tiles '.$tilesClass.'">
                <div class="container">
                    <div class="row ">
                        <div class="col-12 cards tiles">
                            <ul class="row">
                                '.$feedDataHtml.'
                            </ul>
                        </div>
                    </div>
                </div>
            </section>';

endif;
