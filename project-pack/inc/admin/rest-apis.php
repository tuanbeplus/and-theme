<?php

/**
 * Rest APIs for React
 */
add_action( 'rest_api_init', 'cnb_admin_react_load_api');
function cnb_admin_react_load_api() {

    // Login
    register_rest_route( 'wp/v2','/salesforce-import-events/',array(
          'methods'  => 'POST',
          'callback' => 'and_api_import_salesfore_events_by_id'
    ));
    
}

/**
 * Import salesforce events by id function
 */
function and_api_import_salesfore_events_by_id( $request ) {

  $eventsIdStr = $request->get_param('eventIds');
  $eventsIdArr = explode(",",$eventsIdStr);

  $importRes = [];
  $failureCount = 0;
  if ($eventsIdArr) {
    foreach ($eventsIdArr as $key => $eventID) {
      $result = ppsf_import_by_junction_id($eventID);
      if ( ! $result ) {
        $importRes[] = array(
          'id' => $eventID,
          'status' => 'failure'
        );
        $failureCount++;
      } else {
        $importRes[] = array(
          'id' => $eventID,
          'status' => 'success'
        );
      }
    }
  }
  $result = ($failureCount > 0) ? 'failure' : 'success';
  $responseData = array(
    "importResult" => $importRes,
    "result" => $result,
    "failureCount" => $failureCount
  );
  wp_send_json($responseData);
}