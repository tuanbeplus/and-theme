<?php
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
      "Blade__c",
      "Capacity__c",
      "Category__c",
      "Condition__c",
      "Description",
      "Family",
      "Fuel_Type__c",
      "HP__c",
      "HRS_Engine__c",
      "HRS__c",
      "Height__c",
      "Industry__c",
      "IsActive",
      "Length__c",
      "Location__c",
      "Manufacturer__c",
      "Marketability__c",
      "Model__c",
      "Name",
      "Price__c",
      "ROPS__c",
      "Serial__c",
      "Track_Type__c",
      "Track_Width__c",
      "Unit_Description_Short__c",
      "Water_Capacity_gal__c",
      "Weight__c",
      "Width__c",
      "Year__c",
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
