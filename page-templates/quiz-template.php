<?php
/*
* Template Name: Quiz Template
*/
get_header();

$userId = 'admin-and';

global $wpdb;
$table_name = $wpdb->prefix. "and_data_entries_gform";
// $wpdb->delete( $table_name, [ 'username' => 'admin-and' ], [ '%s' ] );

$lastArr = $wpdb->get_results( "SELECT * FROM $table_name WHERE username = 'admin-and' ORDER BY date DESC LIMIT 1", ARRAY_A );
?>

<div class="wrapper-quiz">
  <div class="container">
    <div class="wrap-quiz">
      <input id="userId" type="hidden" name="" value="admin-and">
      <input id="complatedPage" type="hidden" name="" value="">
      <div class="_top">
        <h1 class="_title"><?php echo the_title();?></h1>

        <div class="__right">
          <a href="#" class="save_progress">Save Progress</a>
          <span>Your progress has been saved</span>
        </div>
      </div>
      <?php if ( !empty($lastArr) ): ?>
        <?php
        $field_values = '';
        foreach (unserialize($lastArr[0]['data']) as $key => $value) {
            if ( $key > 0 ) $field_values .= '&';

            $field_values .= $value[1].'='.$value[2];
        }
        ?>
        <?php echo do_shortcode("[gravityform id='9' title='false' description='false' ajax='true' field_values='{$field_values}']"); ?>
      <?php else : ?>
        <?php echo do_shortcode('[gravityform id="9" title="false" description="false" ajax="true"]'); ?>
      <?php endif; ?>
    </div>

    <div class="on-this-page sidebar">
        <div class="inner">
            <h2>In this section</h2>
            <ul>
              <li>
                <a href="/quiz/" id="1" class="circle dark-red">
                  <span class="material-icons">arrow_forward</span>
                  <span class="text">Dashboard</span>
                </a>
              </li>
            </ul>
        </div>
    </div>

  </div>
</div>

<?php
get_footer();
?>
