
<?php

if( get_row_layout() == 'dashboard_advertisement' ):

    $upcomingEvents = getEvents('dashboard');
    $ProfileId = getUser($_COOKIE['userId'])->records[0]->ProfileId;
    ?>
          <section class="dashboard advertisement <?php if($ProfileId == NONE_MEMBER) echo 'non-member'; ?>">
              <div class="container">
                  <?php dynamic_sidebar( 'dashboard_advertisement' ); ?>
              </div>
          </section>
<?php
endif;
?>
