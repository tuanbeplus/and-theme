<?php
/** 
 * Function to log data
 */
function sf_log_data( $data ) {
  $file = SF_DIR . 'sf-sync-log.txt';
  $current = file_get_contents($file);
  $datetime = date('Y-m-d H:i:s');
  $current .= "\nDate Time: {$datetime}\n Data: {$data} \n";
  file_put_contents($file, $current);
}

/** 
 * Function to push Event data from WP to Salesforce
 */
add_action('save_post', 'and_push_event_data_to_salesforce', 10 , 3);
function and_push_event_data_to_salesforce($post_id, $post, $update){

  if( $post->post_type == 'sf-event' && $post->post_status == "publish" ){
      $sf_event_id = get_field('sf_event_id', $post_id );
      if ( ! $sf_event_id ) return;

      $remaining_seats__c = get_field('remaining_seats__c', $post_id );
      $sf_endpoint_url = get_field('salesforce_endpoint_url', 'options');
      $sf_api_version = get_field('salesforce_api_version', 'options');
      $sf_api_access_token = get_field('salesforce_api_access_token', 'options');
      
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $sf_endpoint_url.'/services/data/'.$sf_api_version.'/sobjects/Event/'.$sf_event_id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'PATCH',
        CURLOPT_POSTFIELDS =>'{
          "remaining_seats__c": "'.$remaining_seats__c.'"
        }',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          'Authorization: Bearer '.$sf_api_access_token,
        ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      // echo $response;
  }
}

/** 
 * Function to push Event data from WP to Salesforce
 */
function and_pull_event_data_from_salesforce($event_fields){
  ob_start();
  $event_id = ppsf_find_event_by_sfevent_id($event_fields['Id']);
  if($event_id) {
    foreach($event_fields as $name => $value) {
      if ( $name == "Subject" ) {
        $post_args = [
          "ID" => (int) $event_id,
          "post_title" => $value
        ];
        sf_log_data(wp_json_encode( $post_args ));
        wp_update_post($post_args);
      } else {
        update_post_meta($event_id, strtolower($name), $value);
      }
    }
  }
  ob_get_clean();
}