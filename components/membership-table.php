<?php

if( get_row_layout() == 'member_table_features' ):
    $table = get_sub_field('table');
    $member_pricing = get_sub_field('member_pricing');
    $tableHtml = '';
    // if($_GET['test'] == 'test') {    }
    

    $i = 0;
    foreach($table as $row) {
       
        $tableHtml .= '<div class="row feature-row">';

        $tableHtml .= '
                    <div class="col-12 col-md-3 feature left">
                        <p>'.$row['feature_or_service'].'</p>
                    </div>
                    <div class="col-4 col-md-3 feature">
                        <p>'.$row['bronze'].'</p>
                    </div>
                    <div class="col-4 col-md-3 feature">
                        <p>'.$row['silver'].'</p>
                    </div>
                    <div class="col-4 col-md-3 feature">
                        <p>'.$row['gold'].'</p>
                    </div>';
    
    
         $tableHtml .= '</div>';

        $i++;
    }

    echo '<section class="membership-table">
            <div class="container">
                <div class="row heading">
                    <div class="d-none d-md-inline col-md-3">
                        <p>'. $member_pricing['pricing_heading'] .'</p>
                    </div>
                    <div class="col-4 col-md-3 level">
                        <div class="icons">
                            <img src="/wp-content/themes/and/assets/imgs/bronze-level.svg" alt="Bronze Membership Level"/>
                            <p>Bronze</p>
                        </div>
                        <p class="price">'. $member_pricing['bronze_member_pricing'] .'</p>
                    </div>
                    <div class="col-4 col-md-3 level">
                        <div class="icons">
                            <img src="/wp-content/themes/and/assets/imgs/silver-level.svg" alt="Silver Membership Level"/>
                            <p>Silver</p>
                        </div>
                        <p class="price">'. $member_pricing['silver_menber_pricing'] .'</p>
                    </div>
                    <div class="col-4 col-md-3 level">
                        <div class="icons">
                            <img src="/wp-content/themes/and/assets/imgs/gold-level.svg" alt="Gold Membership Level"/>
                            <p>Gold</p>
                        </div>
                        <p class="price">'. $member_pricing['gold_menber_pricing'] .'</p>
                    </div>
                </div>
                '.$tableHtml.'
            </div>
        </section>';
endif;