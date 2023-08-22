<?php

if( get_row_layout() == 'form_search' ):
    echo '<section class="tfa-form-search">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        '.get_template_part( 'searchform-autocomplete').'
                    </div>
                </div>
            </div>
        </section>';
endif;
