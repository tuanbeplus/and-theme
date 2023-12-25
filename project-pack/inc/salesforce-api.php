<?php 
/**
 * Salesforce API 
 * 
 * @since 1.0.0 
 */

function ppsf_api_info() {
  return [
    'endpoint' => get_field('salesforce_endpoint_url', 'option'),
    'version' => get_field('salesforce_api_version', 'option')
  ];
}

function ppsf_token() {
  return get_field('salesforce_api_access_token', 'option');
}

function ppsf_remote_post($url, $args = [], $method = 'GET') {
  $_default = [
    'method' => $method,
    'headers' => [
      'Authorization' => 'Bearer ' . ppsf_token(),
    ]];

  $_args = wp_parse_args($args, $_default);
  return wp_remote_post($url, $_args);
}

/**
 * Get user infor by Salesforce User ID
 * 
 * @param string $uid
 */
function ppsf_get_user($uid) {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $url = $endpoint . '/services/data/'. $version .'/sobjects/User/' . $uid;
  $response = ppsf_remote_post($url);
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

/**
 * Object Account (label: Organisation)
 * Get Account information by Account ID
 * 
 * @param string $accountID
 */
function ppsf_get_account($accountID) {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $url = $endpoint . '/services/data/'. $version .'/sobjects/Account/' . $accountID;
  $response = ppsf_remote_post($url);
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

/**
 * Object Contact (label: Contact)
 * Get Contact information by Contact ID
 * 
 * @param string $contactID
 */
function ppsf_get_contact($contactID) {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $url = $endpoint . '/services/data/'. $version .'/sobjects/Contact/' . $contactID;
  $response = ppsf_remote_post($url);
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

/**
 * Object Opportunity (label: Opportunities)
 * Get Opportunity information by Account ID
 * 
 * @param string $accountID
 */
function ppsf_get_opportunity($accountID) {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $sql = "SELECT Id, AccountId, Name, StageName
          FROM Opportunity 
          WHERE RecordTypeId='0120I000000TONeQAO' 
          AND IsClosed=false 
          AND AccountId='".$accountID."' 
          ORDER BY CreatedDate DESC";

  $url = $endpoint . '/services/data/'. $version .'/query/?q=' . urlencode($sql);
  $response = ppsf_remote_post($url);

  return json_decode( wp_remote_retrieve_body( $response ), true );
}

function ppsf_get_junctions() {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $sql = "SELECT Id, Name, CreatedDate, Child_Event__c, Parent_Event__c 
          FROM Junction_Workshop_Event__c 
          ORDER BY CreatedDate DESC";
  $url = $endpoint . '/services/data/'. $version .'/query/?q=' . urlencode($sql);
  $response = ppsf_remote_post($url);
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

function ppsf_get_junction_item($junction_id) {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $url = $endpoint . '/services/data/'. $version .'/sobjects/Junction_Workshop_Event__c/' . $junction_id;
  $response = ppsf_remote_post($url);
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

function ppsf_get_event($eventID) {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();
  
  $url = $endpoint . '/services/data/'. $version .'/sobjects/Event/' . $eventID;
  $response = ppsf_remote_post($url);
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

function ppsf_get_events() {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $sql = "SELECT Id, Subject, Total_Number_of_Seats__c, Remaining_Seats__c, Workshop_Event_Date_Text__c, Workshop_Times__c, WhoId, WhatId, DurationInMinutes, Description
          FROM Event 
          WHERE Workshops_Event__c=true";

  $url = $endpoint . '/services/data/'. $version .'/query/?q=' . urlencode($sql);
  $response = ppsf_remote_post($url);
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

function ppsf_get_all_products() {
  list(
    'endpoint' => $endpoint,
    'version' => $version, 
  ) = ppsf_api_info();

  $sql = "SELECT Id, Name, ProductCode, Description, Family, Woocommerce_Description__c 
          FROM Product2";

  $url = $endpoint . '/services/data/'. $version .'/query/?q=' . urlencode($sql);
  $response = ppsf_remote_post($url);
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

function ppsf_get_EventRelation_by_event_Id($eventId) {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $sql = "SELECT Id, RelationId, EventId, AccountId, Status, CreatedDate, IsDeleted 
        FROM EventRelation 
        WHERE EventId='". $eventId ."'"; //  AND IsWhat=false
  
  $url = $endpoint . '/services/data/'. $version .'/query/?q=' . urlencode($sql);
  $response = ppsf_remote_post($url);
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

function ppsf_add_EventRelation($EventId, $RelationId) {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $fields = [
    "EventId" => $EventId,
    "RelationId" => $RelationId,
    "IsInvitee" => true
  ];

  $url = $endpoint . '/services/data/'. $version .'/sobjects/EventRelation/';
  $response = ppsf_remote_post($url, [
    'body' => wp_json_encode($fields),
    'headers'     => [
      'Content-Type' => 'application/json;charset=utf-8',
      'Authorization' => 'Bearer ' . ppsf_token(),
    ],
  ], 'POST');

  return json_decode( wp_remote_retrieve_body( $response ), true );
}

function ppsf_delete_EventRelation_record($record_id) {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $url = $endpoint . '/services/data/'. $version .'/sobjects/EventRelation/' . $record_id;
  $response = ppsf_remote_post($url, [], 'DELETE');
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

function ppsf_find_contact_by_email($email) {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $sql = "SELECT FIELDS(ALL) FROM Contact WHERE Email='". $email ."'  LIMIT 1";
  $url = $endpoint . '/services/data/'. $version .'/query/?q=' . urlencode($sql);
  $response = ppsf_remote_post($url);
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

function ppsf_add_new_contact($fields) {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $url = $endpoint . '/services/data/'. $version .'/sobjects/Contact';
  $response = ppsf_remote_post($url, [
    'body' => wp_json_encode($fields),
    'headers'     => [
      'Content-Type' => 'application/json;charset=utf-8',
      'Authorization' => 'Bearer ' . ppsf_token(),
    ],
  ], 'POST'); 
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

function ppsf_get_Pricebook2() {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $sql = "SELECT FIELDS(ALL) FROM Pricebook2 LIMIT 200";
  $url = $endpoint . '/services/data/'. $version .'/query/?q=' . urlencode($sql);
  $response = ppsf_remote_post($url);
  return json_decode( wp_remote_retrieve_body( $response ), true );
}

function ppsft_get_PricebookEntry_by_Product2ID($product2ID) {
  list(
    'endpoint' => $endpoint,
    'version' => $version,
  ) = ppsf_api_info();

  $sql = "SELECT FIELDS(ALL) FROM PricebookEntry WHERE Product2Id='". $product2ID ."' LIMIT 200";
  $url = $endpoint . '/services/data/'. $version .'/query/?q=' . urlencode($sql);
  $response = ppsf_remote_post($url);
  return json_decode( wp_remote_retrieve_body( $response ), true );
}