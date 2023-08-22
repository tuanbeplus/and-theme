<?php

if( get_row_layout() == 'sf_data_feeds' ):

    $feed = get_sub_field('feed');

    if($feed == 'all') {
        $feedWord = 'Events';

        $events = getEvents('all');

        echo '<section class="page-tiles sf-data">
        <div class="container">
            <div class="row ">
                <div class="col-12 cards tiles">
                    <div class="row">
                        <div class="col-12 title">
                            <h2>Upcoming '.$feedWord.'</h2>
                        </div>
                    </div>
                    <div class="row">
                        '.$events.'
                    </div>
                </div>
            </div>
    </section>';
    }
    if($feed == 'roundtables') {
        $feedWord = 'Roundtables';
        
        $events = getEvents('roundtables');

        echo '<section class="page-tiles sf-data">
        <div class="container">
            <div class="row ">
                <div class="col-12 cards tiles">
                    <div class="row">
                        <div class="col-12 title">
                            <h2>Upcoming '.$feedWord.'</h2>
                        </div>
                    </div>
                    <div class="row">
                        '.$events.'
                    </div>
                </div>
            </div>
    </section>';
    }
    if($feed == 'webinars') {
        $feedWord = 'Webinars';
        $events = getEvents('webinars');
        echo '<section class="page-tiles sf-data">
        <div class="container">
            <div class="row ">
                <div class="col-12 cards tiles">
                    <div class="row">
                        <div class="col-12 title">
                            <h2>Upcoming '.$feedWord.'</h2>
                        </div>
                    </div>
                    <div class="row">
                    '.$events.'
                    </div>
                </div>
            </div>
    </section>';
    }

    

endif;