<?php 

if( get_row_layout() == 'icons_list' ):
    $icons = get_sub_field('icons');
    ?>
    <section class="icons-list">
        <div class="container">
            <div class="icons-wrapper">
                <ul class="icons">
                    <?php foreach ($icons as $item): ?>
                        <li class="icon-item <?php echo $item['icon_options']['shape']; ?>">
                            <div class="_icon" 
                                style="background-color: <?php echo $item['icon_options']['background_color']; ?>;
                                        color: <?php echo $item['icon_options']['icon_color']; ?>;">
                                <span>
                                    <?php echo $item['icon_options']['icon']; ?>
                                </span>
                            </div>
                            <div class="_cta">
                                <a href="<?php echo $item['cta_link']; ?>">
                                    <?php echo $item['cta_text']; ?>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>

<?php endif; ?>

