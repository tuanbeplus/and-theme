<?php 

if( get_row_layout() == 'team_members' ):
    $width = get_sub_field('width');
    $members = get_sub_field('members');

    if($width == 'quarter') {
        $teamSize = 'col-xl-9';
        $colSize = 'col-md-4';
    } else {
        $teamSize = 'col-xl-12';
        $colSize = 'col-md-3';
    }

    $memberHtml = '';

    foreach($members as $member) {
        $memberHtml .= '<div class="col-12 heading"><h2>'.$member['heading'].'</h2></div>';
        $memberHtml .= '<div class="col-12 area">
                            <div class="row">';

            foreach($member['member'] as $profile):

                $memberNumber = rand();

                $memberHtml .= '<div class="'.$colSize.' tile">
                                    <div class="inner">
                                        <div class="image" style="background-image: url('.$profile['image']['url'].');">
                                            <img src="'.$profile['image']['url'].'" alt="'.$profile['image']['alt'].'"/>
                                        </div>
                                        <div class="details">
                                            <div class="flexible">
                                                <div class="name">
                                                    <p>'.$profile['name'].'</p>
                                                </div>
                                                <div class="position">
                                                    <p>'.$profile['position'].'</p>
                                                </div>
                                            </div>
                                            <div class="cta bio">
                                                <button data-fancybox="dialog" title="See full bio" aria-label="See full bio" data-src="#dialog-content-'.$memberNumber.'" class="cta">See full bio</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Dialog Contents -->
                                <div id="dialog-content-'.$memberNumber.'" class="lightbox" style="display:none;max-width:720px;">
                                    <img src="'.$profile['image']['url'].'" alt="'.$profile['image']['alt'].'"/>
                                    <div class="name">
                                        <p>'.$profile['name'].'</p>
                                    </div>
                                    <div class="position">
                                        <p>'.$profile['position'].'</p>
                                    </div>  
                                    <div class="description">
                                        '.$profile['description'].'
                                    </div>
                                    <div class="cta bio">
                                        <button class="cta btn-close-lightbox" title="Close bio" arial-label="Close bio">Close bio</button>
                                    </div>
                                </div>';

            endforeach;

        $memberHtml .= '
                            </div>
                        </div>';
    }

    echo '<section class="col-12 col-lg-12 '.$teamSize.' team">
            <div class="container">
                <div class="row">
                    '.$memberHtml.'
                </div>
            </div>
        </section>';

endif;