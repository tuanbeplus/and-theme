<?php 
/**
 * Custom roles
 */

function pp_salesforce_roles() {
  return apply_filters( 'pp/custom_roles', [
    [
      'name' => 'PRIMARY_MEMBER',
      'label' => 'Primary Member',
      'code' => '00e9q000000LrVSAA0', // salesforce profile ID
      'capabilities' => [
        'read' => true
      ]
    ],
    [
      'name' => 'GENERAL_MEMBER',
      'label' => 'General Member',
      'code' => '00e9q000000Lqn7AAC', // salesforce profile ID
      'capabilities' => [
        'read' => true
      ]
    ],
    [
      'name' => 'NONE_MEMBER',
      'label' => 'None Member',
      'code' => '00e9q000000LrVRAA0', // salesforce profile ID
      'capabilities' => [
        'read' => true
      ]
    ],
  ] );
}

function pp_get_role_item($value, $field = 'code') {
  $r = pp_salesforce_roles();
  $found_key = array_search($value, array_column($r, $field ));

  if($found_key === false) {
    return;
  }

  return $r[$found_key];
}

function pp_add_custom_roles() {
  $r = pp_salesforce_roles();

  foreach($r as $_r) {
    // remove_role( $_r['name'] );
    add_role( $_r['name'], $_r['label'], $_r['capabilities'] );
  }
  
}
add_action( 'init', 'pp_add_custom_roles' );