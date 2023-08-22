<?php
// Get submissions survey from backend
$user_id = $_COOKIE['userId'];
$args = array(
    'post_type' => 'submissions',
    'posts_per_page' => -1,
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'user_id',
            'value' => $user_id,
            'compare' => '=',
        ),
        // array(
        //     'key' => 'assessment_status',
        //     'value' => 'accepted',
        //     'compare' => '=',
        // ),
    ),
);
$all_submissions = get_posts($args);

if( get_row_layout() == 'dashboard_submissions' && !empty( $all_submissions ) ):

    $submissions_heading = get_sub_field('submissions_heading');
    ?>
    <section class="dashboard tasks submissions">
        <div class="container">
            <div class="inner">
                <div class="row">
                    <div class="col-12 header">
                        <div class="inside">
                            <div class="row">
                                <div class="col-md-6 title">
                                    <img src="/wp-content/themes/and/assets/imgs/tasks.svg" alt="Submissions"/>
                                    <h2><?php echo $submissions_heading; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 the-details">
                        <ul>
                          <?php
                            if ( !empty( $all_submissions ) ) {
                                // Show submissions
                                foreach ($all_submissions as $key => $subs) { ?>
                                    <li>
                                      <a href="<?php echo get_permalink($subs->ID) ?>" target="_blank" style="color:#A22F2C;">
                                         <?php echo $subs->post_title; ?>
                                      </a>
                                    </li>
                                <?php
                                }
                            } else {
                                echo "<li>There are currently no items!</li>";
                            }
                          ?>
                        </ul>
                    </div>
            </div>
        </div>
    </section>
<?php
endif;
