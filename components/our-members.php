<?php 

if( get_row_layout() == 'our_members_data' ):
    // Member types array
    $member_types = array('Platinum', 'Gold', 'Silver', 'Bronze');
    ?>
    <section class="our-members-data col-12">
        <div class="container">
            <div class="row">
                <div class="col-md-8 tabs">
                    <div class="accordion">
                    <?php foreach ($member_types as $type): ?>
                        <button class="accordion-title" aria-expanded="false" aria-controls="<?php echo $type.'-members-list'; ?>">
                            <h2 class="heading">Our <?php echo $type ?> members</h2>
                            <div class="chevron"></div>
                        </button>
                        <div id="<?php echo $type.'-members-list'; ?>" class="accordion-content">
                            <?php echo getOurMembers($type); ?>
                        </div>
                    <?php endforeach; ?>
                    </div>
                </div>
                <div class="col-md-4 image">
                    <img src="<?php echo get_the_post_thumbnail_url() ?>" alt="Our members" />
                </div>
            </div>
        </div>
    </section>
    <?php
endif;
