<?php

if( get_row_layout() == 'dashboard_opportunities' ):

    global $opportunities;
    $opportunities_heading = get_sub_field('opportunities_heading');
    $opportunities_cta = get_sub_field('opportunities_cta');
    ?>
    <section class="dashboard tasks opportunities">
        <div class="container">
            <div class="inner">
                <div class="row">
                    <div class="col-12 header">
                        <div class="inside">
                            <div class="row">
                                <div class="col-md-6 title">
                                    <img src="/wp-content/themes/and-theme/assets/imgs/tasks.svg" alt="Opportunities"/>
                                    <h2><?php echo $opportunities_heading; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 the-details">
                        <ul>
                        <?php 
                        $count_opp = 0;
                        if ( !empty( $opportunities ) ) {
                            // Show opportunity items
                            foreach ($opportunities as $key => $opportunity): 
                                if ($opportunity->StageName == 'Proposed' || $opportunity->StageName == 'Proposal Accepted'):
                                    ?>
                                    <li>
                                        <span>Opportunity: <?php echo $opportunity->Name; ?></span>
                                        <span>Stage: <?php echo $opportunity->StageName;?></span>
                                        <span>Close Date: <?php 
                                            $close_date = new DateTime($opportunity->CloseDate);
                                            echo $close_date->format('d/m/Y'); ?>
                                        </span>
                                    </li>
                                    <?php
                                    $count_opp++;
                                endif;
                                if ($key > 4) break;
                            endforeach;
                        } 
                        if (empty($opportunities) || $count_opp == 0) {
                            echo "<li>There are currently no opportunities.</li>";
                        } ?>
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
        </div>
    </section>
<?php
endif;
