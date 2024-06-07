
<?php

if( get_row_layout() == 'dashboard_advertisement' ):
    global $contact_id, $account_id, $user_profile;
    $upcomingEvents = getEvents('dashboard');
    ?>
          <section class="dashboard advertisement <?php if($user_profile == 'NON_MEMBERS') echo 'non-member'; ?>">
              <div class="container">
                  <?php dynamic_sidebar( 'dashboard_advertisement' ); ?>
              </div>
          </section>
<?php
endif;
?>
