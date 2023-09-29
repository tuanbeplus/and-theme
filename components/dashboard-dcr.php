<?php

$user_id = $_COOKIE['userId'];
$arr_terms = array('dcr');
$organisation_id = get_user_meta( get_current_user_id(), '__salesforce_account_id', true);
$dcr_accessible_all_users = get_assessments_accessible_all_users($arr_terms);
$sf_product_id_opp = getProductIdByOpportunity();
$drc_product_id = isset($sf_product_id_opp['dcr_product_id']) ? $sf_product_id_opp['dcr_product_id'] : null;
$dcr_assessments_list = get_assessments_related_sf_products($drc_product_id, 'dcr') ?? null;
$drc_list = array();
$accessible_list = array();

foreach ($dcr_assessments_list as $dcr_id) {
    $submission_completed = get_submissions_completed($organisation_id, $dcr_id);
    if (empty($submission_completed)){
        $drc_list[] = $dcr_id;
    }
}
foreach ($dcr_accessible_all_users as $assessment_id) {
    $submission_completed = get_submissions_completed($organisation_id, $assessment_id);
    if (empty($submission_completed)){
        $accessible_list[] = $assessment_id;
    }
}

if ($drc_list || $accessible_list) {
    if( get_row_layout() == 'dashboard_dcr' ) {
        $dcr_heading = get_sub_field('dcr_heading');
        $dcr_helper_text = get_sub_field('helper_text');
        ?>
        <section class="dashboard tasks dcr">
            <div class="container">
                <div class="inner">
                    <div class="row">
                        <div class="col-12 header">
                            <div class="inside">
                                <div class="row">
                                    <div class="col-md-6 title">
                                        <img src="/wp-content/themes/and/assets/imgs/A&I Icon.svg" alt="DCR"/>
                                        <h2><?php echo $dcr_heading; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 the-details">
                            <p><?php echo $dcr_helper_text; ?></p>
                            <div class="dcr-purchase">
                                <ul class="assessments-list">
                                    <?php foreach ($drc_list as $dcr_id): ?>
                                        <li>
                                            <a href="<?php echo get_the_permalink($dcr_id); ?>" target="_blank">
                                                <?php echo get_the_title($dcr_id); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>

                                    <?php foreach ($accessible_list as $assessment_id): ?>
                                        <li>
                                            <a href="<?php echo get_the_permalink($assessment_id); ?>" target="_blank">
                                                <?php echo get_the_title($assessment_id); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}