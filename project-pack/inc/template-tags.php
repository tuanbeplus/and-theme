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
          <h4><?php echo sprintf('%s. %s', $_index + 1, $item['question']); ?></h4>
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
  ?>
  <div class="pp-product-qtt-update-ui" data-cart-item-key="<?php echo $cart_item_key ?>">
    <label class="__label" title="<?php _e('Quantity', 'pp') ?>"><?php _e('Seats', 'pp') ?></label>
    <button class="pp-button __decrease" title="<?php _e('Decrease', 'pp') ?>">-</button>
    <input type="number" value="<?php echo $cart_item['quantity']; ?>" min=0> 
    <button class="pp-button __increase" title="<?php _e('Increase', 'pp') ?>">+</button>
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
        <h4><?php _e('Buy', 'pp') ?></h4>
      </div>
      <div class="pp-wg-wrap-content">
        <table class="pp-wg-table">
          <tbody>
            <?php foreach($buyItems as $_index => $item) : ?>
            <tr class="<?php echo $_index; ?>">
              <th class="__label"><?php echo $item['label'] ?></th>
              <td class="__value"><?php echo $item['value'] ?></td>
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

function pp_product_variable_choose_options_tag($product) {
  if( !$product->is_type( 'variable' ) ){
    return;
  }
  
  $variations = $product->get_available_variations();
  if(!$variations || count($variations) <= 0) return;
  ?>
  <div id="PRODUCT_BLOCK_CONTENT_CHOOSE_OPTIONS" class="pp__block-content product-variable-options">
    <h4 class="pp__block-content-title"><?php _e('Training courses available', 'pp'); ?></h4>
    <div class="pp-content pp__block-content-entry">
      <form 
        class="pp-form-product-variations" 
        type="POST" 
        data-product-id="<?php echo $product->get_id(); ?>" 
        data-variations='<?php echo json_encode($variations); ?>'>
        <div class="options">
          <?php foreach($variations as $_index => $item) : 
          $_price_html = $item['price_html'];
          $_in_stock = $item['is_in_stock'];
          ?>
          <div class="option-block product-variable-item <?php echo $_in_stock ? '' : '__disable'; ?>">
            <?php if(!$_in_stock) {
              echo '<span class="pp__out-of-stock">'. __('Out of stock', 'pp') .'</span>';
            } ?>
            <label class="product-variation-item-label" <?php echo (!$_in_stock) ? '' : 'tabindex="0"'; ?>>
              <input name="product_variation[]" type="checkbox" style="display: none;" value="<?php echo $item['variation_id']; ?>">
              <h4>
                <?php echo implode(' — ', $item['attributes']); ?> <?php echo ! empty($_price_html) ? "<span class=\"pp-amount\">$_price_html</span>" : '' ?>
              </h4>
              <div class="schedule-course">
                <div class="time-box schedule-course_start">
                  <div class="pp__checkbox-fake-ui">
                    <span class="pp__checkbox-fake-ui-box"></span>
                  </div>
                  <div>
                    <div class="__date"><?php echo pp_date_format($item['start_date']) ?></div>
                    <div class="__time"><?php echo $item['start_time'] ?></div>
                  </div>
                </div>
                <span class="__splicing">+</span>
                <div class="time-box schedule-course_end">
                  <div class="__date"><?php echo pp_date_format($item['end_date']) ?></div>
                  <div class="__time"><?php echo $item['end_time'] ?></div>
                </div>
              </div>
            </label>
          </div>
          <?php endforeach; ?>
        </div>
        <button 
          type="submit" 
          class="pp-button pp-button-submit pp-button-variantion-submit"
          data-template-has-item="<?php _e('Add %CHECKED_NUMBER% item to basket', 'pp') ?>" 
          data-template-has-items="<?php _e('Add %CHECKED_NUMBER% items to basket', 'pp') ?>" 
          data-template-no-item="<?php _e('Choose Slots', 'pp') ?>" ><?php _e('Choose Slots', 'pp') ?></button>
      </form>
    </div>
  </div>
  <?php 
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