<?php

if( get_row_layout() == 'form_embed' ):
    $formEmbed = get_sub_field('form');
    echo '<section class="tfa-form">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        '.$formEmbed.'
                    </div>
                </div>
            </div>
        </section>';
endif;