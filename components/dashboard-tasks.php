

<?php

if( get_row_layout() == 'dashboard_tasks' ):
    global $sf_org_data;
    $tasks_heading = get_sub_field('tasks_heading');
    ?>
    <section class="dashboard tasks">
        <div class="container">
            <div class="inner">
                <div class="row">
                    <div class="col-12 header">
                        <div class="inside">
                            <div class="row">
                                <div class="col-md-6 title">
                                    <img src="<?php echo AND_IMG_URI. 'tasks.svg' ?>" alt="Tasks"/>
                                    <h2><?php echo $tasks_heading; ?></h2>
                                </div>
                                <div class="col-md-6 remaining-hours"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 the-details">
                    <?php 
                        echo '<ul>
                                <li class="task '.($sf_org_data['tasks']['network'] ? 'completed' : '').'">Maintains an Employee Network</li>
                                <li class="task '.($sf_org_data['tasks']['review'] ? 'completed' : '').'">Recruitment Review</li>
                                <li class="task '.($sf_org_data['tasks']['workplace'] ? 'completed' : '').'">Workplace Adjustment Policy or Procedure</li>
                                <li class="task '.($sf_org_data['tasks']['action_plan'] ? 'completed' : '').'">Accessibility Action Plan in place</li>
                            </ul>' 
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
endif;
