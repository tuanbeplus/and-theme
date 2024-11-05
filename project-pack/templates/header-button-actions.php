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
  <a href="<?php echo wc_get_cart_url(); ?>"
      role="button"
      aria-label="View your Cart"
      class="btn-text change pp-buton-action __mini-cart-button __open-pp-offcanvas">
    <span class="__btn-icon">
      <svg width="24.0006272px" height="18.0000147px" viewBox="0 0 24.0006272 18.0000147" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
          <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
              <g id="AND-Homepage-Revamp-R1.1" transform="translate(-987.5, -33.5)" stroke="#FFFFFF" stroke-width="3">
                  <g id="Main-Nav" transform="translate(422, 22)">
                      <g id="View-cart" transform="translate(550, 0)">
                          <path d="M17,13.0006832 L19.5200839,13.0006832 C20.045424,12.9823108 20.513806,13.3361857 20.6501664,13.8544905 L23.8935031,26.2923846 C24.1556988,27.287081 25.0318634,27.9838869 26.0406598,28 L33.4200983,28 C34.3947742,28.0009028 35.2602323,27.3637685 35.567255,26.4193019 L37.9404282,19.1388648 C38.0553433,18.7919492 38.0021257,18.4099353 37.7970281,18.1094904 C37.5919304,17.8090456 37.2593111,17.625852 36.9007523,17.6158573 L21.6333381,17.6158573" id="Path"></path>
                      </g>
                  </g>
              </g>
          </g>
      </svg>
    </span>
    <span><?php _e('View cart', 'pp') ?></span>
    <div class="quantity-cart"><?php echo $cart_items; ?></div>
  </a>
  <?php } ?>

  <?php if(is_user_logged_in()) { // /wp-login.php?action=logout?>
    <a id="logout"
        role="button"
        aria-label="Member Logout"
        href="<?php echo wp_logout_url(); ?>"
        class="btn-text change pp-buton-action">
      <span class="__btn-icon">
        <svg fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.001.907A8.336 8.336 0 00.668 9.241c0 4.6 3.733 8.333 8.333 8.333s8.334-3.734 8.334-8.334S13.6.908 9 .908zM4.893 14.474c.358-.75 2.542-1.483 4.108-1.483 1.567 0 3.759.733 4.109 1.483A6.577 6.577 0 019 15.907a6.577 6.577 0 01-4.108-1.433zm9.408-1.209c-1.191-1.45-4.083-1.941-5.3-1.941-1.216 0-4.108.492-5.3 1.941a6.625 6.625 0 01-1.366-4.024A6.676 6.676 0 019 2.574a6.676 6.676 0 016.667 6.667 6.625 6.625 0 01-1.367 4.024zm-5.3-9.024a2.91 2.91 0 00-2.916 2.916A2.91 2.91 0 009 10.074a2.91 2.91 0 002.917-2.917A2.91 2.91 0 009.001 4.24zm0 4.166c-.691 0-1.25-.558-1.25-1.25s.559-1.25 1.25-1.25c.692 0 1.25.558 1.25 1.25s-.558 1.25-1.25 1.25z" fill="#FAF9F9"/></svg>
      </span>
      <span><?php _e('Logout', 'pp') ?></span>
    </a>
    <a id="dashboard"
        role="button"
        aria-label="Member Dashboard"
        href="/dashboard"
        class="btn-text change pp-buton-action">
      <span><?php _e('Dashboard', 'pp') ?></span>
    </a>
  <?php } else { ?>
    <a id="login"
        role="button"
        aria-label="Member Login"
        href="/login"
        class="btn-text change pp-buton-action">
      <span class="__btn-icon">
        <svg fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.001.907A8.336 8.336 0 00.668 9.241c0 4.6 3.733 8.333 8.333 8.333s8.334-3.734 8.334-8.334S13.6.908 9 .908zM4.893 14.474c.358-.75 2.542-1.483 4.108-1.483 1.567 0 3.759.733 4.109 1.483A6.577 6.577 0 019 15.907a6.577 6.577 0 01-4.108-1.433zm9.408-1.209c-1.191-1.45-4.083-1.941-5.3-1.941-1.216 0-4.108.492-5.3 1.941a6.625 6.625 0 01-1.366-4.024A6.676 6.676 0 019 2.574a6.676 6.676 0 016.667 6.667 6.625 6.625 0 01-1.367 4.024zm-5.3-9.024a2.91 2.91 0 00-2.916 2.916A2.91 2.91 0 009 10.074a2.91 2.91 0 002.917-2.917A2.91 2.91 0 009.001 4.24zm0 4.166c-.691 0-1.25-.558-1.25-1.25s.559-1.25 1.25-1.25c.692 0 1.25.558 1.25 1.25s-.558 1.25-1.25 1.25z" fill="#FAF9F9"/></svg>
      </span>
      <span><?php _e('Login', 'pp') ?></span>
    </a>
  <?php } ?>

</div>
