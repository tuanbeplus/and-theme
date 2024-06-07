
<?php

if( get_row_layout() == 'dashboard_resources' ):
    
    $args = array(
        'post_type' => 'resources',
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    );

    $resources = get_posts($args);
    $resources_heading = get_sub_field('resources_heading');
    $resourcesHtml = '';

    foreach($resources as $resource) {
        $resourcesHtml .= '<li>
            <a href="'.get_permalink($resource->ID).'">
                <span class="material-icons">arrow_forward</span>
                '.get_the_title($resource->ID).'
            </a>
        </li>';
    } 
    ?>
        <section class="dashboard resources-widget">
            <div class="container">
                <div class="inner">
                    <div class="row">
                        <div class="col-12 header">
                            <div class="inside">
                                <div class="row">
                                    <div class="col-md-12 title">
                                        <img src="/wp-content/themes/and-theme/assets/imgs/resources.svg" alt="Resources" />
                                        <h2><?php echo $resources_heading; ?></h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 the-details">
                            <ul>
                                <?php echo $resourcesHtml; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php
endif;
