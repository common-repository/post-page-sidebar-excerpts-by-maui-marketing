<?php
class MMSidebarPostWidget extends WP_Widget {

    function __construct() {
        parent::__construct(
                'mm_sidebar_post_widget', // Base ID
                __('MM Sidebar Post Widget', 'text_domain'), // Name
                array('description' => __('MM Sidebar Post Widget', 'text_domain')) // Args
        );
    }

    public function widget($args, $instance) {
        $select_posts = !empty($instance['select_posts']) ? $instance['select_posts'] : '';
        $excerpt_post_sidebar = !empty($instance['excerpt_post_sidebar']) ? $instance['excerpt_post_sidebar'] : '';
        $hide_post_feature_img = !empty($instance['hide_post_feature_img']) ? $instance['hide_post_feature_img'] : '';

        $args = array(
            'name' => $select_posts,
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        $get_posts = get_posts($args);

        $featured_image = wp_get_attachment_url(get_post_thumbnail_id($get_posts[0]->ID));

        $get_title_sidebar = explode("_", $excerpt_post_sidebar);

        $title_post_sidebar = "title_sidebar_" . $get_title_sidebar[2];

        $get_title = ($excerpt_post_sidebar != '') ? get_post_meta($get_posts[0]->ID, $title_post_sidebar, true) : '';
        $get_excerpt = ($excerpt_post_sidebar != '') ? get_post_meta($get_posts[0]->ID, $excerpt_post_sidebar, true) : '';

        //echo $args['before_widget'];
        ?>
        <div id="mm_sidebar_post_widget_wrap">
            <div class="mm_sidebar_post_widget_inner">
                <?php if ($featured_image && $hide_post_feature_img != "Hide") { ?>
                    <a class="mm_sidebar_post_widget_featured_image" href="<?php echo get_permalink($get_posts[0]->ID); ?>">
                        <img src="<?php echo $featured_image; ?>" width="100" height="auto" />						
                    </a>
                    <div class="mm_sidebar_post_widget_title" style="width: calc(100% - 120px);"><?php echo $get_title; ?></div>
                <?php } else { ?> 
                    <div class="mm_sidebar_post_widget_title" style="width: 100%;"><?php echo html_entity_decode($get_title); ?></div>
                <?php } ?>
            </div>
            <div class="mm_sidebar_post_widget_excerpt"><?php echo html_entity_decode($get_excerpt); ?></div>
        </div>
        <?php
        //echo $args['after_widget'];
    }

    public function form($instance) {
        $select_posts = !empty($instance['select_posts']) ? $instance['select_posts'] : '';
        $excerpt_post_sidebar = !empty($instance['excerpt_post_sidebar']) ? $instance['excerpt_post_sidebar'] : '';
        $hide_post_feature_img = !empty($instance['hide_post_feature_img']) ? $instance['hide_post_feature_img'] : '';

        $args = array(
            'name' => $select_posts,
            'post_type' => 'post',
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
            <select class="select_posts_show" name="<?php echo $this->get_field_name('select_posts'); ?>" excerpt_name="<?php echo $this->get_field_name('excerpt_post_sidebar'); ?>">
                <?php $this->selectPostShow($select_posts); ?>
            </select>
        </p>
        <div class="excerpt_post_sidebar_show">
            <select name="<?php echo $this->get_field_name('excerpt_post_sidebar'); ?>" >
                <?php if ($get_excerpt1 != '') { ?>
                    <option <?php echo ($excerpt_post_sidebar == 'excerp_sidebar_one') ? 'selected="selected"' : ''; ?> value="excerp_sidebar_one">Excerpt Sidebar 1</option>
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

        <div class="hide_post_feature_img">
            <label for="<?php echo $this->get_field_id('hide_post_feature_img'); ?>"><?php _e('Hide Feature Image:'); ?></label> 
            <input <?php echo ($hide_post_feature_img == "Hide") ? 'checked="checked"' : ''; ?>  class="hide_post_feature_img" id="<?php echo $this->get_field_id('hide_post_feature_img'); ?>" name="<?php echo $this->get_field_name('hide_post_feature_img'); ?>" type="checkbox" value="Hide">
        </div>
        <br>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery(".select_posts_show").change(function () {
                    var post_slug = jQuery(this).val();
                    var excerpt_name = jQuery(this).attr("excerpt_name");
                    jQuery.ajax({
                        type: "POST",
                        url: mmAjax.ajaxurl,
                        data: {
                            "action": "getExcerptPostSidebar",
                            "page_slug": post_slug,
                            "excerpt_name": excerpt_name
                        },
                        beforeSend: function () {
                            //jQuery('#submit-client-site').val('Saving...');
                        },
                        success: function (data) {

                            jQuery('.excerpt_post_sidebar_show').html(data);
                        }
                    });
                });
            });
        </script>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['select_posts'] = (!empty($new_instance['select_posts']) ) ? strip_tags($new_instance['select_posts']) : '';
        $instance['excerpt_post_sidebar'] = (!empty($new_instance['excerpt_post_sidebar']) ) ? strip_tags($new_instance['excerpt_post_sidebar']) : '';
        $instance['hide_post_feature_img'] = (!empty($new_instance['hide_post_feature_img']) ) ? strip_tags($new_instance['hide_post_feature_img']) : '';

        return $instance;
    }

    function selectPostShow($selected) {
        $args = array(
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
            'post_type' => 'post',
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