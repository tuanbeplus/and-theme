<?php

if( get_row_layout() == 'member_table_features' ):
    $table = get_sub_field('table');
    $member_pricing = get_sub_field('member_pricing');
    ?>
    <section class="membership-table">
        <div class="container">
            <div class="membership-table-wrapper" role="region" aria-labelledby="Caption01" tabindex="0">
            <table id="membership-table">
                <tr class="heading">
                    <th>
                        <p><?php echo $member_pricing['pricing_heading'] ?></p>
                    </th>
                    <th>
                        <div class="icons">
                            <img src="<?php echo AND_IMG_URI. 'bronze-level.svg' ?>" alt="Bronze Membership Level"/>
                            <p>Bronze</p>
                        </div>
                        <p class="price"><?php echo $member_pricing['bronze_member_pricing'] ?></p>
                    </th>
                    <th>
                        <div class="icons">
                            <img src="<?php echo AND_IMG_URI. 'silver-level.svg' ?>" alt="Silver Membership Level"/>
                            <p>Silver</p>
                        </div>
                        <p class="price"><?php echo $member_pricing['silver_menber_pricing'] ?></p>
                    </th>
                    <th>
                        <div class="icons">
                            <img src="<?php echo AND_IMG_URI. 'gold-level.svg' ?>" alt="Gold Membership Level"/>
                            <p>Gold</p>
                        </div>
                        <p class="price"><?php echo $member_pricing['gold_menber_pricing'] ?></p>
                    </th>
                </tr>
                <?php foreach ($table as $row): ?>
                    <tr>
                        <td class="feature-or-service-heading"><?php echo $row['feature_or_service'] ?></td>
                        <td><?php echo $row['bronze'] ?></td>
                        <td><?php echo $row['silver'] ?></td>
                        <td><?php echo $row['gold'] ?></td>
                    </tr>
                <?php endforeach; ?>
                <caption id="Caption01">Membership levels and pricing</caption>
            </table>
            </div>
        </div>
    </section>
    <?php
endif;