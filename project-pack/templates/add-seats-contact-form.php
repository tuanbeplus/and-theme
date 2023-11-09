<?php 
/**
 * Add contact to cart
 */

$cart = WC()->cart;
$sf_user_metadata = pp_saleforce_current_user_metadata();
$account_id = isset($sf_user_metadata['account_id']) ? $sf_user_metadata['account_id'] : '';
$account_name = isset($sf_user_metadata['salesforce_account']['Name']) ? $sf_user_metadata['salesforce_account']['Name'] : '';
// echo '<pre>'; print_r($sf_user_metadata); echo '</pre>';
?>
<div class="add-attendees-container">
  <form id="ADD_ATTENDEES_FORM" action="" method="POST" class="pp-form">
  <?php 
  foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
    $course_information = $cart_item['course_information'];
    $product_id = $cart_item['product_id'];
    $variation_id = $cart_item['variation_id'];
    $quantity = $cart_item['quantity'];
    if(!isset($course_information) || empty($course_information)) continue;
    ?>
    <div class="__item">
      <h4 class="product-name"><?php echo $course_information['name'] ?></h4>
      <table class="pp-table">
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
          <?php for($i = 1; $i <= $quantity; $i++) : ?>
          <tr class="__slot-item">
            <td>#<?php echo $i; ?></td>
            <td>
              <input name="email[<?php echo $cart_item_key ?>][]" type="email" placeholder="<?php _e('Ex: sara@gmail.com', 'pp') ?>" required />
              <input name="contact_id[<?php echo $cart_item_key ?>][]" type="hidden" />
            </td>
            <td>
              <input name="firstname[<?php echo $cart_item_key ?>][]" type="text" placeholder="<?php _e('Ex: Sara', 'pp') ?>" required />
            </td>
            <td>
              <input name="lastname[<?php echo $cart_item_key ?>][]" type="text" placeholder="<?php _e('Ex: Jones', 'pp') ?>" required />
            </td>
            <td>
              <span class="organisation-text" data-default-text="<?php echo $account_name; ?>"><?php echo $account_name ?></span>
              <input name="organisation[<?php echo $cart_item_key ?>][]" data-default-value="<?php echo $account_id ?>" value="<?php echo $account_id ?>" readonly type="hidden" placeholder="<?php _e('Organisation', 'pp') ?>" />  
            </td>
            <td>
              <span class="__status-icon"><svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M7.25007 2.38782C8.54878 2.0992 10.1243 2 12 2C13.8757 2 15.4512 2.0992 16.7499 2.38782C18.06 2.67897 19.1488 3.176 19.9864 4.01358C20.824 4.85116 21.321 5.94002 21.6122 7.25007C21.9008 8.54878 22 10.1243 22 12C22 13.8757 21.9008 15.4512 21.6122 16.7499C21.321 18.06 20.824 19.1488 19.9864 19.9864C19.1488 20.824 18.06 21.321 16.7499 21.6122C15.4512 21.9008 13.8757 22 12 22C10.1243 22 8.54878 21.9008 7.25007 21.6122C5.94002 21.321 4.85116 20.824 4.01358 19.9864C3.176 19.1488 2.67897 18.06 2.38782 16.7499C2.0992 15.4512 2 13.8757 2 12C2 10.1243 2.0992 8.54878 2.38782 7.25007C2.67897 5.94002 3.176 4.85116 4.01358 4.01358C4.85116 3.176 5.94002 2.67897 7.25007 2.38782ZM15.7071 9.29289C16.0976 9.68342 16.0976 10.3166 15.7071 10.7071L12.0243 14.3899C11.4586 14.9556 10.5414 14.9556 9.97568 14.3899L11 13.3656L9.97568 14.3899L8.29289 12.7071C7.90237 12.3166 7.90237 11.6834 8.29289 11.2929C8.68342 10.9024 9.31658 10.9024 9.70711 11.2929L11 12.5858L14.2929 9.29289C14.6834 8.90237 15.3166 8.90237 15.7071 9.29289Z" fill="#9e9e9e"/> </svg></span>
            </td>
          </tr>
          <?php endfor; ?>
        </tbody>
      </table>
    </div>
    <?php
  }
  ?>
  </form>
</div> <!-- .add-attendees-container -->
