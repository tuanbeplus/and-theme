<?php
/**
 * Header buttons
 * 
 * @version 1.0.0
 * @since 1.0.0
 */

?>
<div class="buttons header-button-actions">
  <?php  if ( class_exists( 'WooCommerce' ) ) { 
    $cart_items = WC()->cart->get_cart_contents_count();
  ?>
  <a href="<?php echo wc_get_cart_url(); ?>" class="btn-text change pp-buton-action __mini-cart-button __open-pp-offcanvas">
    <span class="__btn-icon">
      <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="none"> <circle cx="7.5" cy="18.5" r="1.5" fill="#A22F2C"></circle> <circle cx="16.5" cy="18.5" r="1.5" fill="#A22F2C"></circle> <path stroke="#A22F2C" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l.6 3m0 0L7 15h10l2-7H5.6z"></path> </svg>
    </span>
    <span><?php _e('View cart', 'pp') ?></span>
    <div class="quantity-cart"><?php echo $cart_items; ?></div>
  </a>
  <?php } ?>
  
  <?php if(is_user_logged_in()) { ?>
  <a id="logout" href="<?php echo wp_logout_url(home_url()) ?>" class="btn-text change pp-buton-action">
    <span class="__btn-icon">
      <svg fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.001.907A8.336 8.336 0 00.668 9.241c0 4.6 3.733 8.333 8.333 8.333s8.334-3.734 8.334-8.334S13.6.908 9 .908zM4.893 14.474c.358-.75 2.542-1.483 4.108-1.483 1.567 0 3.759.733 4.109 1.483A6.577 6.577 0 019 15.907a6.577 6.577 0 01-4.108-1.433zm9.408-1.209c-1.191-1.45-4.083-1.941-5.3-1.941-1.216 0-4.108.492-5.3 1.941a6.625 6.625 0 01-1.366-4.024A6.676 6.676 0 019 2.574a6.676 6.676 0 016.667 6.667 6.625 6.625 0 01-1.367 4.024zm-5.3-9.024a2.91 2.91 0 00-2.916 2.916A2.91 2.91 0 009 10.074a2.91 2.91 0 002.917-2.917A2.91 2.91 0 009.001 4.24zm0 4.166c-.691 0-1.25-.558-1.25-1.25s.559-1.25 1.25-1.25c.692 0 1.25.558 1.25 1.25s-.558 1.25-1.25 1.25z" fill="#FAF9F9"/></svg>
    </span>
    <span><?php _e('Logout', 'pp') ?></span>
  </a>
  <a id="dashboard" href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>" class="btn-text change pp-buton-action">
    <span><?php _e('Dashboard', 'pp') ?></span>
  </a>
  <?php } else { ?>
  <a id="login" href="/login" class="btn-text change pp-buton-action">
    <span class="__btn-icon">
      <svg fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.001.907A8.336 8.336 0 00.668 9.241c0 4.6 3.733 8.333 8.333 8.333s8.334-3.734 8.334-8.334S13.6.908 9 .908zM4.893 14.474c.358-.75 2.542-1.483 4.108-1.483 1.567 0 3.759.733 4.109 1.483A6.577 6.577 0 019 15.907a6.577 6.577 0 01-4.108-1.433zm9.408-1.209c-1.191-1.45-4.083-1.941-5.3-1.941-1.216 0-4.108.492-5.3 1.941a6.625 6.625 0 01-1.366-4.024A6.676 6.676 0 019 2.574a6.676 6.676 0 016.667 6.667 6.625 6.625 0 01-1.367 4.024zm-5.3-9.024a2.91 2.91 0 00-2.916 2.916A2.91 2.91 0 009 10.074a2.91 2.91 0 002.917-2.917A2.91 2.91 0 009.001 4.24zm0 4.166c-.691 0-1.25-.558-1.25-1.25s.559-1.25 1.25-1.25c.692 0 1.25.558 1.25 1.25s-.558 1.25-1.25 1.25z" fill="#FAF9F9"/></svg>
    </span>
    <span><?php _e('Login', 'pp') ?></span> 
  </a>
  <?php } ?>
    
</div>