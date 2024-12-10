<?php 
/**
 * Get current WP user's ID by Salesforce Contact ID
 *
 * @param int $sf_user_id   Salesforce Contact ID
 *
 * @return int WP User's ID
 * 
 */
function and_get_wp_user_by_email($email) {
    if (empty($email)) {
        return;
    }
    // Get WP user by email
    $wp_user = get_user_by('email', $email);

    if (!empty($wp_user) && !empty($wp_user->ID)) {
        return $wp_user->ID;
    }
}

/**
 * Function to log ClearXP eLearn data
 * 
 * @param string $message
 * 
 */
function and_clearxp_elearn_log_data( $message ) {
    // Create a new DateTime object in Vietnam timezone (GMT+7)
    $date = new DateTime("now", new DateTimeZone('Asia/Ho_Chi_Minh'));

    // Get the absolute path to the wp-content directory
    // Include the current month and year in the log file name
    $log_file_path = WP_CONTENT_DIR . '/clearxp-webhook-logs/clearxp-elearn-webhook-'. $date->format('m-Y') .'.log';

    // Prepare the log message with the current timestamp
    $log_message  = PHP_EOL;
    $log_message .= "[" . $date->format('d-m-Y H:i:s') . " GMT+7] " . PHP_EOL;
    $log_message .= $message . PHP_EOL;

    // Ensure the logs directory exists
    if (!file_exists(WP_CONTENT_DIR . '/clearxp-webhook-logs')) {
        mkdir(WP_CONTENT_DIR . '/clearxp-webhook-logs', 0755, true);
    }

    // Append the log message to the log file
    file_put_contents($log_file_path, $log_message, FILE_APPEND | LOCK_EX);
}

/**
 * Function to delete a ClearXP eLearn log file by its name
 * 
 * @param string $file_name The name of the log file to delete (e.g., 'clearxp-elearn-webhook-09-2024.log')
 * @return bool Returns true if the file was successfully deleted, false otherwise
 */
function and_clearxp_elearn_delete_log_file( $file_name ) {
    // Build the full file path
    $file_path = WP_CONTENT_DIR . '/clearxp-webhook-logs/' . $file_name;

    // Check if the file exists
    if (file_exists($file_path)) {
        // Try to delete the file
        if (unlink($file_path)) {
            // File successfully deleted
            return true;
        } else {
            // Failed to delete the file
            return false;
        }
    } else {
        // File doesn't exist
        return false;
    }
}

/**
 * Get eLearn single module name by module number
 * 
 * @param int|string $module_no
 * 
 * @return string|null
 * 
 */
function and_get_elearn_single_module_name($module_no) {
    // Ensure module number is valid
    if (!isset($module_no) || empty($module_no)) {
        return;
    }
    // Cast the module number to an integer to prevent issues with string numbers
    $module_no = (int) $module_no;
    // List of module names
    $module_names = array(
        1 => 'About Disability & Accessibility', #1
		2 => 'Disability Confidence is Good Business', #2
		3 => 'Inclusive Communication', #3
		4 => 'Creating Enabling Environments', #4
		5 => 'Inclusive Recruitment', #5
		6 => 'Workplace Adjustments', #6
		7 => 'Disability Confident Conversations', #7
		8 => 'Facilitating Positive Employment', #8
		9 => 'Inclusive Customer Experiences', #9
		10 => 'The Experience Journey', #10
		11 => 'Your Disability Confidence', #11
    );
    return $module_names[$module_no] ?? 'Unknown Module Name';
}

/**
 * Save ClearXP eLearn data to WP user meta
 *
 * @param array $webhook_elearn_data
 */
function and_save_clearxp_elearn_user_meta($webhook_elearn_data) {
    // Validate input data
    if (empty($webhook_elearn_data) || !is_array($webhook_elearn_data)) {
        return;
    }
    $user_email = $webhook_elearn_data['attributes']['Email'] ?? '';
    if (empty($user_email)) {
        return;
    }
    $wp_user_id = and_get_wp_user_by_email($user_email);
    if (!$wp_user_id) {
        return; // Early exit if user ID not found
    }

    // Initialize metadata
    $clearxp_elearns_meta = get_user_meta($wp_user_id, 'clearxp_elearns', true) ?: [];

    // Extract relevant data
    $status = '';
    $elearn_id = $webhook_elearn_data['attributes']['eLearnId'] ?? '';
    $message_id = $webhook_elearn_data['id'] ?? '';
    $completion = $webhook_elearn_data['completion'] ?? '';
    $module_status = $webhook_elearn_data['event'][0] ?? '';
    $module_name = $webhook_elearn_data['definition']['name']['en'] ?? 
                   $webhook_elearn_data['definition']['name']['und'] ?? 
                   'Unknown Module Name';

    // Format module name
    $trimmed_module_name = strtolower(preg_replace(['/[\s]+/', '/[^\w]/'], ['_', ''], trim($module_name)));

    if ($module_status == 'cxp:activity:progress' && $completion != true) {
        $status = 'progress';
    }
    else if ($module_status == 'cxp:activity:completed' && $completion == true) {
        $status = 'completed';
    }
    else {
        $status = 'unknow_status';
    }

    // Prepare metadata
    $clearxp_elearns_meta[$trimmed_module_name]['module_name'] = $module_name;
    $clearxp_elearns_meta[$trimmed_module_name][$status] = array(
        'event'       => $webhook_elearn_data['event'] ?? '',
        'message_id'  => $message_id,
        'completion'  => $completion,
        'timestamp'   => $webhook_elearn_data['timestamp'] ?? '',
        'user_name'   => $webhook_elearn_data['name'] ?? '',
        'elearn_id'   => $elearn_id,
        'module_name' => $module_name,
        'attributes'  => json_encode($webhook_elearn_data['attributes']) ?? [],
    );

    // Save data to WP usermeta and log the result
    $updated_user_meta = update_user_meta($wp_user_id, 'clearxp_elearns', $clearxp_elearns_meta);
    $log_message = $updated_user_meta
        ? '[SUCCESS] ClearXP eLearn user meta has been saved'
        : '[INFOR] Failed to save ClearXP eLearn user meta';

    // Append additional information to log
    $log_message .= sprintf(
        ', Message ID: %s, User name: %s, WP User ID: %s, User email: %s',
        $message_id,
        $webhook_elearn_data['name'] ?? 'N/A',
        $wp_user_id,
        $user_email
    );
    // Log save status
    and_clearxp_elearn_log_data($log_message);
}

/**
 * Send data webhook fake to the endpoint
 */
function and_send_fake_data_webhook_to_enpoint() {

    if (isset($_GET['test']) && $_GET['test'] == 'clearxp_webhook') {
        // Fake data to simulate ClearXP
        $fake_data_json = get_field('clearxp_elean_json_test', 'option');

        // Send the fake data to the webhook URL
        $response = wp_remote_post(home_url('/?action=elearn-change'), array(
            'body'    => $fake_data_json,
            'headers' => array(
                'Content-Type' => 'application/json',
            ),
        ));

        if (is_wp_error($response)) {
            error_log('Error sending fake data: ' . $response->get_error_message());
        } else {
            error_log('Fake data sent successfully: ' . print_r($response, true));
        }
    }
}
add_action( 'init', 'and_send_fake_data_webhook_to_enpoint', 10 );

/**
 * Get eLearn data by Webhook from ClearXP
 */
function and_get_elearn_data_from_clearxp() {
    // Handle event the eLearn change
    if (isset($_GET['action']) && $_GET['action'] == 'elearn-change') {

        // wp_remote_post('https://b57f0fe326fa821a9201366b7e63333c.m.pipedream.net', [
        //     'body' => file_get_contents('php://input'),
        // ]);

        // Fetch the raw JSON data from the request body
        $json = file_get_contents('php://input');
        if (empty($json)) return;

        // Log eLearn data
        and_clearxp_elearn_log_data($json);
    
        // Convert JSON to PHP associative array
        $object_data = json_decode($json, true);

        // Check if JSON decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            return;
        }
        // Save data to WP usermeta
        and_save_clearxp_elearn_user_meta($object_data);
    }
}
add_action( 'init', 'and_get_elearn_data_from_clearxp', 10 );

/**
 * Print ClearXP eLearn Modules HTML
 * 
 * @param array $clearxp_elearns_meta Array of ClearXP eLearn meta data.
 */
function and_print_elearn_modules_html($clearxp_elearns_meta = []) {
    // Early return if no data is provided
    if (empty($clearxp_elearns_meta)) {
        echo '<li>No eLearn has commenced yet.</li>';
        return;
    }
    // Loop through eLearn modules
    foreach ($clearxp_elearns_meta as $elearn) {
        try {
            // Sanitize the module name
            $moduleName = esc_html($elearn['module_name'] ?? 'Unknown Module');

            // Sanitize and format dates
            $commencedDate = !empty($elearn['progress']['timestamp']) 
                ? (new DateTime($elearn['progress']['timestamp']))->format('Y-m-d H:i:s A') : '';

            $completionDate = !empty($elearn['completed']['timestamp']) 
                ? (new DateTime($elearn['completed']['timestamp']))->format('Y-m-d H:i:s A') : '';
            ?>
            <li>
                <p><?php echo 'Module: <b>' . $moduleName . '</b>'; ?></p>

                <?php if (!empty($commencedDate)): ?>
                    <p><?php echo 'Commenced date: ' . esc_html($commencedDate); ?></p>
                <?php endif; ?>

                <?php if (!empty($completionDate)): ?>
                    <p><?php echo 'Completion date: ' . esc_html($completionDate); ?></p>
                <?php endif; ?>
            </li>
            <?php
        } catch (Exception $e) {
            // Handle any exceptions (e.g., invalid date format)
            echo '<li>Module: <b>' . esc_html($moduleName) . '</b></li>';
        }
    }
}

/**ư
 * Ajax update usaged eLearns data from user meta
 */
function and_update_usaged_elearns_ajax() {
    $wp_user_id = isset($_POST['wp_user_id']) ? $_POST['wp_user_id'] : '';
    if (empty($wp_user_id)) {
        return;
    }
    // Get metadata
    $clearxp_elearns_meta = get_user_meta($wp_user_id, 'clearxp_elearns', true);
    // Print eLearn modules
    and_print_elearn_modules_html($clearxp_elearns_meta);
    die;
}
add_action('wp_ajax_update_usaged_elearns', 'and_update_usaged_elearns_ajax');
add_action('wp_ajax_nopriv_update_usaged_elearns', 'and_update_usaged_elearns_ajax');
