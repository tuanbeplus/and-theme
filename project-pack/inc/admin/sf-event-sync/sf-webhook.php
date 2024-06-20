<?php
add_action( 'init', 'sf_get_object_data_from_salesforce', 10 );
function sf_get_object_data_from_salesforce() {
  if(isset($_GET['action']) && $_GET['action'] == 'event-change') {
    
    // wp_remote_post('https://43220378982f26ae3cb65f4454b5273d.m.pipedream.net', [
    //   'body'    => file_get_contents('php://input'),
    // ]);

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
        "StartDateTime",
        "EndDateTime"
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

  if(isset($_GET['action']) && $_GET['action'] == 'product-change') {
    
    // wp_remote_post('https://43220378982f26ae3cb65f4454b5273d.m.pipedream.net', [
    //   'body'    => file_get_contents('php://input'),
    // ]);

    $xml = file_get_contents('php://input');

    if(empty($xml)) {
      return;
    }
  
    $requiredData = array();
    $result = new DOMDocument();
    $result->loadXML($xml);
  
    foreach(array(
        "Id",
        "Description",
        "Family",
        "IsActive",
        "Name",
        "ProductCode",
        "Woocommerce_Description__c"
    ) as $key) {
      foreach($result->getElementsByTagNameNS("urn:sobject.enterprise.soap.sforce.com", $key) as $element) {
        if($element instanceof DOMElement) {
          $requiredData[$key] = $element->textContent;
        }
      }
    }

    // Only pull Workshops or On-demand products
    if ($requiredData['Family'] == 'Workshops' || $requiredData['Family'] == 'On-demand') {
      and_pull_product_data_from_salesforce($requiredData);
    }

    if ($requiredData['Family'] == 'On-demand') {
      and_update_role_base_prices_wc_product($requiredData);
    }

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

/**
 * Update Role Base Price on WooCommerce Product
 * 
 * @param $sf_product_data    Salesforce Product2 Array data
 * 
 */
function and_update_role_base_prices_wc_product($sf_product_data) {

  // Stop if Salesforce Product2 data is empty
  if(empty($sf_product_data)) return;

  // Get the Salesforce Product2 ID
  $sf_product2_id = $sf_product_data['Id'] ?? '';

  // Get Woo Product ID
  $product_id = and_find_product_by_sfproduct_id($sf_product2_id) ?? '';

  // Only work on Woo Products  
  if (!empty($product_id) && get_post_type($product_id) == 'product') {
    // Get Role Base Prices from Theme settings
    $role_based_list = get_field('__role-based_pricing', 'option');
    // Get Member Prices
    $regular_price = get_post_meta( $product_id, '_regular_price', true );
    $member_price = get_post_meta( $product_id, 'product_role_based_price_MEMBERS', true );
    $primary_member_price = get_post_meta( $product_id, 'product_role_based_price_PRIMARY_MEMBERS', true );

    // Stop if all Role base prices has value
    if ($regular_price != '' && $member_price != '' && $primary_member_price != '') return;

    if (!empty($role_based_list) && !empty($sf_product2_id)) {
      foreach ($role_based_list as $role_base) {
        $role_name = $role_base['role']; 
        $pricebook2_id = $role_base['pricebook2']; 
        // Get UnitPrice
        $unit_price = ppsf_get_unitprice_from_pricebook_entry($pricebook2_id, $sf_product2_id);
        
        // Non-members
        if ($regular_price == '' && !empty($role_name) && $role_name == 'regular_price') {
          update_post_meta($product_id, '_regular_price', $unit_price);
        }
        // Members 
        if ($member_price == '' && !empty($role_name) && $role_name == 'MEMBERS') {
          update_post_meta($product_id, 'product_role_based_price_MEMBERS', $unit_price);
        }
        // Primary Members
        if ($primary_member_price == '' && !empty($role_name) && $role_name == 'PRIMARY_MEMBERS') {
          update_post_meta($product_id, 'product_role_based_price_PRIMARY_MEMBERS', $unit_price);
        }
      }
    }

    $regular_price = get_post_meta( $product_id, '_regular_price', true );
    $member_price = get_post_meta( $product_id, 'product_role_based_price_MEMBERS', true );
    $primary_member_price = get_post_meta( $product_id, 'product_role_based_price_PRIMARY_MEMBERS', true );

    $to = 'tom@ysnstudios.com';
    $subject = 'Product Role Base Prices has been updated';
    $message = 'Product Name: ' . get_the_title($product_id);
    $message .= '<br>';
    $message .= 'Woo Product ID: ' . $product_id;
    $message .= '<br>';
    $message .= 'SF Product2 ID: ' . $sf_product2_id;
    $message .= '<br>';
    $message .= '_regular_price: ' . $regular_price;
    $message .= '<br>';
    $message .= 'product_role_based_price_MEMBERS: ' . $member_price;
    $message .= '<br>';
    $message .= 'product_role_based_price_PRIMARY_MEMBERS: ' . $primary_member_price;
    wp_mail($to, $subject, $message);
  }
}

