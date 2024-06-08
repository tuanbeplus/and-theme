<?php
/*
* Template Name: Dashboard
*/
get_header();

// Profile member
const GENERAL_MEMBER = '00e9q000000Lqn7AAC';
const NONE_MEMBER = '00e9q000000LrVRAA0';
const PRIMARY_MEMBER = '00e9q000000LrVSAA0';

$organisationData = getAccountMember();
$wp_user_id = get_current_user_id();
$user_data = getUser($_COOKIE['userId']);
$wp_user_meta_json = get_user_meta($wp_user_id, '__salesforce_user_meta', true);
$wp_user_meta = json_decode($wp_user_meta_json, true);

// get WP User Role
$wp_user_roles = get_userdata($wp_user_id)->roles ?? array();

// get Salesforce Contact ID
$contact_id = $wp_user_meta['ContactId'] ?? '';
if (empty($contact_id)) {
    $contact_id = $user_data->records[0]->ContactId;
}
// get Salesforce Account ID
$account_id = $wp_user_meta['AccountId'] ?? '';
if (empty($account_id)) {
    $account_id = $user_data->records[0]->AccountId;
}
// get Salesforce member profile
$user_profile = '';
$is_member = $wp_user_meta['Members__c'] ?? false;
$is_non_member = $wp_user_meta['Non_Members__c'] ?? false;
$is_primary_member = $wp_user_meta['Primary_Members__c'] ?? false;

if ($is_member == true || in_array('MEMBERS', $wp_user_roles)) {
    $user_profile = 'MEMBERS';
}
elseif ($is_non_member == true || in_array('NON_MEMBERS', $wp_user_roles)) {
    $user_profile = 'NON_MEMBERS';
}
elseif ($is_primary_member == true || in_array('PRIMARY_MEMBERS', $wp_user_roles)) {
    $user_profile = 'PRIMARY_MEMBERS';
}

global $contact_id, $account_id, $user_profile;
?>

<?php if(isset($_COOKIE['userId']) && is_user_logged_in()): ?>
    <div class="main-dashboard container">
        <div class="dashboard welcome">
            <div class="container">
                <?php 
                    $firstname = $user_data->records[0]->FirstName;
                    echo '<h1>Welcome back, '.$firstname.'</h1>';
                ?>
                <?php if ($organisationData['hours_remain'] && $user_profile == 'PRIMARY_MEMBERS'): ?>
                    <h3>You have <?php echo $organisationData['hours_remain']; ?> hours remaining</h3>
                <?php endif; ?>
            </div>
        </div>
        <div class="dashboard-wrapper">
            <div class="dashboard-wrapper-right">
                <?php
                // check if the flexible content field has rows of data
                if( have_rows('page_builder') ):
                    // loop through the rows of data
                while ( have_rows('page_builder') ) : the_row();
                    // Primary Members
                    if ($user_profile == 'PRIMARY_MEMBERS') { 
                        get_template_part('components/dashboard-tasks');
                        get_template_part('components/dashboard-opportunities');
                        get_template_part('components/dashboard-submissions');
                        get_template_part('components/dashboard-e-learn');
                        get_template_part('components/dashboard-index');
                        get_template_part('components/dashboard-dcr');
                        get_template_part('components/dashboard-resources');
                        get_template_part('components/dashboard-history');
                    }
                    // Members
                    elseif ($user_profile == 'MEMBERS') { 
                        get_template_part('components/dashboard-submissions');
                        get_template_part('components/dashboard-e-learn');
                        get_template_part('components/dashboard-index');
                        get_template_part('components/dashboard-dcr');
                        get_template_part('components/dashboard-resources');
                    }
                    // Non Members
                    elseif ($user_profile == 'NON_MEMBERS') { 
                        get_template_part('components/dashboard-submissions');
                        get_template_part('components/dashboard-e-learn');
                        get_template_part('components/dashboard-index');
                        get_template_part('components/dashboard-dcr');
                        get_template_part('components/dashboard-resources');
                    }
                    endwhile;
                else :
                    // no layouts found
                endif;
            ?>
            </div>

            <div class="dashboard-wrapper-left">
            <?php
            // check if the flexible content field has rows of data
            if( have_rows('page_builder') ):
                // loop through the rows of data
                while ( have_rows('page_builder') ) : the_row();
                    // Primary Members
                    if ($user_profile == 'PRIMARY_MEMBERS') { 
                        get_template_part('components/dashboard-upcoming-events');
                        get_template_part('components/dashboard-membership');
                        get_template_part('components/dashboard-advertisement');
                    }
                    // Members
                    elseif ($user_profile == 'MEMBERS') { 
                        get_template_part('components/dashboard-upcoming-events');
                        get_template_part('components/dashboard-relationship-manager');
                        get_template_part('components/dashboard-advertisement');
                    }
                    // Non Members
                    elseif ($user_profile == 'NON_MEMBERS') { 
                        get_template_part('components/dashboard-upcoming-events');
                        get_template_part('components/dashboard-relationship-manager');
                        get_template_part('components/dashboard-advertisement');
                    }
                endwhile;
            else :
                // no layouts found
            endif;
            ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="main-dashboard container">
        <div class="dashboard welcome">
            <div class="container">
                <h3 style="text-align:center;">Welcome back, You must be login to see your Dashboard.</h3>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php
get_footer();
?>
