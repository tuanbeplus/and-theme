<?php

if( get_row_layout() == 'dashboard_membership' ):

    $renew_membership_cta = get_sub_field('renew_membership_cta');
    $organisationData = getAccountMember();
    $ProfileId = getUser($_COOKIE['userId'])->records[0]->ProfileId;
    $user_data = getUser($organisationData['manager'])->records[0]->Name;
    ?>
    <section id="membership" class="dashboard membership">
        <div class="container">
            <div class="inner">
                <div class="row">
                      <div class="col-12 header">
                          <div class="inside">
                              <div class="row">
                                  <div class="col-md-12 title">
                                      <img src="/wp-content/themes/and/assets/imgs/membership.svg" alt="Membership" />
                                      <h2 class="membership-heading">
                                        <span><?php echo $organisationData['Name']; ?></span><br>
                                        Membership (Level: <span class="__level"><?php echo $organisationData['membership_level']; ?></span>)
                                      </h2>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-12 the-details">
                          <div class="info">
                              <p>Your membership expires in <span><?php echo $organisationData['renewal']; ?></span></p>
                          </div>
                          <div class="cta">
                              <a href="mailto:<?php echo $renew_membership_cta['cta_link']; ?>"><?php echo $renew_membership_cta['cta_text']; ?></a>
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
?>
