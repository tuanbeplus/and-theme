<?php
/**
 * Off Camvas
 */

?>
<div class="pp-offcanvas">
  <div class="pp-offcanvas__container">
    <button class="pp-btn pp-btn__close">
      <?php _e('Close', 'pp') ?> 
      <span class="pp-btn__close-icon">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.207 6.207a1 1 0 0 0-1.414-1.414L12 10.586 6.207 4.793a1 1 0 0 0-1.414 1.414L10.586 12l-5.793 5.793a1 1 0 1 0 1.414 1.414L12 13.414l5.793 5.793a1 1 0 0 0 1.414-1.414L13.414 12l5.793-5.793z"/></svg>
      </span>
    </button>
    <div class="pp-offcanvas__widget">
      <?php do_action( 'pp/offcanvas-item' ); ?>
    </div>
  </div>
</div> <!-- .pp-offcanvas -->