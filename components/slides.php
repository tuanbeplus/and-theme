<?php

if( get_row_layout() == 'slides' ):
    $slide_template = get_sub_field('templates');
    $navigation = (get_sub_field('navigation')) ? 'true' : 'false';
    $dots = (get_sub_field('dots')) ? 'true' : 'false';
    $autoplay = (get_sub_field('autoplay')) ? 'true' : 'false';
    $autoplay_speed = (get_sub_field('autoplay_speed')) ? get_sub_field('autoplay_speed') : 1000;
    $list_items = get_sub_field('list_items');
    $img_url = AND_IMG_URI .'footer-bg.jpg';
    $wrapper_id = rand(1, 999);
    ?>
    <section class="slides slides-ss">
        <div class="<?php if($slide_template == 'container') echo 'container slides-box'; ?>">
            <div id="slides-wrapper-<?php echo $wrapper_id; ?>" class="slides-wrapper owl-carousel">
                <?php foreach($list_items as $key=>$item):
                    $item = $item['slide_item'];
                    ?>
                    <div class="item slide-item"
                        style="<?php if($item['background_color']) echo 'background-color:'.$item['background_color'].';'; ?>">
                        <div class="container <?php if($slide_template == 'container') echo 'template-container'; ?>">
                            <div class="slide-content">
                                <?php if($item['headline']): ?>
                                    <h2 class="headline"><?php echo $item['headline']; ?></h2>
                                <?php endif; ?>
                                <?php if($item['description']): ?>
                                    <p class="description"><?php echo $item['description']; ?></p>
                                <?php endif; ?>
                                <?php if($item['cta_link']) { ?>
                                    <?php
                                    $cta_id = rand(1, 999);
                                    ?>
                                    <div class="cta-field">
                                        <a class="cta cta-<?php echo $cta_id; ?> >"
                                        role="button" href="<?php echo $item['cta_link']; ?>">
                                            <?php echo $item['cta_text']; ?>
                                        </a>
                                        <style>
                                            .slides-wrapper .slide-content .cta.cta-<?php echo $cta_id; ?> {
                                                background-color: <?php echo $item['background_color']; ?>;
                                            }
                                            .slides-wrapper .slide-content .cta.cta-<?php echo $cta_id; ?>:hover {
                                                color: <?php echo $item['background_color']; ?>;
                                                background-color: #fff;
                                            }
                                        </style>
                                    </div>
                                <?php } else {
                                    ?>
                                    <a class="cta and-next-sl hidden-link" href="javascript:;"></a>
                                    <?php
                                } ?>
                            </div>

                            <div class="slide-img">
                                <?php if($item['image']['url']){ $img_url = $item['image']['url']; }?>
                                <img src="<?php echo $img_url; ?>" alt="<?php echo $item['image']['alt']; ?>">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <script>
        jQuery(document).ready(function(){
            var $slides_<?php echo $wrapper_id; ?> = jQuery("#slides-wrapper-<?php echo $wrapper_id; ?>").owlCarousel({
                loop: false,
                margin: 0,
                items: 1,
                nav: <?php echo $navigation; ?>,
                dots: <?php echo $dots; ?>,
                autoplay: <?php echo $autoplay; ?>,
                autoplaySpeed: <?php echo $autoplay_speed; ?>,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                navText: [
                    '<span aria-label="Previous Slide"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg></span>',
			        '<span aria-label="Next Slide"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg></span>',
                ],
            });
            jQuery("#slides-wrapper-<?php echo $wrapper_id; ?> .owl-dots")
                .addClass('container <?php if($slide_template == 'container') echo 'template-container'; ?>');

            jQuery("#slides-wrapper-<?php echo $wrapper_id; ?> .owl-dots").children('.owl-dot').attr('tabindex', -1).attr('role', "button")
        });
    </script>
<?php endif; ?>
