

<?php

if( get_row_layout() == 'dashboard_tasks' ):

    $tasks_heading = get_sub_field('tasks_heading');
    $response = getTasks();
    $organisationData = getAccountMember();
    ?>
    <section class="dashboard tasks">
        <div class="container">
            <div class="inner">
                <div class="row">
                    <div class="col-12 header">
                        <div class="inside">
                            <div class="row">
                                <div class="col-md-6 title">
                                    <img src="/wp-content/themes/and/assets/imgs/tasks.svg" alt="Tasks"/>
                                    <h2><?php echo $tasks_heading; ?></h2>
                                </div>
                                <div class="col-md-6 remaining-hours">

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 the-details">
                        <?php 
                            echo '<ul>
                                    <li class="task '.($organisationData['tasks']['network'] ? 'completed' : '').'">Maintains an Employee Network</li>
                                    <li class="task '.($organisationData['tasks']['review'] ? 'completed' : '').'">Recruitment Review</li>
                                    <li class="task '.($organisationData['tasks']['workplace'] ? 'completed' : '').'">Workplace Adjustment Policy or Procedure</li>
                                    <li class="task '.($organisationData['tasks']['action_plan'] ? 'completed' : '').'">Accessibility Action Plan in place</li>
                                </ul>' 
                            ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
endif;
