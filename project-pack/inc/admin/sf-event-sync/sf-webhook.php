<?php
if(isset($_GET['action']) && $_GET['action'] == 'event-change') {
  echo "come here"; die;
  $xml = file_get_contents('php://input');

  if(empty($xml)) {
    return;
  }

  $requiredData = array();
  $result = new DOMDocument();
  $result->loadXML($xml);

  foreach(array(
      "Id",
      "Blade__c",
      "Capacity__c",
      "Category__c",
  ) as $key) {
      foreach($result->getElementsByTagNameNS("urn:sobject.enterprise.soap.sforce.com", $key) as $element) {
          if($element instanceof DOMElement)
          {
             $requiredData[$element->tagName] = $element->textContent;
          }
      }
  }
  $response = json_encode($requiredData);

  sf_log_data($response);

  echo '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
        <soapenv:Body>
        <notificationsResponse xmlns="http://soap.sforce.com/2005/09/outbound">
        <Ack>true</Ack>
        </notificationsResponse>
        </soapenv:Body>
        </soapenv:Envelope>';

  die;
}
