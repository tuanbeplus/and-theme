<?php

if( get_row_layout() == 'resources_data_feed' ):

    $args = array(
        'post_type' => 'resources',
        'post_status' => 'publish'
    );

    $resources = get_posts($args);

    foreach($resources as $resource) {
        $resourceImage = get_the_post_thumbnail_url($resource->ID);

        $show = get_field('show_on_main', $resource->ID);
        if($show !== 'no' && $feed !== 'news') {
            $resourcesHtml .= '<li class="col-md-6 col-lg-6 the-card">
                <div class="inner">
                    <img src="'.$resourceImage.'" alt="Resource Image" />
                    <div class="detail">
                        <h2><a href="'.get_permalink($resource->ID).'">'.get_the_title($resource->ID).'</a></h2>
                        <p>'.get_the_excerpt($resource->ID).'</p>
                    </div>
                    <div class="btn circle">
                        <span class="material-icons">arrow_forward</span>
                    </div>
                </div>
            </li>';
        }
    }

    echo '<section class="page-tiles">
                <div class="container">
                    <div class="row ">
                        <div class="col-12 cards tiles">
                            <ul class="row">
                                '.$resourcesHtml.'
                            </ul>
                        </div>
                    </div>
                </div>
            </section>';

endif;