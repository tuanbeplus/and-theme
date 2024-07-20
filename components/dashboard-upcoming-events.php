<?php

if( get_row_layout() == 'dashboard_upcoming_events' ):
    
    global $contact_id, $account_id, $user_profile;
    $response = getCampains();
    $upcoming_events_heading = get_sub_field('upcoming_events_heading');
    $upcoming_events_cta = get_sub_field('upcoming_events_cta');
?>
<section class="dashboard upcoming-events">
    <div class="container">
        <div class="inner">
            <div class="row">
                <div class="col-12 header">
                    <div class="inside">
                        <div class="row">
                            <div class="col-md-12 title">
                                <img src="<?php echo AND_IMG_URI. 'upcoming-events.svg' ?>" />
                                <h2><?php echo $upcoming_events_heading; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 the-details">
                    <ul>
                    <?php
                    $i = 0;
                    $cnt_items = 0;
                    $events_array = array();

                    foreach ($response->records as $event) {
                        $date_now = strtotime("now");
                        $event_date = strtotime($event->StartDate);
                        
                        if ($user_profile == 'NON_MEMBERS') {
                            if ($event_date > $date_now && $event->Event_Type__c == 'Public') { //Event_Date__c
                                echo '<li class="event">
                                        <div class="event-info">
                                            <p><span>Campaign: </span>' . $event->Name . '</p>
                                            <p><span>Date: </span><span>' . $event->Event_Date__c . '</span></p>
                                            <p><span>Time: </span><span> ' . $event->Event_Time__c . '</span></p>
                                            <p><span>Location: </span><span> ' . $event->Event_Location__c . '</span></p>
                                        </div>
                                    </li>';
                                $cnt_items++;
                                $events_array[] = $event->Name;
                            }
                        }
                        elseif ($user_profile == 'PRIMARY_MEMBERS' || $user_profile == 'MEMBERS') {
                            if ($event_date > $date_now ) {
                                echo '<li class="event">
                                        <div class="event-info">
                                            <p><span>Campaign: </span>' . $event->Name . '</p>
                                            <p><span>Date: </span><span>' . $event->Event_Date__c . '</span></p>
                                            <p><span>Time: </span><span> ' . $event->Event_Time__c . '</span></p>
                                            <p><span>Location: </span><span> ' . $event->Event_Location__c . '</span></p>
                                        </div>
                                    </li>';
                                $cnt_items++;
                                $events_array[] = $event->Name;
                            }
                        }
                        if ($cnt_items > 5) break;
                    }
                    if (empty( $events_array )) {
                        echo "<li>There are currently no campaign.</li>";
                    }
                    ?>
                    </ul>
                </div>
                <div class="col-12 cta">
                    <div class="inside">
                        <a href="<?php echo $upcoming_events_cta['cta_link']; ?>">
                            <div><span class="material-icons">arrow_forward</span></div>
                            <?php echo $upcoming_events_cta['cta_text']; ?>
                        </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif;
