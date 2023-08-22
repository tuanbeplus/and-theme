<?php 

if( get_row_layout() == 'sources_horizontal' ):
    $leftSourcesList = get_sub_field('left_sources_list');
    $middleSourcesList = get_sub_field('middle_sources_list');
    $rightSourcesList = get_sub_field('right_sources_list');

    echo '</div></div><section class="col-12 sources source-list">
            <div class="container">
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-4 text-content-block">
                        <div class="row">
                            <div class="col-12 text-content-block">
                                '.$leftSourcesList.'
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4 text-content-block">
                        <div class="row">
                            <div class="col-12 text-content-block">
                                '.$middleSourcesList.'
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4 text-content-block">
                        <div class="row">
                            <div class="col-12 text-content-block">
                                '.$rightSourcesList.'
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div class="container"><div class="row">';

endif;