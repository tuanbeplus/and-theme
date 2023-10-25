<?php
add_action( 'init', 'sf_get_event_data_from_salesforce', 10 );
function sf_get_event_data_from_salesforce() {
  if(isset($_GET['action']) && $_GET['action'] == 'event-change') {

    $xml = file_get_contents('php://input');

    if(empty($xml)) {
      return;
    }
  
    $requiredData = array();
    $result = new DOMDocument();
    $result->loadXML($xml);
  
    foreach(array(
        "Id",
        "Subject",
        "Total_Number_of_Seats__c",
        "Remaining_Seats__c",
        "Workshop_Event_Date_Text__c",
        "Workshop_Times__c",
        //"Workshops_Event__c"
    ) as $key) {
      foreach($result->getElementsByTagNameNS("urn:sobject.enterprise.soap.sforce.com", $key) as $element) {
        if($element instanceof DOMElement) {
          $requiredData[$key] = $element->textContent;
        }
      }
    }

    and_pull_event_data_from_salesforce($requiredData);

    $response = json_encode($requiredData);
    sf_log_data($response);
    
    ppsf_return_webhook();
  }
}

function ppsf_return_webhook() {
  echo '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Body>
  <notificationsResponse xmlns="http://soap.sforce.com/2005/09/outbound">
  <Ack>true</Ack>
  </notificationsResponse>
  </soapenv:Body>
  </soapenv:Envelope>';
  die;
}

