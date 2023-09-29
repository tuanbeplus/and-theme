<?php

if( get_row_layout() == 'dashboard_history' ):

    $response = getTasks();
    $history_heading = get_sub_field('history_heading');
    ?>
    <section class="dashboard tasks history">
        <div class="container">
            <div class="inner">
                <div class="row">
                    <div class="col-12 header">
                        <div class="inside">
                            <div class="row">
                                <div class="col-md-6 title">
                                    <img src="/wp-content/themes/and/assets/imgs/tasks.svg" alt="history" />
                                    <h2><?php echo $history_heading; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 the-details">
                        <ul>
                            <?php
                                $i = 0;
                                $str_email = "Email:";
                            ?>
                            <?php if (!empty($response->records)): ?>
                                <?php if ($response->records[0]->Status !== 'Completed'): ?>
                                    <li>No latest completed Task</li>
                                <?php endif; ?>
                                <?php
                                    foreach ($response->records as $task_completed):
                                        if ($task->Status == 'Completed'):
                                    ?>
                                            <li>
                                                <?php if (substr($task_completed->Subject, 0, 6) == $str_email): ?>
                                                <span>Task Subject:
                                                    <?php echo str_replace($str_email, " ", $task_completed->Subject); ?></span><br>
                                                <?php else: ?>
                                                <span>Task Subject: <?php echo $task_completed->Subject; ?></span><br>
                                                <?php endif; ?>
                                                <span>Status: <?php echo $task_completed->Status; ?></span>
                                            </li>
                                        <?php
                                        endif;
                                        if ($i++ > 3) break;
                                    endforeach; ?>
                            <?php else: ?>
                                <li>No latest completed Task</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>