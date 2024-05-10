<?php
/**
 * Template Dashboard DCR 
 *
 * @author Tuan
 */

if ( ! is_plugin_active('wp-assessment/index.php')) return;
global $account_id;
$user_id = $_COOKIE['userId'];
$arr_terms = array('dcr');
$organisation_id = $account_id;
$dcr_accessible_all_users = get_assessments_accessible_all_users($organisation_id, $arr_terms);
$sf_product_id_opp = getProductIdByOpportunity();
$drc_product_id = isset($sf_product_id_opp['dcr_product_id']) ? $sf_product_id_opp['dcr_product_id'] : null;
$dcr_list = get_assessments_related_sf_products($drc_product_id, 'dcr') ?? null;
$merge_assessment_list = array_merge($dcr_list, $dcr_accessible_all_users);
$assessment_accessible_list = get_assessments_on_dashboard($user_id, $organisation_id, $merge_assessment_list);

if ( !empty($assessment_accessible_list) ) {
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
                                        <img src="/wp-content/themes/and-theme/assets/imgs/A&I Icon.svg" alt="DCR"/>
                                        <h2><?php echo $dcr_heading; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-12 the-details">
                            <p><?php echo $dcr_helper_text; ?></p>
                            <div class="dcr-purchase">
                                <ul class="assessments-list">
                                    <?php foreach ($assessment_accessible_list as $dcr_id): ?>
                                        <li>
                                            <a href="<?php echo get_the_permalink($dcr_id); ?>" target="_blank">
                                                <?php echo get_the_title($dcr_id); ?>
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