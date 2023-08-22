<?php

if( get_row_layout() == 'dashboard_opportunities' ):

    $opportunities_heading = get_sub_field('opportunities_heading');
    $opportunities_cta = get_sub_field('opportunities_cta');
    $response = getOpportunity();
    $opps_array = array();
    foreach ($response->records as $opport_unity):
        if ($opport_unity->StageName == 'Proposed' || $opport_unity->StageName == 'Proposal Accepted') {
            $opps_array[] = $opport_unity;
        }
    endforeach;
    ?>
    <section class="dashboard tasks opportunities">
        <div class="container">
            <div class="inner">
                <div class="row">
                    <div class="col-12 header">
                        <div class="inside">
                            <div class="row">
                                <div class="col-md-6 title">
                                    <img src="/wp-content/themes/and/assets/imgs/tasks.svg" alt="Opportunities"/>
                                    <h2><?php echo $opportunities_heading; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 the-details">
                        <ul>
                          <?php
                            if ( !empty( $opps_array ) ) {
                                // Show opportunity items
                                foreach ($opps_array as $key => $opportunity):
                                  $opportunity_contact = getContacts($opportunity->Contact__c); ?>
                                  <li>
                                    <span>Opportunity: <?php echo $opportunity->Name; ?></span>
                                    <span>Stage: <?php echo $opportunity->StageName;?></span>
                                    <span>Close Date: <?php 
                                        $close_date = new DateTime($opportunity->CloseDate);
                                        echo $close_date->format('d/m/Y'); ?>
                                    </span>
                                   </li>
                                   <?php
                                   if ($key > 4) break;
                                endforeach;
                            } else {
                                echo "<li>There are currently no opportunities.</li>";
                            }
                          ?>
                        </ul>
                    </div>
                    <div class="col-12 cta">
                        <div class="inside">
                            <a href="<?php echo $opportunities_cta['cta_link']; ?>">
                                <div><span class="material-icons">arrow_forward</span></div>
                                <?php echo $opportunities_cta['cta_text']; ?></a>
                            </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
endif;
