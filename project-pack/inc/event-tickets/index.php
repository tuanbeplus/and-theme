<?php 
/**
 * Event ticket custom
 * 
 * @version 1.0.0
 * @since 1.0.0
 */

add_filter( 'tec_tickets_commerce_gateways', function($gateways) {
  // echo '<pre>'; print_r($gateways); echo '</pre>'; 
  return $gateways;
} );

// add_filter( 'tec_tickets_commerce_payments_tab_sections', function($sections) {
//   // echo '<pre>'; print_r($sections); echo '</pre>'; 

//   $sections[] = [
//     'classes' => [],
//     'slug'    => 'custom',
//     'url'     => '',
//     'text'    => 'Custom',
//   ];
//   return $sections;
// } );