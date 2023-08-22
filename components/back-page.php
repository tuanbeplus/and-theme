<?php

if( get_row_layout() == 'back_page' ):
    $link = get_sub_field('link');

    echo '<section class="back-page">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-6 text-content-block">
                        <a href="'.$link['url'].'" class="btn circle dark-red">
                            <span class="material-icons">arrow_back</span>
                            <span class="text">'.$link['title'].'</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>';
endif;