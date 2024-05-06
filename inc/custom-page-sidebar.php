<?php
/**
 * Add Custom Menu Sidebar meta box
 * 
 */
function init_meta_boxes_admin_page() {
    add_meta_box(
        'custom_page_sidebar',   
        'Custom Menu Sidebar',  
        'custom_page_sidebar_meta_box_content', 
        array(
            'page',
            'about-us',
            'how_we_can_help_you',
            'resources',
            'new_campaign',
            'news_and_events',
            'join-us',
            'students-jobseekers',
        ),
        'normal',  
    );
}
add_action('add_meta_boxes', 'init_meta_boxes_admin_page');

/**
 * Get all Heading 2 in post type contents
 * 
 * @param $post_id  Post ID
 * @return array h2 array
 * 
 */
function get_all_h2_tags_page_content($post_id) {
    $h2_tags_arr = array();
    // Create a DOMDocument
    $dom = new DOMDocument;
    // get page builder ACF
    $page_builder = get_field('page_builder', $post_id);
    $contents = '';

    if (isset($page_builder) && !empty($page_builder)) {
        // Loop page contents
        foreach ($page_builder as $row) {
            $contents .= $row['content'];
        }
    }
    
    if (isset($contents) && !empty($contents)) {
        // Load HTML content
        $dom->loadHTML($contents);
        // Find all <h2> tags
        $h2_tags = $dom->getElementsByTagName('h2');
    }

    if (isset($h2_tags) && !empty($h2_tags)) {
        $index = 1;
        foreach ($h2_tags as $h2_tag) {
            if (!empty($h2_tag->textContent)) {
                $h2_tags_arr[$index] = $h2_tag->textContent;
                $index++;
            }
        }
    }
    return $h2_tags_arr;
}

/**
 * Custom Menu Sidebar meta box content
 * 
 */
function custom_page_sidebar_meta_box_content() {

    global $post;
    $post_id = $post->ID;
    
    // Get all h2 tags
    $h2_tags = get_all_h2_tags_page_content($post_id);

    // Get meta data
    $custom_page_sidebar = get_post_meta($post_id, 'custom_page_sidebar', true);
    $menu_title = isset($custom_page_sidebar['menu_title']) ? $custom_page_sidebar['menu_title'] : '';
    $is_custom_menu = isset($custom_page_sidebar['is_custom_menu']) ? $custom_page_sidebar['is_custom_menu'] : '0';
    $menu_items = isset($custom_page_sidebar['menu_items']) ? $custom_page_sidebar['menu_items'] : array();
    ?>
    <div class="custom-page-sidebar">
        <table class="menu-option">
            <tr>
                <td>
                    <p>Choose Menu Sidebar:</p>
                    <p class="gr-radio-btn">
                        <label for="default-menu">
                            <input id="default-menu" 
                                type="radio" 
                                name="custom_page_sidebar[is_custom_menu]"  
                                value="0"
                                <?php if ($is_custom_menu == false) echo 'checked'; ?>>
                            Default Menu
                        </label>
                        <label for="custom-menu">
                            <input id="custom-menu" 
                                type="radio" 
                                name="custom_page_sidebar[is_custom_menu]" 
                                value="1"
                                <?php if ($is_custom_menu == true) echo 'checked'; ?>>
                            Custom Menu
                        </label>
                    </p>
                </td>
                <td>
                    <p>Menu Title:</p>
                    <input type="text" name="custom_page_sidebar[menu_title]" value="<?php echo $menu_title; ?>">
                </td>
            </tr>
        </table>
        <?php if (isset($h2_tags) && !empty($h2_tags)): ?>
        <table class="table-menu">
            <tbody>
                <tr>
                    <th>Default Menu items</th>
                    <th>Custom Menu items</th>
                </tr>
                <!-- Loop through each <h2> tag and output its content -->
                <?php foreach ($h2_tags as $index => $h2_tag): ?>
                <tr>
                    <td>
                        <?php echo $h2_tag; ?>
                        <input type="hidden" 
                            name="custom_page_sidebar[menu_items][<?php echo $index; ?>][h2_tag]"
                            value="<?php echo $h2_tag; ?>">
                    </td>
                    <td>
                        <textarea name="custom_page_sidebar[menu_items][<?php echo $index; ?>][custom_item]"
                            cols="40" rows="2"><?php 
                            if (!empty($menu_items)) {
                                foreach ($menu_items as $item) {
                                    if (isset($item['h2_tag'])) {
                                        if ($item['h2_tag'] == $h2_tag) {
                                            echo $item['custom_item'];
                                            continue;
                                        }
                                    }
                                }
                            }
                        ?></textarea>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p class="no-found-mess">No menu was found.</p>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Save the Custom Menu Sidebar meta box data
 * 
 */
function save_custom_menu_sidebar_meta_box_data($post_id) {

    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Get all h2 tags
    $h2_tags = get_all_h2_tags_page_content($post_id) ?? array();
    $custom_page_sidebar = $_POST['custom_page_sidebar'] ?? array();

    if (isset($custom_page_sidebar) && !empty($custom_page_sidebar)) {
        // Update post meta
        update_post_meta($post_id, 'custom_page_sidebar', $custom_page_sidebar);
    }
}
add_action('save_post', 'save_custom_menu_sidebar_meta_box_data');