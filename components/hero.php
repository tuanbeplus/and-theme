<?php

if( get_row_layout() == 'hero' ):
    $title = get_sub_field('title');
    $image = get_sub_field('image');
    echo '<section class="hero">
            <div class="container">
                <div class="row">
                    <div class="col-11 col-md-7 title">
                        <h1>'.$title.'</h1>
                    </div>
                </div>
            </div>
            <div class="image" style="background-image:url('.$image['url'].');">
                <img src="'.$image['url'].'" alt="'.$img['alt'].'" />
            </div>
        </section>';
endif;