<?php
/**
 * Template Dashboard DCR 
 *
 * @author Tuan
 */

if ( ! is_plugin_active('wp-assessment/index.php')) return;
global $assessments_accessible_list;
$dcr_assessments_list = array();

if (!empty($assessments_accessible_list)) {
    // Filter the assessments that contain the 'dcr' term
    $dcr_assessments_list = array_filter($assessments_accessible_list, function($assessment_id) {
        $terms_arr = get_assessment_terms($assessment_id);
        return in_array('dcr', $terms_arr);
    });
}

if ( get_row_layout() == 'dashboard_dcr' && !empty($dcr_assessments_list) ) {
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
                                <?php foreach ($dcr_assessments_list as $dcr_id): ?>
                                    <li>
                                        <a href="<?php echo get_the_permalink($dcr_id); ?>" target="_blank">
                                            <span class="external-link-icon" role="img" aria-label="External, opens in a new tab">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </span>
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
