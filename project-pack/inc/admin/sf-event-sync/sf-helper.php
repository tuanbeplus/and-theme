<?php
function sf_log_data( $data ) {

  $file = SF_DIR . 'log.txt';

  $current = file_get_contents($file);

  $datetime = date('Y-m-d H:i:s');

  $current .= "\nDate Time: {$datetime}\n Data: {$data} \n";

  file_put_contents($file, $current);
}
