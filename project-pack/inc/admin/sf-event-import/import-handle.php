<?php 
/**
 * Import Product Handle
 */

function ppsf_event_create_product_parent($data) {

}

function ppsf_event_add_product_child($data) {

}

function ppsf_event_check_product_parent_exists($product_sfid) {

}

function ppsf_event_check_product_child_exists($event_sfid) {
  
}

function ppsf_event_product_import($data) {

}

add_action('wp_ajax_pp_ajax_ppsf_event_product_import', 'pp_ajax_ppsf_event_product_import');
add_action('wp_ajax_nopriv_pp_ajax_ppsf_event_product_import', 'pp_ajax_ppsf_event_product_import');

function pp_ajax_ppsf_event_product_import() {
  // ppsf_event_product_import();
}