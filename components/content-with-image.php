<?php 

if( get_row_layout() == 'content_with_image' ):
    $content = get_sub_field('content');
    $image = get_sub_field('image');
    $mobileImage = get_sub_field('image_mobile');
    $contentPosition = get_sub_field('content_position');

    if($contentPosition == 'right') {
        $html = '<div class="col-12 col-md-4 col-lg-4 image">
                    <img src="'.$image['url'].'" alt="'.$image['alt'].'" class="d-none d-md-block"/>
                    <img src="'.$mobileImage['url'].'" alt="'.$mobileImage['alt'].'" class="d-md-none mobile-size"/>
                </div>
                <div class="col-12 offset-md-1 col-md-7 text-content-block">
                    '.$content.'
                </div>';
    } else {
        $html .= ' <div class="col-12 offset-md-1 col-md-7 col-lg-7 text-content-block">
                    '.$content.'
                </div>
                <div class="col-12 col-md-4 col-lg-4 image">
                    <img src="'.$image['url'].'" alt="'.$image['alt'].'" class="d-none d-md-block"/>
                    <img src="'.$mobileImage['url'].'" alt="'.$mobileImage['alt'].'" class="d-md-none mobile-size"/>
                </div>';
    }

    echo '<section class="content with-image '.$contentPosition.'">
            <div class="container">
                <div class="row">
                    '.$html.'
                </div>
            </div>
        </section>';
endif;
