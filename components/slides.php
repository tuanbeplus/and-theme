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
    <section class="slides">
        <div class="<?php if($slide_template == 'container') echo 'container'; ?>">
            <div id="slides-wrapper-<?php echo $wrapper_id; ?>" class="slides-wrapper owl-carousel">
                <?php foreach($list_items as $item): 
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
                                <?php if($item['cta_link']): ?>
                                    <?php $cta_id = rand(1, 999); ?>
                                    <a id="cta-<?php echo $cta_id; ?>" class="cta" role="button" href="<?php echo $item['cta_link']; ?>">
                                        <?php echo $item['cta_text']; ?>
                                    </a>
                                    <style>
                                        #cta-<?php echo $cta_id; ?> {
                                            background-color: <?php echo $item['background_color']; ?>;
                                        }
                                        #cta-<?php echo $cta_id; ?>:hover {
                                            color: <?php echo $item['background_color']; ?>;
                                            background-color: #fff;
                                        }
                                    </style>
                                <?php endif; ?>
                            </div>
                            
                            <div class="slide-img">
                                <?php if($item['image']){ $img_url = $item['image']; }?>
                                <img src="<?php echo $img_url; ?>" alt="Slide Image">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <script>
        jQuery(document).ready( function() {
			$(document).ready(function(){
                $("#slides-wrapper-<?php echo $wrapper_id; ?>").owlCarousel({
                    loop: true,
                    margin: 0,
                    items: 1,
                    nav: <?php echo $navigation; ?>,
                    dots: <?php echo $dots; ?>,
                    autoplay: <?php echo $autoplay; ?>,
                    autoplaySpeed: <?php echo $autoplay_speed; ?>,
                    autoplayTimeout: 3000,
                    autoplayHoverPause: true,
                    navText: [
                        '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M9.4 233.4c-12.5 12.5-12.5 32.8 0 45.3l192 192c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256 246.6 86.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0l-192 192z"/></svg>', 
                        '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M310.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-192 192c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L242.7 256 73.4 86.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l192 192z"/></svg>'
                    ],
                });
                $("#slides-wrapper-<?php echo $wrapper_id; ?> .owl-dots")
                    .addClass('container <?php if($slide_template == 'container') echo 'template-container'; ?>');
            });
		});
    </script>
<?php endif; ?>