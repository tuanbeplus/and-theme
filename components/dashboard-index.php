<?php
global $account_id;
$user_id = $_COOKIE['userId'];
$arr_terms = array('self-assessed','index');
$organisation_id = $account_id;
$assessments_accessible_all_users = get_assessments_accessible_all_users($organisation_id, $arr_terms);
$sf_product_id_opp = getProductIdByOpportunity();
$index_product_id = isset($sf_product_id_opp['index_product_id']) ? $sf_product_id_opp['index_product_id'] : null;
$index_assessments_list = get_assessments_related_sf_products($index_product_id, 'index') ?? null;
$accessible_list = array();
$index_list = array();

foreach ($index_assessments_list as $index_id) {
    $submission_completed = get_submissions_completed($organisation_id, $index_id);
    if (empty($submission_completed)){
        $index_list[] = $index_id;
    }
}
foreach ($assessments_accessible_all_users as $assessment_id) {
    $submission_completed = get_submissions_completed($organisation_id, $assessment_id);
    if (empty($submission_completed)){
        $accessible_list[] = $assessment_id;
    }
}

if ($accessible_list || $index_list) {
    if( get_row_layout() == 'dashboard_index' ) {
        $index_heading = get_sub_field('index_heading');
        $index_helper_text = get_sub_field('helper_text');
        ?>
        <section class="dashboard tasks index">
            <div class="container">
                <div class="inner">
                    <div class="row">
                        <div class="col-12 header">
                            <div class="inside">
                                <div class="row">
                                    <div class="col-md-6 title">
                                        <img src="/wp-content/themes/and/assets/imgs/DCR Icon.svg" alt="Index"/>
                                        <h2><?php echo $index_heading; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 the-details">
                            <p><?php echo $index_helper_text; ?></p>
                            <div class="index-purchase">
                                <ul class="assessments-list">
                                    <?php foreach ($accessible_list as $assessment_id): ?>
                                        <li>
                                            <a href="<?php echo get_the_permalink($assessment_id); ?>" target="_blank">
                                                <?php echo get_the_title($assessment_id); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>

                                    <?php foreach ($index_list as $index_id): ?>
                                        <li>
                                            <a href="<?php echo get_the_permalink($index_id); ?>" target="_blank">
                                                <?php echo get_the_title($index_id); ?>
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