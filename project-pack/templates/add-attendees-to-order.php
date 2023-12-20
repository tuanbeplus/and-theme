<?php 
/**
 * add-attendees-to-order
 */
// var_dump($atts);
$order_id = (int) $atts['order_id'];
$order = wc_get_order($order_id);
$sf_user_metadata = pp_saleforce_current_user_metadata();
$account_id = isset($sf_user_metadata['account_id']) ? $sf_user_metadata['account_id'] : '';
$account_name = isset($sf_user_metadata['salesforce_account']['Name']) ? $sf_user_metadata['salesforce_account']['Name'] : '';
$attendees = pp_get_attendees_by_order($order_id);
echo '<pre>'; print_r($attendees); echo '</pre>'; 
?>
<div class="add-attendees-to-order">
  <div class="add-attendees-to-order__inner add-attendees-container">
    <form id="ADD_ATTENDEES_FORM" action="" method="POST" class="pp-form">
      <?php 
      foreach($order->get_items() as $item_key => $item) {
        
        // echo '<pre>'; print_r($item); echo '</pre>';
        $course_information = $item->get_meta('course_information');
        // $__SF_CONTACT_FULL = $item->get_meta('__SF_CONTACT_FULL'); 
        echo '<pre>'; print_r($course_information); echo '</pre>';
        // echo '<pre>'; print_r($__SF_CONTACT_FULL); echo '</pre>';
        $product_id = $item['product_id'];
        $variation_id = $item['variation_id'];
        $quantity = $item['quantity'];
        $_attendees = array_values(array_filter($attendees, function($a) use($item_key) { return $item_key == $a['item_key']; }));

        if(!isset($course_information) || empty($course_information)) continue;
        // print_r([$product_id, $variation_id, $quantity]);
      ?>
      <div class="__item">
        <h4 class="product-name"><?php echo $course_information['name'] ?></h4>
        <table class="pp-table add-attendees-table">
          <thead>
            <tr>
              <th><?php _e('Slot', 'pp') ?></th>
              <th width="30%"><?php _e('Email Address', 'pp') ?></th>
              <th width="20%"><?php _e('First Name', 'pp') ?></th>
              <th width="20%"><?php _e('Last Name', 'pp') ?></th>
              <th width="20%"><?php _e('Organisation', 'pp') ?></th>
              <th><?php _e('Status', 'pp') ?></th>
            </tr>
          </thead>
          <tbody>
            <?php for($i = 0; $i <= ($quantity - 1); $i++) : 
              $slot_item_data = isset($_attendees[$i]) ? $_attendees[$i] : [];
              $rid = isset($slot_item_data['relation_id']) ? $slot_item_data['relation_id'] : '';
              $email = isset($slot_item_data['email']) ? $slot_item_data['email'] : '';
            ?>
            <tr class="__slot-item">
              <td class="__slot-number">
                <?php 
                if(empty($rid)) {
                  echo '#' . $i + 1;
                } else {
                  echo '<a href="#" class="__remove-item" data-rid="'. $rid .'" data-order-id="'. $order_id .'">✕ Clean <span class="__std">#'. $i + 1 .'</span></a>';
                }
                ?>
              </td>
              <td class="__td-email">
                <input 
                  data-event-parent-id="<?php echo $course_information['event_parent']['sf_event_id'] ?>" 
                  name="email[<?php echo $item_key ?>][]" 
                  type="email" 
                  placeholder="<?php _e('Ex: sara@gmail.com', 'pp') ?>" 
                  <?php echo !empty($rid) ? 'readonly' : '' ?>
                  required 
                  value="<?php echo isset($slot_item_data['email']) ? $slot_item_data['email'] : '' ?>" />
                <input 
                  name="contact_id[<?php echo $item_key ?>][]" 
                  type="hidden" 
                  value="<?php echo isset($slot_item_data['contact_id']) ? $slot_item_data['contact_id'] : '' ?>" />
                <div class="error-message"></div>
                <input 
                  type="hidden" 
                  name="relation_id[<?php echo $item_key ?>][]" 
                  value="<?php echo $rid; ?>">
              </td>
              <td>
                <input 
                  name="firstname[<?php echo $item_key ?>][]" 
                  type="text" 
                  placeholder="<?php _e('Ex: Sara', 'pp') ?>" 
                  required 
                  <?php echo isset($slot_item_data['firstname']) ? 'readonly' : '' ?>
                  value="<?php echo isset($slot_item_data['firstname']) ? $slot_item_data['firstname'] : '' ?>" />
              </td>
              <td>
                <input 
                  name="lastname[<?php echo $item_key ?>][]" 
                  type="text" 
                  placeholder="<?php _e('Ex: Jones', 'pp') ?>"  
                  required 
                  <?php echo isset($slot_item_data['lastname']) ? 'readonly' : '' ?>
                  value="<?php echo isset($slot_item_data['lastname']) ? $slot_item_data['lastname'] : '' ?>" />
              </td>
              <td>
                <span class="organisation-text" data-default-text="<?php echo $account_name; ?>">
                  <?php echo isset($slot_item_data['account_id']) ? $slot_item_data['account_id'] : $account_name ?>
                </span>
                <input name="organisation[<?php echo $item_key ?>][]" data-default-value="<?php echo $account_id ?>" value="<?php echo $account_id ?>" readonly type="hidden" placeholder="<?php _e('Organisation', 'pp') ?>" value="<?php echo isset($slot_item_data['account_id']) ? $slot_item_data['account_id'] : '' ?>" />  
              </td>
              <td>
                <span class="__status-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M7.25007 2.38782C8.54878 2.0992 10.1243 2 12 2C13.8757 2 15.4512 2.0992 16.7499 2.38782C18.06 2.67897 19.1488 3.176 19.9864 4.01358C20.824 4.85116 21.321 5.94002 21.6122 7.25007C21.9008 8.54878 22 10.1243 22 12C22 13.8757 21.9008 15.4512 21.6122 16.7499C21.321 18.06 20.824 19.1488 19.9864 19.9864C19.1488 20.824 18.06 21.321 16.7499 21.6122C15.4512 21.9008 13.8757 22 12 22C10.1243 22 8.54878 21.9008 7.25007 21.6122C5.94002 21.321 4.85116 20.824 4.01358 19.9864C3.176 19.1488 2.67897 18.06 2.38782 16.7499C2.0992 15.4512 2 13.8757 2 12C2 10.1243 2.0992 8.54878 2.38782 7.25007C2.67897 5.94002 3.176 4.85116 4.01358 4.01358C4.85116 3.176 5.94002 2.67897 7.25007 2.38782ZM15.7071 9.29289C16.0976 9.68342 16.0976 10.3166 15.7071 10.7071L12.0243 14.3899C11.4586 14.9556 10.5414 14.9556 9.97568 14.3899L11 13.3656L9.97568 14.3899L8.29289 12.7071C7.90237 12.3166 7.90237 11.6834 8.29289 11.2929C8.68342 10.9024 9.31658 10.9024 9.70711 11.2929L11 12.5858L14.2929 9.29289C14.6834 8.90237 15.3166 8.90237 15.7071 9.29289Z" fill="<?php echo isset($slot_item_data['contact_id']) ? '#8BC34A' : '#9e9e9e' ?>"/> </svg></span>
              </td>
            </tr>
            <?php endfor; ?>
          </tbody>
        </table>
      </div>
      <?php } ?>
      <div class="footer-form">
        <div class="buttons">
          <input name="order_id" type="hidden" value="<?php echo $order_id; ?>" />
          <button type="submit" class="pp-button btn-submit"><?php _e('Save Attendees', 'pp') ?></button>
          <!-- <button type="button" class="pp-button btn-next-step btn-not-active"><?php _e('Next Step', 'pp') ?></button> -->
        </div>
      </div>
    </form>

    <div class="pp-popup-add-new-contact">
      <div class="pp-popup-add-new-contact__inner">
        <form action="" class="pp-form add-new-contact-form">
          <h4><?php _e('Add Contact Information', 'pp') ?></h4>
          <div class="pp-notification"><?php _e('Currently, there is no information about this email account, please create a new contact information first.', 'pp') ?></div>
          <label>
            <span class="__label"><?php _e('Email', 'pp') ?></span>
            <input type="email" name="c_email" readonly required>
          </label>
          <label>
            <span class="__label"><?php _e('First Name', 'pp') ?></span>
            <input type="text" name="f_name" required>
          </label>
          <label>
            <span class="__label"><?php _e('Last Name', 'pp') ?></span>
            <input type="text" name="l_name" required>
          </label>
          <label>
            <span class="__label"><?php _e('Organisation', 'pp') ?></span>
            <input type="text" name="organisation" value="<?php echo $account_name; ?>" readonly required>
          </label>
          <div class="buttons">
            <button class="pp-button" type="submit"><?php _e('Add Contact', 'pp') ?></button>
            <button class="pp-button btn-close" type="button"><?php _e('Close', 'pp') ?></button>
          </div>
        </form>
      </div>
    </div>
  </div>  
</div> <!-- .add-attendees-to-order -->