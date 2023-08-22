<?php
/*
* Template Name: Dashboard
*/
get_header();

$wp_assessment = new WP_Assessment();
$sf_login_redirect_url = $wp_assessment->get_data_value_local_storage('sf_login_redirect_url');

if ($sf_login_redirect_url && $_COOKIE['userId']) {
    wp_redirect($sf_login_redirect_url);
}
elseif ($sf_login_redirect_url && empty($_COOKIE['userId'])){
    $wp_assessment->save_data_local_storage('sf_login_redirect_url', null);
}

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

$welcome_text = '';
if(isset($_COOKIE['userId']) && $_COOKIE['userId']):
    $parts = explode(" ", $_COOKIE['sf_name']);
    $lastname = array_pop($parts);
    $firstname = implode(" ", $parts);
    $welcome_text = '<h1>Welcome back, '.$firstname.'</h1>';
else:
    $welcome_text = '<h3 style="text-align:center;">Welcome back, You must be login to see your Dashboard.</h3>';
endif;

?>
    <div class="main-dashboard container">
        <div class="dashboard welcome">
            <div class="container">
                <?php echo $welcome_text; ?>
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

<?php
get_footer();
?>
