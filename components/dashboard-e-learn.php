<?php

if( get_row_layout() == 'dashboard_e_learn' ):

    global $contact_id, $account_id, $user_profile, $sf_org_data, $opportunities;

    $e_learn_heading = get_sub_field('e_learn_heading');
    $e_learns_fields_title = get_sub_field('e_learns_fields_title');
    $e_learn_cta = get_sub_field('e_learn_cta');
    $elearns_arr = array();

    if (!empty($opportunities)) {
        foreach ($opportunities as $opportunity) {
            $elearns_by_opportunity_id = getElearnsByOpportunityId($opportunity->Id);
            if (isset($elearns_by_opportunity_id->records)) {
                foreach ($elearns_by_opportunity_id->records as $elearn){
                    $elearns_arr[] = $elearn;
                }
            }
        }
    }
?>
    <section class="dashboard tasks e-learn">
        <div class="container">
            <div class="title">
                <h2>Upcoming programs</h2>
            </div>
            <div class="inner">
                <div class="row">
                    <div class="col-12 header">
                        <div class="inside">
                            <div class="row">
                                <div class="col-md-6 title">
                                    <img src="/wp-content/themes/and-theme/assets/imgs/tasks.svg" alt="e-learn"/>
                                    <h2><?php echo $e_learn_heading; ?></h2>
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
                                
                            if ($user_profile == 'PRIMARY_MEMBERS') { 
                                $is_elearn_arr[] = $elearn->Id; ?>
                                <li elearn_id="<?php echo $elearn->Id;?>">
                                    <span class="elearn-contact">
                                        <?php echo $e_learns_fields_title[0]['title'] .': '. $contact_name;  ?>
                                    </span>
                                    <span class="elearn-modules"><?php 
                                        echo $e_learns_fields_title[1]['title']; 
                                        echo ': ';
                                        echo get_elearn_modules_purchased($modules, $bundles); ?>
                                    </span>
                                    <span><?php 
                                        $purchase_date = new DateTime($elearn->Purchase_Date__c);
                                        echo $e_learns_fields_title[2]['title'] .': '. $purchase_date->format('d/m/Y'); ?>
                                    </span>
                                    <span><?php 
                                        $expiry_date = new DateTime($elearn->Expiry_Date__c);
                                        echo $e_learns_fields_title[3]['title'] .': '. $expiry_date->format('d/m/Y'); ?>
                                    </span>
                                </li>
                                <?php
                                if ($i++ > 3) break;
                            }

                            if ($user_profile == 'MEMBERS' || $user_profile == 'NON_MEMBERS') {
                                if ($elearn->Contact__c == $contact_id) {
                                    $is_elearn_arr[] = $elearn->Id;
                                    ?>
                                    <li elearn_id="<?php echo $elearn->Id;?>">
                                        <span class="elearn-modules"><?php 
                                            echo $e_learns_fields_title[1]['title']; 
                                            echo ': ';
                                            echo get_elearn_modules_purchased($modules, $bundles); ?>
                                        </span>
                                        <span><?php 
                                            $purchase_date = new DateTime($elearn->Purchase_Date__c);
                                            echo $e_learns_fields_title[2]['title'] .': '. $purchase_date->format('d/m/Y'); ?>
                                        </span>
                                        <span><?php 
                                            $expiry_date = new DateTime($elearn->Expiry_Date__c);
                                            echo $e_learns_fields_title[3]['title'] .': '. $expiry_date->format('d/m/Y'); ?>
                                        </span>
                                    </li>
                                <?php
                                if ($i++ > 3) break;
                                }
                            }
                        endforeach; 

                        if (empty($is_elearn_arr)) {
                            echo "<li>There are currently no eLearn.</li>";
                        }
                        ?>
                        </ul>
                    </div>
                    <div class="col-12 cta">
                        <div class="inside">
                            <a href="<?php echo $e_learn_cta['cta_link']; ?>">
                                <div><span class="material-icons">arrow_forward</span></div>
                                <?php echo $e_learn_cta['cta_text']; ?></a>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
endif;
