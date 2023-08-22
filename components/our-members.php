<?php 

if( get_row_layout() == 'our_members_data' ):

    getOurMembers('Silver');
    echo '<section class="our-members-data col-12">
            <div class="container">
                <div class="row">
                    <div class="col-md-8 tabs">
                        <div class="accordion">
                            <div class="accordion-title">
                                <h1>Our Platinum members</h1>
                                <div class="chevron"></div>
                            </div>
                            <div class="accordion-content">
                                '.getOurMembers('Platinum').'
                            </div>
                            <div class="accordion-title">
                                <h1>Our Gold members</h1>
                                <div class="chevron"></div>
                            </div>
                            <div class="accordion-content">
                                '.getOurMembers('Gold').'
                            </div>
                            <div class="accordion-title">
                                <h1>Our Silver members</h1>
                                <div class="chevron"></div>
                            </div>
                            <div class="accordion-content">
                                '.getOurMembers('Silver').'
                            </div>
                            <div class="accordion-title">
                                <h1>Our Bronze members</h1>
                                <div class="chevron"></div>
                            </div>
                            <div class="accordion-content">
                                '.getOurMembers('Bronze').'
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 image">
                        <img src="'.get_the_post_thumbnail_url().'" alt="Our members" />
                    </div>
                </div>
            </div>
        </section>';
endif;
