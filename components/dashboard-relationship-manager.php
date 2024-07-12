

<?php

if( get_row_layout() == 'dashboard_relationship_manager' ):
    global $sf_org_data;
    $relationship_manager_heading = get_sub_field('relationship_manager_heading');
    $manager_name = getUser($sf_org_data['manager'])->records[0]->Name;
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
                            <p class="manager-name"><?php echo $manager_name; ?></p>
                            <ul>
                                <li>
                                    <a href="tel:<?php echo $sf_org_data['manager_phone']; ?>"><?php echo $sf_org_data['manager_phone']; ?></a>
                                </li>
                                <li>
                                    <a href="mailto:<?php echo $sf_org_data['manager_email']; ?>">Contact your Relationship Manager</a>
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
