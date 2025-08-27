<?php 

if( get_row_layout() == 'single_column_content' ):
    $content = get_sub_field('content');
    $member_content = get_sub_field('member_content');
    $contentPosition = get_sub_field('content_position');
    $alignLeft = get_sub_field('align_left');
    $whiteBackground = get_sub_field('white_background');

    // If user is logged in and has a userId cookie, append member content if available
    if (is_user_logged_in() && !empty($_COOKIE['userId']) && !empty($member_content)) {
        $content .= $member_content;
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
                    <div class="inner single-column-content">
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

$resources = get_sub_field('resource_media');

if ($resources) {
  foreach ($resources as $post) {
    setup_postdata($post);
    get_template_part('template-parts/resource-media');
  }
  wp_reset_postdata();
}

$resources_members_only = get_sub_field('resource_media_members_only');
if ($resources_members_only && isset($_COOKIE['lgi'])) {
  foreach ($resources_members_only as $post) {
    setup_postdata($post);
    get_template_part('template-parts/resource-media');
  }
  wp_reset_postdata();
}
?>