<?php
/*
* Template Name: Dashboard
*/
get_header();

// Profile Members
const MEMBERS_ID = '00e9q000000Lqn7AAC';
const PRIMARY_MEMBERS_ID = '00e9q000000LrVSAA0';
const NON_MEMBERS_ID = '00e9q000000LrVRAA0';

// Get member data
$member_data = and_prepare_member_data_for_dashboard();
$user_id        = $member_data['Id'];
$contact_id     = $member_data['ContactId'];
$account_id     = $member_data['AccountId'];
$user_profile   = $member_data['user_profile'];
$user_data      = $member_data['user_data'];
$sf_org_data    = $member_data['org_data'];
$opportunities  = $member_data['opportunities'];

$first_name = $user_data['FirstName'] ?? '';
$member_hours_remain = $sf_org_data['hours_remain'] ?? '';

$assessments_for_all_users = get_assessments_accessible_all_users();
$saturn_assessments_list = get_assessments_related_saturn_products();
$merged_assessments = array_unique(array_merge($assessments_for_all_users, $saturn_assessments_list));
$assessments_accessible_list = get_assessments_on_dashboard($user_id, $account_id, $merged_assessments);

global $contact_id, $account_id, $user_profile, $sf_org_data, $opportunities, $assessments_accessible_list;
?>

<?php if(isset($_COOKIE['userId']) && is_user_logged_in()): ?>
    <div class="main-dashboard container">
        <div class="dashboard welcome">
            <div class="container">
                <h1><?php echo 'Welcome back, '. $first_name; ?></h1>
                <?php if (!empty($member_hours_remain) && $user_profile == 'PRIMARY_MEMBERS'): ?>
                    <h3>You have <?php echo $member_hours_remain ?> hours remaining</h3>
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
