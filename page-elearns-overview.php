<?php
/*
* Template Name: E-Learn
*/

get_header();

// Profile member
const PRIMARY_MEMBER = '00e9q000000LrVSAA0';
const GENERAL_MEMBER = '00e9q000000Lqn7AAC';
const NONE_MEMBER = '00e9q000000LrVRAA0';

global $sf_user_id;
$organisationData = getAccountMember();
$user_data = getUser($_COOKIE['userId']);
$profile_id = $user_data->records[0]->ProfileId;
$contact_id = $user_data->records[0]->ContactId;
$opportunities = getOpportunity();
$elearns_arr = array();

if (isset($opportunities->records)) {
    foreach ($opportunities->records as $opportunity) {
        $elearns_by_opportunity_id = getElearnsByOpportunityId($opportunity->Id);

        if (isset($elearns_by_opportunity_id->records)) {
            foreach ($elearns_by_opportunity_id->records as $elearn){
                $elearns_arr[] = $elearn;
            }
        }
    }
}
?>

<?php if($_COOKIE['userId']): ?>
    <!-- E-learn loading -->
    <div class="elearn-loading-wrapper container" style="">
        <p class="loading-corprorate-name"><?php echo $organisationData['Name']; ?></p>
        <img class="img-loading" src="/wp-content/themes/and/assets/imgs/handshake_loading.png" alt="Handshake E-learn loading">
        <p class="loading-description">We're taking you to your e-learn lorem ipsum dolor</p>
    </div>
    <!-- /E-learn loading -->

    <div class="main-elearn-wrapper">
        <div class="container" style="padding:0;">
            <div class="elearn-headline">
                <div class="container">
                    <h1 class="__title"><?php echo $organisationData['Name']; ?> eLearns overview</h1>
                </div>
                <?php if( have_rows('page_builder') ): ?>
                    <?php while ( have_rows('page_builder') ) : the_row(); ?>
                        <div class="elearn-hero">
                            <?php get_template_part('components/single-column-content'); ?>
                        </div>
                    <?php endwhile;?>
                <?php endif; ?>
            </div>
        </div>
        <div class="container">
            <?php if( have_rows('page_builder') ): ?>
                <?php while ( have_rows('page_builder') ) : the_row(); ?>

                    <?php if ( get_row_layout() == 'purchased_e_learns' ): ?>
                        <?php 
                        $purchased_e_learns_heading = get_sub_field('purchased_e_learns_heading');
                        $purchased_e_learns_fields = get_sub_field('purchased_e_learns_fields');
                        $purchased_e_learns_cta = get_sub_field('purchased_e_learns_cta');
                        ?>
                        <section class="dashboard tasks purchased-elearns">
                            <div class="container">
                                <div class="inner">
                                    <div class="row">
                                        <div class="col-12 header">
                                            <div class="inside">
                                                <div class="row">
                                                    <div class="col-md-6 title">
                                                        <img class="" src="/wp-content/themes/and/assets/imgs/tasks.svg" alt="Purchased e-learns"/>
                                                        <h2><?php echo $purchased_e_learns_heading; ?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 the-details">
                                        <ul class="elearn-wrapper">
                                            <?php
                                            $is_elearn_arr = array();
                                            $i = 0;
                                            foreach ($elearns_arr as $elearn):

                                                $contact_name = getContacts($elearn->Contact__c)->records[0]->Name;

                                                $modules = array(
                                                    'All_Modules__c' => $elearn->All_Modules__c,
                                                    'About_Disability_Accessibility__c' => $elearn->About_Disability_Accessibility__c,
                                                    'Disability_Confidence_is_Good_Business__c' => $elearn->Disability_Confidence_is_Good_Business__c,
                                                    'Inclusive_Communication__c' => $elearn->Inclusive_Communication__c,
                                                    'Creating_Enabling_Environments__c' => $elearn->Creating_Enabling_Environments__c,
                                                    'Inclusive_Recruitment__c' => $elearn->Inclusive_Recruitment__c,
                                                    'Workplace_Adjustments__c' => $elearn->Workplace_Adjustments__c,
                                                    'Disability_Confident_Conversations__c' => $elearn->Disability_Confident_Conversations__c,
                                                    'Facilitating_Positive_Employment__c' => $elearn->Facilitating_Positive_Employment__c,
                                                    'Inclusive_Customer_Experiences__c' => $elearn->Inclusive_Customer_Experiences__c,
                                                    'The_Experience_Journey__c' => $elearn->The_Experience_Journey__c,
                                                    'Your_Disability_Confidence__c' => $elearn->Your_Disability_Confidence__c,
                                                );

                                                $bundles = array(
                                                    'General_Workforce_Bundle__c' => $elearn->General_Workforce_Bundle__c,
                                                    'Recruiters_HR_Bundle__c' => $elearn->Recruiters_HR_Bundle__c,
                                                    'Managers_Bundle__c' => $elearn->Managers_Bundle__c,
                                                    'Inclusive_Customer_Experiences_Bundle__c' => $elearn->Inclusive_Customer_Experiences_Bundle__c,
                                                    'DCR_Program_Bundle__c' => $elearn->DCR_Program_Bundle__c,
                                                );
                                                
                                                if ($profile_id == PRIMARY_MEMBER) {
                                                    $is_elearn_arr[] = $elearn->Id;
                                                    ?>
                                                    <li elearn_id="<?php echo $elearn->Id;?>">
                                                        <span><?php 
                                                            echo $purchased_e_learns_fields[0]['fields_title']; 
                                                            echo ': ';
                                                            echo $contact_name; ?>
                                                        </span>
                                                        <span class="elearn-modules"><?php 
                                                            echo $purchased_e_learns_fields[1]['fields_title']; 
                                                            echo ': ';
                                                            echo get_elearn_modules_purchased($modules, $bundles); ?>
                                                        </span>
                                                        <span><?php 
                                                            echo $purchased_e_learns_fields[2]['fields_title'];
                                                            echo ': ';
                                                            echo $elearn->Purchase_Date__c; ?>
                                                        </span>
                                                        <span><?php 
                                                            echo $purchased_e_learns_fields[3]['fields_title'];
                                                            echo ': ';
                                                            echo $elearn->Expiry_Date__c; ?>
                                                        </span>
                                                    </li>
                                                    <?php
                                                    if ($i++ > 3) break;
                                                }

                                                if ($profile_id == GENERAL_MEMBER || $profile_id == NONE_MEMBER) {
                                                    if ($elearn->Contact__c == $contact_id) {
                                                        $is_elearn_arr[] = $elearn->Id;
                                                        ?>
                                                        <li elearn_id="<?php echo $elearn->Id;?>">
                                                            <span class="elearn-modules"><?php 
                                                                echo $purchased_e_learns_fields[1]['fields_title']; 
                                                                echo ': ';
                                                                echo get_elearn_modules_purchased($modules, $bundles); ?>
                                                            </span>
                                                            <span><?php 
                                                                echo $purchased_e_learns_fields[2]['fields_title'];
                                                                echo ': ';
                                                                echo $elearn->Purchase_Date__c; ?>
                                                            </span>
                                                            <span><?php 
                                                                echo $purchased_e_learns_fields[3]['fields_title'];
                                                                echo ': ';
                                                                echo $elearn->Expiry_Date__c; ?>
                                                            </span>
                                                        </li>
                                                        <?php
                                                        if ($i++ > 3) break;
                                                    }
                                                }
                                            endforeach; 

                                            // Show message if no eLearns found
                                            if (empty($is_elearn_arr)) {
                                                echo "<li>There are currently no eLearns.</li>";
                                            }
                                            ?>
                                        </ul>
                                        </div>
                                        <?php if ($purchased_e_learns_cta['cta_link']): ?>
                                            <div class="col-12 cta">
                                                <div class="inside">
                                                <a class="btn-action" href="<?php echo $purchased_e_learns_cta['cta_link']; ?>">
                                                    <div><span class="material-icons">arrow_forward</span></div>
                                                    <?php echo $purchased_e_learns_cta['cta_text']; ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>

                    <?php if ( get_row_layout() == 'e_learn_usage_to_date' ): ?>
                        <?php 
                        $e_learn_usage_to_date_heading = get_sub_field('e_learn_usage_to_date_heading');
                        $e_learn_usage_to_date_fields = get_sub_field('e_learn_usage_to_date_fields');
                        $e_learn_usage_to_date_cta = get_sub_field('e_learn_usage_to_date_cta');
                        ?>
                        <section class="dashboard upcoming-events elearn-usage">
                            <div class="container">
                                <div class="inner">
                                    <div class="row">
                                        <div class="col-12 header">
                                            <div class="inside">
                                                <div class="row">
                                                    <div class="col-md-12 title">
                                                        <img src="/wp-content/themes/and/assets/imgs/upcoming-events.svg" />
                                                        <h2><?php echo $e_learn_usage_to_date_heading; ?></h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 the-details">
                                        <ul class="elearn-wrapper">
                                            <?php
                                            $i = 0;
                                            $elearns_commenced_array = array();
                                            
                                            foreach ($elearns_arr as $elearn):

                                                $contact_name = getContacts($elearn->Contact__c)->records[0]->Name;

                                                $modules = array(
                                                    'All_Modules__c' => $elearn->All_Modules__c,
                                                    'About_Disability_Accessibility__c' => $elearn->About_Disability_Accessibility__c,
                                                    'Disability_Confidence_is_Good_Business__c' => $elearn->Disability_Confidence_is_Good_Business__c,
                                                    'Inclusive_Communication__c' => $elearn->Inclusive_Communication__c,
                                                    'Creating_Enabling_Environments__c' => $elearn->Creating_Enabling_Environments__c,
                                                    'Inclusive_Recruitment__c' => $elearn->Inclusive_Recruitment__c,
                                                    'Workplace_Adjustments__c' => $elearn->Workplace_Adjustments__c,
                                                    'Disability_Confident_Conversations__c' => $elearn->Disability_Confident_Conversations__c,
                                                    'Facilitating_Positive_Employment__c' => $elearn->Facilitating_Positive_Employment__c,
                                                    'Inclusive_Customer_Experiences__c' => $elearn->Inclusive_Customer_Experiences__c,
                                                    'The_Experience_Journey__c' => $elearn->The_Experience_Journey__c,
                                                    'Your_Disability_Confidence__c' => $elearn->Your_Disability_Confidence__c,
                                                );

                                                $bundles = array(
                                                    'General_Workforce_Bundle__c' => $elearn->General_Workforce_Bundle__c,
                                                    'Recruiters_HR_Bundle__c' => $elearn->Recruiters_HR_Bundle__c,
                                                    'Managers_Bundle__c' => $elearn->Managers_Bundle__c,
                                                    'Inclusive_Customer_Experiences_Bundle__c' => $elearn->Inclusive_Customer_Experiences_Bundle__c,
                                                    'DCR_Program_Bundle__c' => $elearn->DCR_Program_Bundle__c,
                                                );
                                                
                                                if ($profile_id == PRIMARY_MEMBER) {
                                                    if ($elearn->Commenced_Date__c){ 
                                                        $elearns_commenced_array[] = $elearn->Id; ?>

                                                        <li elearn_id="<?php echo $elearn->Id;?>">
                                                            <span class="elearn-modules"><?php 
                                                                echo $e_learn_usage_to_date_fields[0]['fields_title']; 
                                                                echo ': ';
                                                                echo get_elearn_modules_purchased($modules, $bundles); ?>
                                                            </span>
                                                            <span><?php 
                                                                echo $e_learn_usage_to_date_fields[1]['fields_title'];
                                                                echo ': ';
                                                                echo $elearn->Commenced_Date__c; ?>
                                                            </span>

                                                            <?php if ($elearn->Completion_Date__c): ?>
                                                                <span><?php 
                                                                    echo $e_learn_usage_to_date_fields[2]['fields_title'];
                                                                    echo ': ';
                                                                    echo $elearn->Completion_Date__c; ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php
                                                    } 
                                                }

                                                if ($profile_id == GENERAL_MEMBER || $profile_id == NONE_MEMBER) {
                                                    if ($elearn->Contact__c == $contact_id && $elearn->Commenced_Date__c){ 
                                                        $elearns_commenced_array[] = $elearn->Id; ?>

                                                        <li elearn_id = "<?php echo $elearn->Id;?>">    
                                                            <span class="elearn-modules"><?php 
                                                                echo $e_learn_usage_to_date_fields[0]['fields_title']; 
                                                                echo ': ';
                                                                echo get_elearn_modules_purchased($modules, $bundles); ?>
                                                            </span>
                                                            <span><?php 
                                                                echo $e_learn_usage_to_date_fields[1]['fields_title'];
                                                                echo ': ';
                                                                echo $elearn->Commenced_Date__c; ?>
                                                            </span>

                                                            <?php if ($elearn->Completion_Date__c): ?>
                                                                <span><?php 
                                                                    echo $e_learn_usage_to_date_fields[2]['fields_title'];
                                                                    echo ': ';
                                                                    echo $elearn->Completion_Date__c; ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php
                                                    if ($i++ > 3) break;
                                                    } 
                                                }
                                            endforeach; 

                                            if (empty($elearns_commenced_array)) {
                                                echo '<li>No eLearn has commenced yet.</li>';
                                            }
                                            ?>
                                            </ul>
                                        </div>
                                        <?php if ($e_learn_usage_to_date_cta['cta_link']): ?>
                                            <div class="col-12 cta">
                                                <div class="inside">
                                                    <a href="<?php echo $e_learn_usage_to_date_cta['cta_link']; ?>">
                                                        <div><span class="material-icons">arrow_forward</span></div>
                                                        <?php echo $e_learn_usage_to_date_cta['cta_text']; ?>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>

                <?php endwhile;?>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <section class="" style="margin-bottom: 2rem;">
        <div class="container">
            <h3>You must login to see eLearn overview</h3>
        </div>
    </section>
<?php endif; ?>
<?php
get_footer();
