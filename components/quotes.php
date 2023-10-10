<?php 

if( get_row_layout() == 'quotes' ):
    $quotes = get_sub_field('quotes');
    $contentPosition = '';
    $html = '';
    foreach($quotes as $quote) {
        $html .= '<div class="quote-area">
                <div class="col-8 quote">
                        '.$quote['text'].'
                   </div>
                   <div class="shape">
                    <img src="'.$quote['image']['url'].'" alt="The quotes" />
                </div>
                </div>';
    }
    echo '<section class="quotes '.$contentPosition.'">
            <div class="container">
                <div class="row">
                    '.$html.'
                </div>
            </div>
        </section>';
endif;
