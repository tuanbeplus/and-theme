<?php
/**
 * Template Dashboard Index & Self-assessed 
 *
 * @author Tuan
 */

if ( ! is_plugin_active('wp-assessment/index.php')) return;
global $assessments_accessible_list;
$index_assessments_list = array();

if (!empty($assessments_accessible_list)) {
    // Filter the assessments that contain the 'self-assessed' or 'index' terms
    $index_assessments_list = array_filter($assessments_accessible_list, function($assessment_id) {
        $terms_arr = get_assessment_terms($assessment_id);
        return in_array('self-assessed', $terms_arr) || in_array('index', $terms_arr);
    });
}

if ( get_row_layout() == 'dashboard_index' && !empty($index_assessments_list) ) {
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
                                    <img src="/wp-content/themes/and-theme/assets/imgs/DCR Icon.svg" alt="Index"/>
                                    <h2><?php echo $index_heading; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 the-details">
                        <p><?php echo $index_helper_text; ?></p>
                        <div class="index-purchase">
                            <ul class="assessments-list">
                                <?php foreach ($index_assessments_list as $index_id): ?>
                                    <li>
                                        <a href="<?php echo get_the_permalink($index_id); ?>" target="_blank">
                                            <span class="external-link-icon" role="img" aria-label="External, opens in a new tab">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </span>
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
