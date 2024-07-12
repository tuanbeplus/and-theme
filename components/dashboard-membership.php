<?php

if( get_row_layout() == 'dashboard_membership' ):
  global $sf_org_data;
  $renew_membership_cta = get_sub_field('renew_membership_cta');
  $manager_name = getUser($sf_org_data['manager'])->records[0]->Name;
    ?>
    <section id="membership" class="dashboard membership">
        <div class="container">
            <div class="inner">
                <div class="row">
                      <div class="col-12 header">
                          <div class="inside">
                              <div class="row">
                                  <div class="col-md-12 title">
                                      <img src="/wp-content/themes/and-theme/assets/imgs/membership.svg" alt="Membership" />
                                      <h2 class="membership-heading">
                                        <span><?php echo $sf_org_data['Name']; ?></span><br>
                                        Membership (Level: <span class="__level"><?php echo $sf_org_data['membership_level']; ?></span>)
                                      </h2>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-12 the-details">
                          <div class="info">
                              <p>Your membership expires in <span><?php echo $sf_org_data['renewal']; ?></span></p>
                          </div>
                          <div class="cta">
                              <a href="mailto:<?php echo $renew_membership_cta['cta_link']; ?>"><?php echo $renew_membership_cta['cta_text']; ?></a>
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
?>
