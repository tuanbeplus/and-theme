

<?php

if( get_row_layout() == 'dashboard_relationship_manager' ):

    $relationship_manager_heading = get_sub_field('relationship_manager_heading');
    $organisationData = getAccountMember();
    $user_data = getUser($organisationData['manager'])->records[0]->Name;
    ?>
    <section class="dashboard membership relationship-manager">
        <div class="container">
            <div class="inner">
                <div class="row">
                    <div class="col-12 header">
                        <div class="inside">
                            <div class="row">
                                <div class="col-md-12 title">
                                    <img src="/wp-content/themes/and-theme/assets/imgs/relationship-manager.svg" alt="Relationship manager"/>
                                    <h2><?php echo $relationship_manager_heading; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 header relationship-manager">
                        <div class="inside">
                            <p>Your relationship manager is:</p>
                            <p class="manager-name"><?php echo $user_data; ?></p>
                            <ul>
                                <li>
                                    <a href="tel:<?php echo $organisationData['manager_phone']; ?>"><?php echo $organisationData['manager_phone']; ?></a>
                                </li>
                                <li>
                                    <a href="mailto:<?php echo $organisationData['manager_email']; ?>">Contact your Relationship Manager</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
endif;
