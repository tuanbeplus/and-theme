<?php
/*
* Template Name: Dashboard
*/
get_header();

$organisationData = getAccountMember();
$user_data = getUser($_COOKIE['userId']);

$contact_id = $user_data->records[0]->ContactId;
$account_id = $user_data->records[0]->AccountId;
$profile_id = $user_data->records[0]->ProfileId;

global $contact_id, $account_id, $profile_id;

// Profile member
const PRIMARY_MEMBER = '00e9q000000LrVSAA0';
const GENERAL_MEMBER = '00e9q000000Lqn7AAC';
const NONE_MEMBER = '00e9q000000LrVRAA0';
?>

<?php if(isset($_COOKIE['userId']) && is_user_logged_in()): ?>
    <div class="main-dashboard container">
        <div class="dashboard welcome">
            <div class="container">
                <?php 
                    $firstname = $user_data->records[0]->FirstName;
                    echo '<h1>Welcome back, '.$firstname.'</h1>';
                ?>
                <?php if ($organisationData['hours_remain'] && $profile_id == PRIMARY_MEMBER): ?>
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

                    if ($profile_id == PRIMARY_MEMBER) { // Primary Member
                        get_template_part('components/dashboard-tasks');
                        get_template_part('components/dashboard-opportunities');
                        get_template_part('components/dashboard-submissions');
                        get_template_part('components/dashboard-e-learn');
                        get_template_part('components/dashboard-index');
                        get_template_part('components/dashboard-dcr');
                        get_template_part('components/dashboard-resources');
                        get_template_part('components/dashboard-history');
                    }
                    elseif ($profile_id == GENERAL_MEMBER) { // General Member 
                        get_template_part('components/dashboard-submissions');
                        get_template_part('components/dashboard-e-learn');
                        get_template_part('components/dashboard-index');
                        get_template_part('components/dashboard-dcr');
                        get_template_part('components/dashboard-resources');
                    }
                    elseif ($profile_id == NONE_MEMBER) { // None Member
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

                    if ($profile_id == PRIMARY_MEMBER) { // Primary Member
                        get_template_part('components/dashboard-upcoming-events');
                        get_template_part('components/dashboard-membership');
                        get_template_part('components/dashboard-advertisement');
                    }
                    elseif ($profile_id == GENERAL_MEMBER) { // General Member
                        get_template_part('components/dashboard-upcoming-events');
                        get_template_part('components/dashboard-relationship-manager');
                        get_template_part('components/dashboard-advertisement');
                    }
                    elseif ($profile_id == NONE_MEMBER) { // None Member
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
