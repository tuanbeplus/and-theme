<?php 
/**
 * Template tags 
 */

function pp_product_search_filter_tag($active_slug = '') {
  $product_cats = pp_get_product_cats();
  ?>
  <div class="product-seach-filter-tag">
    <h4><?php _e('Search and Filter', 'pp') ?></h4>
    <form action="" method="POST" class="product-card-filter-form">
      <div>
        <label for="PRODUCT_SEARCH" style="display: none;">Keywords</label>
        <input 
          id="PRODUCT_SEARCH"
          tabindex="0" 
          type="text" 
          name="product_search" 
          autocomplete="on" 
          placeholder="<?php _e('Enter a search term...', 'pp') ?>">
      </div>
      <div>
        <label for="PRODUCT_CAT" style="display: none;">Select category</label>
        <select id="PRODUCT_CAT" name="product_cat" tabindex="0">
          <option value=""><?php _e('Please select...', 'pp') ?></option>
          <?php foreach($product_cats as $_index => $cat) {
            $selected = $active_slug == $cat->slug ? 'selected' : '';
            echo "<option value=\"$cat->term_id\" $selected>$cat->name ($cat->count)</option>";
          } ?>
        </select>
      </div>
      <div class="form-end">
        <button class="btn-submit" type="submit"><?php _e('Search', 'pp') ?></button>
      </div>
    </form>
  </div> <!-- .product-seach-filter-tag -->
  <?php 
}

function pp_product_search_filter_v2_tag() {
  pp_product_search_filter_tag();
}

function pp_get_products_tag($products_query) {
  ?><div class="product-card" >
    <ul class="products product-card-style">
      <?php pp_get_products_loop_tag($products_query); ?>
    </ul>
  </div><?php
}

function pp_get_products_loop_tag($products_query) {
  // global $post;
  // $old_post = $post;

  if ( $products_query->have_posts() ) :
    while ( $products_query->have_posts() ) : $products_query->the_post();
      get_template_part( '/woocommerce/loop/product-item' );
    endwhile;
    wp_reset_query();
  endif;
}

function pp_faq_tags($data) {
  $randID = "key__" . rand(9,99999);
  ?>
  <div id="PP_FAQS" class="pp-sidebar-target-scroll"></div>
  <div id="<?php echo $randID; ?>" class="faqs-content">
    <h2><?php _e('Frequently Asked Questions', 'pp') ?></h2>
    <div class="faqs-nav"></div>
    <div class="faqs-block faq-content-of-table-target">
      <?php foreach($data as $_index => $item) : 
        $classes = [ 'faqs-block__item', '__faq-index__' . ($_index + 1) ]
      ?>
      <div class="<?php echo implode(' ', $classes); ?>">
        <div class="faqs-block__item-heading">
          <h3><?php echo sprintf('%s. %s', $_index + 1, $item['question']); ?></h3>
        </div>
        <div class="faqs-block__item-body"> 
          <?php echo wpautop($item['answer']); ?>
        </div>
        <a href="#<?php echo $randID; ?>" class="faqs-block__item-btt">
          <?php _e('Back to top', 'pp') ?>
          <span class="__icon-btt">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"> <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3C12.2652 3 12.5196 3.10536 12.7071 3.29289L19.7071 10.2929C20.0976 10.6834 20.0976 11.3166 19.7071 11.7071C19.3166 12.0976 18.6834 12.0976 18.2929 11.7071L13 6.41421V20C13 20.5523 12.5523 21 12 21C11.4477 21 11 20.5523 11 20V6.41421L5.70711 11.7071C5.31658 12.0976 4.68342 12.0976 4.29289 11.7071C3.90237 11.3166 3.90237 10.6834 4.29289 10.2929L11.2929 3.29289C11.4804 3.10536 11.7348 3 12 3Z" fill="#663077"/> </svg>
          </span>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php
}

function pp_shoplanding_sidebar_tag() {
  pp_load_template('shoplanding-sidebar');
}

function pp_shoplanding_widget_on_this_page_tag() {
  global $post;
  $term = pp_get_product_cats();
  ?>
  <div class="pp-widget pp-widget__on-this-page">
    <div class="pp-widget__inner">
      <div class="pp-widget__title">
        <h4><?php _e('On this page', 'pp') ?></h4>
      </div>
      <ul class="pp-nav">
        <?php if($term && count($term) > 0) : 
          foreach($term as $_index => $t) :
            // $termLink =  get_the_permalink($post) . '?product-term=' . $t->slug;
            $termLink = '#TERM_' . $t->slug;
            ?>
            <li class="pp-nav__item __term">
              <a href="<?php echo $termLink ?>">
                <span class="pp-nav__item-icon">
                  <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> <g> <polyline data-name="Right" fill="none" points="16.4 7 21.5 12 16.4 17" stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /> <line fill="none" stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="2.5" x2="19.2" y1="12" y2="12" /> </g> </svg>
                </span>
                <?php echo $t->name; ?>
              </a>
            </li>
            <?php
          endforeach;
        endif; ?>
        <li class="pp-nav__item __faqs">
          <a href="#PP_FAQS">
            <span class="pp-nav__item-icon">
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> <g> <polyline data-name="Right" fill="none" points="16.4 7 21.5 12 16.4 17" stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" /> <line fill="none" stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="2.5" x2="19.2" y1="12" y2="12" /> </g> </svg>
            </span>
            <?php _e('FAQs', 'pp') ?>
          </a>
        </li>
        <?php  if ( class_exists( 'WooCommerce' ) ) { 
          $cart_items = WC()->cart->get_cart_contents_count();
        ?>
        <li class="pp-nav__item __cart">
          <a href="<?php echo wc_get_cart_url(); ?>" class="__open-pp-offcanvas">
            <span class="pp-nav__item-icon">
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"> <circle cx="7.5" cy="18.5" r="1.5" fill="#000000"/> <circle cx="16.5" cy="18.5" r="1.5" fill="#000000"/> <path stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l.6 3m0 0L7 15h10l2-7H5.6z"/> </svg>
            </span>
            <?php _e('View cart', 'pp') ?>
            <div class="quantity-cart"><?php echo $cart_items; ?></div>
          </a>
        </li>
        <?php } ?>
        
      </ul>
    </div>
  </div>
  <?php
}

function pp_offcanvas_tag() {
  pp_load_template('offcanvas');
}

function pp_woo_mini_cart_tag() {
  if ( ! class_exists( 'WooCommerce' ) ) { 
    return;
  }
  ?>
  <div class="pp-minicart">
    <div class="pp-minicart__inner">
      <h4><?php _e('Your shopping cart', 'pp') ?></h4>
      <div class="pp-minicart__entry">
        <?php woocommerce_mini_cart(); ?>
      </div>
    </div>
  </div>
  <?php
}

function pp_product_first_term_name_tag($product, $template = '%s') {
  try {
    $type = $product->get_type();
    if( in_array($type, ['variation']) ){
      $product = wc_get_product($product->get_parent_id());
    }
    
    $terms = get_the_terms( $product->get_id(), 'product_cat' );
    if(!$terms || count($terms) == 0) return;

    echo sprintf($template, $terms[0]->name);
  } catch (\Exception $e) {

  }
}

function pp_cart_item_qtt_update_tag($product, $cart_item, $cart_item_key) {
  $max_qtt = '';
  if($product->is_type('variation')) {
    $variation_o = new WC_Product_Variation($product->get_id());
    $max_qtt = $product->get_stock_quantity();
  }
  $title = ($max_qtt ? 'title="'. sprintf('Maximum stock quantity %s', $max_qtt) .'"' : '');
  $__increase_class_disable = ($max_qtt && ($cart_item['quantity'] >= $max_qtt)) ? '__disable' : '';
  $__decrease_class_disable = ($cart_item['quantity'] <= 1) ? '__disable' : '';
  ?>
  <div class="pp-product-qtt-update-ui" data-cart-item-key="<?php echo $cart_item_key ?>">
    <label class="__label" title="<?php _e('Quantity', 'pp') ?>"><?php _e('Seats', 'pp') ?></label>
    <button class="pp-button __decrease <?php echo $__decrease_class_disable; ?>" title="<?php _e('Decrease', 'pp') ?>">-</button>
    <input 
      <?php echo $title; ?> 
      type="number" 
      value="<?php echo $cart_item['quantity']; ?>" 
      min=0 <?php echo ($max_qtt ? "max='". $max_qtt ."'" : '') ?>> 
    <button class="pp-button __increase <?php echo $__increase_class_disable; ?>" title="<?php _e('Increase', 'pp') ?>">+</button>
  </div>
  <?php 
}

/**
 * 
 */
function pp_product_single_widget_on_this_page_tag($product) {
  $navItems = [
    [
      'label' => __('Description', 'pp'),
      'url' => '#POST_CONTENT'
    ] ];
  
  $content_block = get_field('block_content', $product->get_id());
  if($content_block && count($content_block) > 0) {
    foreach($content_block as $_index => $item) {
      array_push($navItems, [
        'label' => $item['title'],
        'url' => '#PRODUCT_BLOCK_CONTENT_' . $_index,
      ]);
    }
  }

  $navItems = apply_filters( 'pp/product_single_widget_on_this_page_nav', $navItems );
  ?>
  <div class="pp-widget pp-widget__on-this-page pp-widget__on-this-page-single-product">
    <div class="pp-widget__inner">
      <div class="pp-widget__title">
        <h4><?php _e('On this page', 'pp') ?></h4>
      </div>
      <ul class="pp-nav">
        <?php foreach($navItems as $_index => $item) : ?>
        <li class="pp-nav__item __term">
          <a href="<?php echo $item['url']; ?>">
            <span class="pp-nav__item-icon">
              <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> <g> <polyline data-name="Right" fill="none" points="16.4 7 21.5 12 16.4 17" stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></polyline> <line fill="none" stroke="black" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="2.5" x2="19.2" y1="12" y2="12"></line> </g> </svg>
            </span>
            <?php echo $item['label']; ?>
          </a>
        </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php
}

function pp_product_single_widget_buy_tag($product) {
  $buyItems = apply_filters( 'pp/product_single_buy_items', [
    'product_price' => [
      'label' => __('Price:', 'pp'),
      'value' => $product->get_price_html(),
    ]
  ], $product );
  ?>
  <div class="pp-widget pp-widget__buy">
    <div class="pp-widget__inner">
      <div class="pp-widget__title">
        <h4><?php _e('Purchase', 'pp') ?></h4>
      </div>
      <div class="pp-wg-wrap-content">
        <table class="pp-wg-table">
          <tbody>
            <?php foreach($buyItems as $_index => $item) : ?>
            <tr class="<?php echo $_index; ?>">
              <th class="__label"><?php echo $item['label'] ?></th>
              <td class="__value"><?php echo $item['value'] ? $item['value'] : '—' ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php do_action( 'pp/single_product_widget_by_end', $product ); ?>
    </div>
  </div>
  <?php
}

function pp_product_button_add_to_cart_tag($product) {
  ?>
  <div class="pp-product-single-add-to-cart __type-<?php echo $product->get_type(); ?>">
  <?php
  if( $product->is_type( 'variable' ) ){
    echo '<button 
      type="button" 
      data-text-init="' . __('Choose Slots', 'pp') . '"
      class="pp-button pp-button-choose-slots pp-button-variation-add-to-cart">'
        . __('Choose Slots', 'pp') .
      '</button>';
  } else if( $product->is_type( 'simple' ) ) {
    echo do_shortcode('[add_to_cart id="'. get_the_ID() .'"]');
  } else if( $product->is_type( 'calendly' ) ) {
    echo '<a 
    href="" 
    onclick="Calendly.initPopupWidget({url: \''. get_post_meta( get_the_ID(), '_calendly_booking_url', true ) .'\'});return false;" 
    data-text-init="' . __('Book Slot', 'pp') . '"
    data-pid="'. get_the_ID() .'" 
    class="pp-button pp-button-book-slot">'
      . __('Book Slot', 'pp') .
    '</a>';
  }
  ?>
  </div>
  <?php
}

function pp_date_format($date_str = '', $format = "l, j F Y") {
  if(empty($date_str)) return;
  $date = date_create( $date_str );
  return date_format($date, $format);
}

function pp_product_variable_old_event_toggle_html() {
  ?>
  <label class="__old-event-toggle">
    <input type="checkbox" id="VARIABLE_OLD_EVENT_TOGGLE_INPUT">
    <div class="__toggle-fake">
      <span class="__toggle-fake-dot"></span>
    </div>
    <div class="__toggle-label"><?php _e('Show Old Events', 'pp') ?></div>
  </label>
  <?php 
}

function pp_product_variable_choose_options_tag($product) {
  if( !$product->is_type( 'variable' ) ){
    return;
  }
  
  $variations = $product->get_available_variations();
  if(!$variations || count($variations) <= 0) return;
  ?>
  <div id="PRODUCT_BLOCK_CONTENT_CHOOSE_OPTIONS" class="pp__block-content product-variable-options">
    <h4 class="pp__block-content-title">
      <?php _e('Training courses available', 'pp'); ?>
      <?php pp_product_variable_old_event_toggle_html(); ?>
    </h4>
    <div class="pp-content pp__block-content-entry">
      <form 
        class="pp-form-product-variations" 
        type="POST" 
        data-product-id="<?php echo esc_attr($product->get_id()); ?>" 
        data-variations='<?php echo esc_attr(json_encode($variations)); ?>'>
        <div class="options">
          <?php 
            $allEventData = array();
            foreach($variations as $_index => $item) {
              $product_variation = new WC_Product_Variation($item['variation_id']);

              // echo $product_variation->get_price_html();
              $eventData = ppwc_get_event_data_by_product_variation_id($item['variation_id']);
              $eventData['price_html']    = $item['price_html'];
              $eventData['is_in_stock']   = $item['is_in_stock'];
              $eventData['attributes']    = $item['attributes'];
              $eventData['variation_id']  = $item['variation_id']; 
              $allEventData[] = $eventData;
            }
            
            $eventDataAfterSort = groupAndSortEventsByMonth($allEventData, 'ASC');
            // echo count($eventDataAfterSort);
            $upcomingEvents = array();
          ?>
          <?php foreach ($eventDataAfterSort as $monthName => $monthBucket): ?>
            <?php if (!empty($monthBucket)): ?>
              <div class="month-bucket">
                <span class="month__name"><?php echo $monthName ?? ''; ?></span>
                <?php foreach ($monthBucket as $event): 
                    $upcomingEvents[] = $event;
                    // echo '<pre>'; print_r($event); echo '</pre>';
                    $old_class = ((isset($event['__OLD_EVENT']) && $event['__OLD_EVENT']) == 1 ? '__old-event __disable' : '');
                    $event_name = '';
                    $e_parent_name = $event['event_parent']['post_title'] ?? '';
                    $e_child_name = $event['event_child']['post_title'] ?? '';
                    $event_name = ppwc_merge_event_names($e_parent_name, $e_child_name);
                  ?>
                  <div class="<?php echo $old_class; ?> option-block product-variable-item product-variable-item__id-<?php echo $event['variation_id'] ?> <?php echo $event['is_in_stock'] ? '' : '__disable'; ?>">
                    <?php if(!$event['is_in_stock']) {
                      echo '<span class="pp__out-of-stock">'. __('Out of stock', 'pp') .'</span>';
                    } ?>
                    <label class="product-variation-item-label" <?php echo (!$event['is_in_stock']) ? '' : 'tabindex="0"'; ?>>
                      <input name="product_variation[]" type="checkbox" style="display: none;" value="<?php echo $event['variation_id']; ?>">
                      <h4>
                        <strong style="margin-right:10px;"><?php echo $event_name; ?></strong>
                        <?php echo ! empty($event['price_html']) ? "<span class=\"pp-amount\">{$event['price_html']}</span>" : '' ?>
                        <?php echo pp_woo_remaining_seats_available($event); ?>
                      </h4>
                      <div class="schedule-course">
                        <div class="time-box schedule-course_start <?php echo (empty($eventData['event_child']) ? '__full' : '') ?>">
                          <div class="pp__checkbox-fake-ui">
                            <span class="pp__checkbox-fake-ui-box"></span>
                          </div>
                          <div>
                            <div class="__date"><?php echo $event['event_parent']['workshop_event_date_text__c'] ?></div>
                            <div class="__time"><?php echo $event['event_parent']['workshop_times__c'] ?></div>
                          </div>
                        </div>
                        <?php if(!empty($event['event_child'])) : ?>
                        <span class="__splicing">+</span>
                        <div class="time-box schedule-course_end">
                          <div class="__date"><?php echo $event['event_child']['workshop_event_date_text__c'] ?></div>
                          <div class="__time"><?php echo $event['event_child']['workshop_times__c'] ?></div>
                        </div>
                        <?php endif; ?>
                      </div>
                    </label>
                  </div>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>
        <?php if (!empty($upcomingEvents)): ?>
          <button 
            style="display:none;"
            type="submit" 
            class="pp-button pp-button-submit pp-button-variantion-submit"
            data-template-has-item="<?php _e('Add %CHECKED_NUMBER% item to basket', 'pp') ?>" 
            data-template-has-items="<?php _e('Add %CHECKED_NUMBER% items to basket', 'pp') ?>" 
            data-template-no-item="<?php _e('Choose Slots', 'pp') ?>" ><?php _e('Choose Slots', 'pp') ?>
          </button>
        <?php else: ?>
          <h4 class="no-events-message">There are no upcoming events.</h4>
        <?php endif; ?>
      </form>
    </div>
  </div>
  <?php 
}

function groupAndSortEventsByMonth($eventsArray, $sortDirection = 'ASC') {
  $groupedAndSortedArrays = array();

  // List all names of months in the year
  for ($month = 1; $month <= 12; $month++) {
    $monthName = date("F", mktime(0, 0, 0, $month, 1, date("Y")));
    $groupedAndSortedArrays[$monthName] = array();
  }

  // Sorting events by date and time
  $eventsArray = array_filter($eventsArray, function($v, $k) {
    return !empty($v['event_parent']['startdatetime']) ? true : false;
  }, ARRAY_FILTER_USE_BOTH);

  usort($eventsArray, function($a, $b) use ($sortDirection) {
      if(!isset($a['event_parent']['startdatetime']) || !isset($b['event_parent']['startdatetime'])) return;

      $dateA = strtotime($a['event_parent']['startdatetime']);
      $dateB = strtotime($b['event_parent']['startdatetime']);

      return ($sortDirection === 'ASC') ? ($dateA - $dateB) : ($dateB - $dateA);
  });
  
  // Group events by month
  foreach ($eventsArray as $event) {
    $dateTimeString = $event['event_parent']['startdatetime'];

    // Create a DateTime object from the given string
    $dateTimeUtc = new DateTime($dateTimeString, new DateTimeZone('UTC'));

    // get option WP time zone string
    $wpTimeZone = get_option('timezone_string');

    // Set the time zone to WP time zone
    $dateTimeUtc->setTimezone(new DateTimeZone($wpTimeZone));

    // Format the converted date and time
    $convertedDateTime = strtotime($dateTimeUtc->format('Y-m-d H:i:s P'));

    $monthKey = date('F', $convertedDateTime);
    
    // Just Add future Events
    if ($convertedDateTime > strtotime('now')) {
      // Add the child array to the month key
      if (isset($monthKey)) {
        // $groupedAndSortedArrays[$monthKey][] = $event;
      }
    } else {
      $event['__OLD_EVENT'] = true;
    }

    $groupedAndSortedArrays[$monthKey][] = $event;
  }
  return $groupedAndSortedArrays;
}

function pp_product_list_item_info_tag($product) {
  $items = [];
  $inclusivity = get_field('inclusivity', $product->get_id());
  $minutes = get_field('minutes', $product->get_id());
  $member_only = get_field('member_only', $product->get_id());

  if($inclusivity) {
    array_push($items, 'Inclusivity');
  }

  if($minutes) {
    array_push($items, $minutes . 'm');
  }

  if($member_only) {
    array_push($items, 'Member only');
  }

  if(count($items) == 0) return;
  ?>
  <ul class="item-list-info">
    <?php foreach($items as $text) : ?>
      <li><?php echo $text; ?></li>
    <?php endforeach; ?>
  </ul>
  <?php
}

function pp_button_back_to_shoplanding_tag() {
  $shopLanding = get_field('shop_landing_page', 'option');
  ?>
  <a class="pp-button pp-btn-back-to-shoplanding" href="<?php echo $shopLanding; ?>">
    <span class="__arrow-icon">
      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"> <g > <g > <g> <polyline data-name="Right" fill="none" points="7.6 7 2.5 12 7.6 17" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/> <line fill="none" stroke="#000000" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" x1="21.5" x2="4.8" y1="12" y2="12"/> </g> </g> </g> </svg>
    </span>
    <?php _e('Our Learning Solutions ', 'pp'); ?>
  </a>
  <?php 
}