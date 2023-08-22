<?php

if( get_row_layout() == 'page_tiles' ):
    $tiles = get_sub_field('tiles');
    $tilesHtml = '';
    $i = 0;
    $tileCount = count($tiles);

    if($tileCount % 2 == 0) {
        $class = 'col-md-6 col-lg-6';
        $sidebarHtml = '';
        $cardsClass = 'col-12 cards tiles';
        $sectionClass = 'page-tiles'; 
    } else {
        $class = 'col-md-6 col-lg-4';
        $sidebarHtml = '';
        $cardsClass = 'col-12 cards tiles';
        $sectionClass = 'page-tiles'; 
    }
    foreach($tiles as $tile) {
        $i++;
        if(isset($tile['link'])) {
            $link = $tile['link'];
        } else {
            $link = '';
        }
        $tilesHtml .= '<div class="'.$class.' the-card">
                            <div class="inner">
                                <img src="'.$tile['image']['url'].'" alt="'.$tile['image']['alt'].'"/>
                                <div class="detail" data-mh="my-group">
                                    '.$tile['content'].'
                                </div>
                                <a href="'.$link.'" class="btn circle">
                                    <span class="material-icons">arrow_forward</span>
                                </a>
                            </div>
                       </div>';
    }
    
    echo '</div>';

    echo '<section class="col-12 '.$sectionClass.'">
            <div class="container">
                <div class="row ">
                    '.$sidebarHtml.'
                    <div class="'.$cardsClass.'">
                        <div class="row">
                            '.$tilesHtml.'
                        </div>
                    </div>
                </div>
            </div>
        </section>';
    echo '<div class="col-md-12 col-lg-9 main-area">';
endif;