<?php

if( get_row_layout() == 'text_blocks' ):

    $grid_blocks = get_sub_field('grid_blocks');
    $count = 1;
    ?> 
    <section class="text-blocks">
        <div class="container">
            <div class="blocks-wrapper">
                <?php foreach ($grid_blocks as $block): ?>
                    <style>
                        .block-item-<?php echo $count; ?> {
                            background-color: <?php echo $block['background_color']; ?>;
                            color: <?php echo $block['text_color']; ?>;
                        }
                        .block-item-<?php echo $count; ?> .label,
                        .block-item-<?php echo $count; ?> .headline,
                        .block-item-<?php echo $count; ?> .description,
                        .block-item-<?php echo $count; ?> .block-cta {
                            color: <?php echo $block['text_color']; ?>;
                        }
                        .block-item-<?php echo $count; ?> .block-cta {
                            border: 2px solid <?php echo $block['text_color']; ?>;
                        }
                        .block-item-<?php echo $count; ?> .block-cta:hover {
                            color: <?php echo $block['background_color']; ?>;
                            background-color: <?php echo $block['text_color']; ?>;
                        }
                    </style>
                    <div class="block block-item-<?php echo $count; ?>">
                        <div>
                            <?php if ($block['label']): ?>
                                <p class="label"><?php echo $block['label']; ?></p>
                            <?php endif; ?>
                            <?php if ($block['headline']): ?>
                                <h3 class="headline"><?php echo $block['headline']; ?></h3>
                            <?php endif; ?>
                        </div>

                        <div>
                            <?php if ($block['description']): ?>
                                <p class="description"><?php echo $block['description']; ?></p>
                            <?php endif; ?>
                            <?php if ($block['cta_link']): ?>
                                <a class="block-cta" href="<?php echo $block['cta_link']; ?>"><?php echo $block['cta_text']; ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php 
                    $count++;
                    endforeach; 
                ?>
            </div>
        </div>
    </section>

<?php endif; ?>