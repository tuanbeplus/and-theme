<?php 

if( get_row_layout() == 'single_column_content' ):
    $content = get_sub_field('content');
    $contentPosition = get_sub_field('content_position');
    $alignLeft = get_sub_field('align_left');
    $whiteBackground = get_sub_field('white_background');

    if(isset($_COOKIE['lgi'])) {
         $content = get_sub_field('content');
        $content .= get_sub_field('member_content');
    } else {
        $content = $content;
    }

    $class = '';

    if($whiteBackground == 'yes') {
        $class = 'white';
    }

    if($contentPosition == 'right') {
        $html = '<div class="col-12 col-md-4 col-lg-5 text-content-block left"></div>
                <div class="col-12 col-md-8 col-lg-7 text-content-block right">
                    '.$content.'
                </div>';
    } else {
        $html = '';
        if($alignLeft == 'no' || $alignLeft == '') {
            $html .= '<div class="col-12 col-md-4 col-lg-3"></div>';
        }
        $html .= '
                <div class="col-12 text-content-block">
                    <div class="inner">
                    '.$content.'
                    </div>
                </div>';
    }

    echo '<section class="single-column-content '.$class.'">
            <div class="container">
                <div class="row">
                    '.$html.'
                </div>
            </div>
        </section>';
endif;
