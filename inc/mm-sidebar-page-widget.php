<?php

class MMSidebarPageWidget extends WP_Widget {

    function __construct() {
        parent::__construct(
                'mm_sidebar_page_widget', // Base ID
                __('MM Sidebar Page Widget', 'text_domain'), // Name
                array('description' => __('MM Sidebar Page Widget', 'text_domain')) // Args
        );
    }

    public function widget($args, $instance) {
        $select_pages = !empty($instance['select_pages']) ? $instance['select_pages'] : '';
        $excerpt_sidebar = !empty($instance['excerpt_sidebar']) ? $instance['excerpt_sidebar'] : '';
        $hide_feature_img = !empty($instance['hide_feature_img']) ? $instance['hide_feature_img'] : '';

        $args = array(
            'name' => $select_pages,
            'post_type' => 'page',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        $get_posts = get_posts($args);

        $featured_image = wp_get_attachment_url(get_post_thumbnail_id($get_posts[0]->ID));

        $get_title_sidebar = explode("_", $excerpt_sidebar);

        $title_sidebar = "title_sidebar_" . $get_title_sidebar[2];

        $get_title = ($excerpt_sidebar != '') ? get_post_meta($get_posts[0]->ID, $title_sidebar, true) : '';
        $get_excerpt = ($excerpt_sidebar != '') ? get_post_meta($get_posts[0]->ID, $excerpt_sidebar, true) : '';

        //echo $args['before_widget'];
        ?>
        <div id="mm_sidebar_widget_wrap">
            <div class="mm_sidebar_widget_inner">
                <?php if ($featured_image && $hide_feature_img != "Hide") { ?>
                    <a class="mm_sidebar_widget_featured_image" href="<?php echo get_permalink($get_posts[0]->ID); ?>">
                        <img src="<?php echo $featured_image; ?>" width="100" height="auto" />						
                    </a>
                    <div class="mm_sidebar_widget_title" style="width: calc(100% - 120px);"><?php echo $get_title; ?></div>
                <?php } else { ?> 
                    <div class="mm_sidebar_widget_title" style="width: 100%;"><?php echo html_entity_decode($get_title); ?></div>
                <?php } ?>
            </div>
            <div class="mm_sidebar_widget_excerpt"><?php echo html_entity_decode($get_excerpt); ?></div>
        </div>
        <?php
        //echo $args['after_widget'];
    }

    public function form($instance) {
        $select_pages = !empty($instance['select_pages']) ? $instance['select_pages'] : '';
        $excerpt_sidebar = !empty($instance['excerpt_sidebar']) ? $instance['excerpt_sidebar'] : '';
        $hide_feature_img = !empty($instance['hide_feature_img']) ? $instance['hide_feature_img'] : '';

        $args = array(
            'name' => $select_pages,
            'post_type' => 'page',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        $get_posts = get_posts($args);
        $get_excerpt1 = get_post_meta($get_posts[0]->ID, 'excerp_sidebar_one', true);
        $get_excerpt2 = get_post_meta($get_posts[0]->ID, 'excerp_sidebar_two', true);
        $get_excerpt3 = get_post_meta($get_posts[0]->ID, 'excerp_sidebar_three', true);
        $get_excerpt4 = get_post_meta($get_posts[0]->ID, 'excerp_sidebar_four', true);
        $get_excerpt5 = get_post_meta($get_posts[0]->ID, 'excerp_sidebar_five', true);
        ?>
        <p>
            <select class="select_pages_show" name="<?php echo $this->get_field_name('select_pages'); ?>" excerpt_name="<?php echo $this->get_field_name('excerpt_sidebar'); ?>">
                <?php $this->selectPageShow($select_pages); ?>
            </select>
        </p>
        <div class="excerpt_sidebar_show">
            <select name="<?php echo $this->get_field_name('excerpt_sidebar'); ?>" >
                <?php if ($get_excerpt1 != '') { ?>
                    <option <?php echo ($excerpt_sidebar == 'excerp_sidebar_one') ? 'selected="selected"' : ''; ?> value="excerp_sidebar_one">Excerpt Sidebar 1</option>
                    <?php
                }
                if ($get_excerpt2 != '') {
                    ?>
                    <option <?php echo ($excerpt_sidebar == 'excerp_sidebar_two') ? 'selected="selected"' : ''; ?> value="excerp_sidebar_two">Excerpt Sidebar 2</option>
                    <?php
                }
                if ($get_excerpt3 != '') {
                    ?>
                    <option <?php echo ($excerpt_sidebar == 'excerp_sidebar_three') ? 'selected="selected"' : ''; ?> value="excerp_sidebar_three">Excerpt Sidebar 3</option>
                    <?php
                }
                if ($get_excerpt4 != '') {
                    ?>
                    <option <?php echo ($excerpt_sidebar == 'excerp_sidebar_four') ? 'selected="selected"' : ''; ?> value="excerp_sidebar_four">Excerpt Sidebar 4</option>
                    <?php
                }
                if ($get_excerpt5 != '') {
                    ?>
                    <option <?php echo ($excerpt_sidebar == 'excerp_sidebar_five') ? 'selected="selected"' : ''; ?> value="excerp_sidebar_five">Excerpt Sidebar 5</option>
                <?php } ?>
            </select>
        </div>
        <br>

        <div class="hide_feature_img">
            <label for="<?php echo $this->get_field_id('hide_feature_img'); ?>"><?php _e('Hide Feature Image:'); ?></label> 
            <input <?php echo ($hide_feature_img == "Hide") ? 'checked="checked"' : ''; ?>  class="hide_feature_img" id="<?php echo $this->get_field_id('hide_feature_img'); ?>" name="<?php echo $this->get_field_name('hide_feature_img'); ?>" type="checkbox" value="Hide">
        </div>
        <br>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery(".select_pages_show").change(function () {
                    var page_slug = jQuery(this).val();
                    var excerpt_name = jQuery(this).attr("excerpt_name");
                    jQuery.ajax({
                        type: "POST",
                        url: mmAjax.ajaxurl,
                        data: {
                            "action": "getExcerptSidebar",
                            "page_slug": page_slug,
                            "excerpt_name": excerpt_name
                        },
                        beforeSend: function () {
                            //jQuery('#submit-client-site').val('Saving...');
                        },
                        success: function (data) {

                            jQuery('.excerpt_sidebar_show').html(data);
                        }
                    });
                });
            });
        </script>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['select_pages'] = (!empty($new_instance['select_pages']) ) ? strip_tags($new_instance['select_pages']) : '';
        $instance['excerpt_sidebar'] = (!empty($new_instance['excerpt_sidebar']) ) ? strip_tags($new_instance['excerpt_sidebar']) : '';
        $instance['hide_feature_img'] = (!empty($new_instance['hide_feature_img']) ) ? strip_tags($new_instance['hide_feature_img']) : '';

        return $instance;
    }

    function selectPageShow($selected) {
        $args = array(
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'page',
            'post_status' => 'publish',
        );
        $posts_array = get_posts($args);
        $html = '';
        foreach ($posts_array as $post) {
            $select = ($selected != '' && $selected == $post->post_name) ? "selected = 'selected'" : '';
            $html .= '<option ' . $select . ' value="' . $post->post_name . '">' . $post->post_title . '</option>';
        }
        echo $html;
    }

}
?>