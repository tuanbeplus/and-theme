<?php
global $account_id;
$user_id = $_COOKIE['userId'];
// Get submissions survey from backend
$all_submissions = get_user_submissions_exist($user_id, $account_id);

if( get_row_layout() == 'dashboard_submissions' && !empty($all_submissions) ):

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
                                    <img src="<?php echo AND_IMG_URI. 'tasks.svg' ?>" alt="Submissions"/>
                                    <h2><?php echo $submissions_heading; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 the-details">
                        <ul class="submissions-list">
                          <?php
                            if ( !empty( $all_submissions ) ) {
                                // Show submissions
                                foreach ($all_submissions as $submission_id) { ?>
                                    <li>
                                        <a href="<?php echo get_permalink($submission_id) ?>" target="_blank">
                                            <span class="external-link-icon" role="img" aria-label="External, opens in a new tab">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </span>
                                            <?php echo get_the_title($submission_id); ?>
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
        </div>
    </section>
<?php
endif;
