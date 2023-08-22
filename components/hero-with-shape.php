<?php

if( get_row_layout() == 'hero_with_shape' ):
    $image = get_sub_field('image');
    $shape = get_sub_field('shape');
    echo '<section class="hero with-shape">
            <div class="image" style="background-image:url('.$image['url'].');">
                <img src="'.$image['url'].'" alt="'.$image['alt'].'" />
            </div>
            <div class="shape">
                <img src="'.$shape['url'].'" alt="'.$shape['alt'].'" />
            </div>
        </section>';
endif;