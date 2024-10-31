<?php
/*
  Plugin Name: Post & Page Sidebar Excerpts by Maui Marketing
  Plugin URI: http://mauimarketing.com/
  Description: Increase site engagement using customized post and page excerpts in the sidebar.  
  Version: 1.0.1
  Author: Maui Marketing
  Author URI: http://mauimarketing.com/
  Text domain: mm_sidebar
 */

define('PLUGIN_URI', plugin_dir_url(__FILE__));
define('PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_NAME', plugin_basename(dirname(__FILE__)));

/** add sidebar widgets * */
include PLUGIN_PATH. '/inc/mm-sidebar-page-widget.php';
include PLUGIN_PATH. '/inc/mm-sidebar-post-widget.php';

if (!class_exists('MM_Sidebar')) {

    class MM_Sidebar {

        function __construct() {
            add_action('plugins_loaded', array($this, 'mm_sidebar_load_textdomain'));
            add_action('wp_enqueue_scripts', array($this, 'mm_sidebar_load_scripts'));
            add_action('admin_enqueue_scripts', array($this, 'mm_sidebar_admin_load_scripts'));
            add_action('widgets_init', array($this, 'register_mm_sidebar_page_widget'));
            add_action('widgets_init', array($this, 'register_mm_sidebar_post_widget'));
            add_action('add_meta_boxes', array($this, 'mm_sidebar_widget_meta_box'));
            add_action('save_post', array($this, 'mm_sidebar_widget_meta_box_save'));
            add_action("wp_ajax_getExcerptSidebar", array($this, 'getExcerptSidebar'));
            add_action("wp_ajax_getExcerptPostSidebar", array($this, 'getExcerptPostSidebar'));
        }

        function mm_sidebar_load_textdomain() {
            load_plugin_textdomain('mm_sidebar', false, PLUGIN_NAME . '/languages');
        }

        function mm_sidebar_load_scripts() {
            wp_enqueue_style('mm-sidebar-css', PLUGIN_URI . 'css/mm-sidebar-css.css', false, '1.0.0');
        }

        function mm_sidebar_admin_load_scripts() {
            wp_enqueue_script('jquery');
            wp_enqueue_style('mm-sidebar-admin-css', PLUGIN_URI . 'css/mm-sidebar-admin-css.css', false, '1.0.0');
            wp_register_script('mm-sidebar-js', PLUGIN_URI . 'js/mm-sidebar-js.js', array('jquery'));
            wp_localize_script('mm-sidebar-js', 'mmAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
            wp_enqueue_script('mm-sidebar-js');
        }

        /** register sidebar widgets * */
        function register_mm_sidebar_page_widget() {
            register_widget('MMSidebarPageWidget');
        }
        
        function register_mm_sidebar_post_widget() {
            register_widget('MMSidebarPostWidget');
        }

        /** add sidebar widgets meta box * */
        function mm_sidebar_widget_meta_box() {
            add_meta_box('mm_sidebar_widget_one', __('Sidebar Widget 1', 'textdomain'), array($this, 'mm_sidebar_widget_one_input'), 'page', 'normal', 'high');
            add_meta_box('mm_sidebar_widget_two', __('Sidebar Widget 2', 'textdomain'), array($this, 'mm_sidebar_widget_two_input'), 'page', 'normal', 'high');
            add_meta_box('mm_sidebar_widget_three', __('Sidebar Widget 3', 'textdomain'), array($this, 'mm_sidebar_widget_three_input'), 'page', 'normal', 'high');
            add_meta_box('mm_sidebar_widget_four', __('Sidebar Widget 4', 'textdomain'), array($this, 'mm_sidebar_widget_four_input'), 'page', 'normal', 'high');
            add_meta_box('mm_sidebar_widget_five', __('Sidebar Widget 5', 'textdomain'), array($this, 'mm_sidebar_widget_five_input'), 'page', 'normal', 'high');
            
            add_meta_box('mm_sidebar_widget_one', __('Sidebar Widget 1', 'textdomain'), array($this, 'mm_sidebar_widget_one_input'), 'post', 'normal', 'high');
            add_meta_box('mm_sidebar_widget_two', __('Sidebar Widget 2', 'textdomain'), array($this, 'mm_sidebar_widget_two_input'), 'post', 'normal', 'high');
            add_meta_box('mm_sidebar_widget_three', __('Sidebar Widget 3', 'textdomain'), array($this, 'mm_sidebar_widget_three_input'), 'post', 'normal', 'high');
            add_meta_box('mm_sidebar_widget_four', __('Sidebar Widget 4', 'textdomain'), array($this, 'mm_sidebar_widget_four_input'), 'post', 'normal', 'high');
            add_meta_box('mm_sidebar_widget_five', __('Sidebar Widget 5', 'textdomain'), array($this, 'mm_sidebar_widget_five_input'), 'post', 'normal', 'high');
        }

        function mm_sidebar_widget_one_input($field) {
            global $post;
            wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');

            $title_sidebar_one = get_post_meta($post->ID, 'title_sidebar_one', true);
            $excerp_sidebar_one = get_post_meta($post->ID, 'excerp_sidebar_one', true);
            $args = array(
                'textarea_rows' => 5,
            );
            ob_start();
            ?>
            <div id="title_sidebar_widget_one" class="sidebar_widget">
                <p class="label"><label for="title_sidebar_one"><?php echo __('Title Sidebar 1', 'text_domain'); ?>:</label></p>
                <?php wp_editor(html_entity_decode($title_sidebar_one), "title_sidebar_one", $args); ?>
            </div>

            <div id="excerp_sidebar_widget_one"  class="sidebar_widget">
                <p class="label"><label for="excerp_sidebar_one"><?php echo __('Excerpt Sidebar 1', 'text_domain'); ?>:</label></p>
                <?php wp_editor(html_entity_decode($excerp_sidebar_one), "excerp_sidebar_one", $args); ?>
            </div>
            <?php
            ob_end_flush();
            $html = ob_get_clean();
            echo $html;
        }

        function mm_sidebar_widget_two_input($field) {
            global $post;
            wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');

            $title_sidebar_two = get_post_meta($post->ID, 'title_sidebar_two', true);
            $excerp_sidebar_two = get_post_meta($post->ID, 'excerp_sidebar_two', true);
            $args = array(
                'textarea_rows' => 5,
            );
            ob_start();
            ?>
            <div id="title_sidebar_widget_two" class="sidebar_widget">
                <p class="label"><label for="title_sidebar_two"><?php echo __('Title Sidebar 2', 'text_domain'); ?>:</label></p>
                <?php wp_editor(html_entity_decode($title_sidebar_two), "title_sidebar_two", $args); ?>
            </div>

            <div id="excerp_sidebar_widget_two" class="sidebar_widget">
                <p class="label"><label for="excerp_sidebar_two"><?php echo __('Excerpt Sidebar 2', 'text_domain'); ?>:</label></p>
                <?php wp_editor(html_entity_decode($excerp_sidebar_two), "excerp_sidebar_two", $args); ?>
            </div>
            <?php
            ob_end_flush();
            $html = ob_get_clean();
            echo $html;
        }

        function mm_sidebar_widget_three_input($field) {
            global $post;
            wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');

            $title_sidebar_three = get_post_meta($post->ID, 'title_sidebar_three', true);
            $excerp_sidebar_three = get_post_meta($post->ID, 'excerp_sidebar_three', true);
            $args = array(
                'textarea_rows' => 5,
            );
            ob_start();
            ?>
            <div id="title_sidebar_widget_three" class="sidebar_widget">
                <p class="label"><label for="title_sidebar_three"><?php echo __('Title Sidebar 3', 'text_domain'); ?>:</label></p>
                <?php wp_editor(html_entity_decode($title_sidebar_three), "title_sidebar_three", $args); ?>
            </div>

            <div id="excerp_sidebar_widget_three" class="sidebar_widget">
                <p class="label"><label for="excerp_sidebar_three"><?php echo __('Excerpt Sidebar 3', 'text_domain'); ?>:</label></p>
                <?php wp_editor(html_entity_decode($excerp_sidebar_three), "excerp_sidebar_three", $args); ?>
            </div>
            <?php
            ob_end_flush();
            $html = ob_get_clean();
            echo $html;
        }

        function mm_sidebar_widget_four_input($field) {
            global $post;
            wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');

            $title_sidebar_four = get_post_meta($post->ID, 'title_sidebar_four', true);
            $excerp_sidebar_four = get_post_meta($post->ID, 'excerp_sidebar_four', true);
            $args = array(
                'textarea_rows' => 5,
            );
            ob_start();
            ?>
            <div id="title_sidebar_widget_four" class="sidebar_widget">
                <p class="label"><label for="title_sidebar_four"><?php echo __('Title Sidebar 4', 'text_domain'); ?>:</label></p>
                <?php wp_editor(html_entity_decode($title_sidebar_four), "title_sidebar_four", $args); ?>
            </div>

            <div id="excerp_sidebar_widget_four" class="sidebar_widget">
                <p class="label"><label for="excerp_sidebar_four"><?php echo __('Excerpt Sidebar 4', 'text_domain'); ?>:</label></p>
                <?php wp_editor(html_entity_decode($excerp_sidebar_four), "excerp_sidebar_four", $args); ?>
            </div>
            <?php
            ob_end_flush();
            $html = ob_get_clean();
            echo $html;
        }

        function mm_sidebar_widget_five_input($field) {
            global $post;
            wp_nonce_field('my_meta_box_nonce', 'meta_box_nonce');

            $title_sidebar_five = get_post_meta($post->ID, 'title_sidebar_five', true);
            $excerp_sidebar_five = get_post_meta($post->ID, 'excerp_sidebar_five', true);
            $args = array(
                'textarea_rows' => 5,
            );
            ob_start();
            ?>
            <div id="title_sidebar_widget_five" class="sidebar_widget">
                <p class="label"><label for="title_sidebar_five"><?php echo __('Title Sidebar 5', 'text_domain'); ?>:</label></p>
                <?php wp_editor(html_entity_decode($title_sidebar_five), "title_sidebar_five", $args); ?>
            </div>

            <div id="excerp_sidebar_widget_five" class="sidebar_widget">
                <p class="label"><label for="excerp_sidebar_five"><?php echo __('Excerpt Sidebar 5', 'text_domain'); ?>:</label></p>
                <?php wp_editor(html_entity_decode($excerp_sidebar_five), "excerp_sidebar_five", $args); ?>
            </div>
            <?php
            ob_end_flush();
            $html = ob_get_clean();
            echo $html;
        }

        function mm_sidebar_widget_meta_box_save($post_id) {
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return;

            if (!isset($_POST['meta_box_nonce']) || !wp_verify_nonce($_POST['meta_box_nonce'], 'my_meta_box_nonce'))
                return;

            if (!current_user_can('edit_post'))
                return;

            //one
            if (isset($_POST['title_sidebar_one'])) {
				$title_sidebar_one = esc_html($_POST['title_sidebar_one']);
				$title_sidebar_one_txt = sanitize_text_field($title_sidebar_one);
                update_post_meta($post_id, 'title_sidebar_one', $title_sidebar_one_txt);
            }
            if (isset($_POST['excerp_sidebar_one'])) {
				$excerp_sidebar_one = esc_html($_POST['excerp_sidebar_one']);
				$excerp_sidebar_one_txt = sanitize_text_field($excerp_sidebar_one);
                update_post_meta($post_id, 'excerp_sidebar_one', $excerp_sidebar_one_txt);
            }

            //two
            if (isset($_POST['title_sidebar_two'])) {
				$title_sidebar_two = esc_html($_POST['title_sidebar_two']);
				$title_sidebar_two_txt = sanitize_text_field($title_sidebar_two);
                update_post_meta($post_id, 'title_sidebar_two', $title_sidebar_two_txt);
            }
            if (isset($_POST['excerp_sidebar_two'])) {
				$excerp_sidebar_two = esc_html($_POST['excerp_sidebar_two']);
				$excerp_sidebar_two_txt = sanitize_text_field($excerp_sidebar_two);
                update_post_meta($post_id, 'excerp_sidebar_two', $excerp_sidebar_two_txt);
            }

            //three
            if (isset($_POST['title_sidebar_three'])) {
				$title_sidebar_three = esc_html($_POST['title_sidebar_three']);
				$title_sidebar_three_txt = sanitize_text_field($title_sidebar_three);
                update_post_meta($post_id, 'title_sidebar_three', $title_sidebar_three_txt);
            }
            if (isset($_POST['excerp_sidebar_three'])) {
				$excerp_sidebar_three = esc_html($_POST['excerp_sidebar_three']);
				$excerp_sidebar_three_txt = sanitize_text_field($excerp_sidebar_three);
                update_post_meta($post_id, 'excerp_sidebar_three', $excerp_sidebar_three_txt);
            }

            //four
            if (isset($_POST['title_sidebar_four'])) {
				$title_sidebar_four = esc_html($_POST['title_sidebar_four']);
				$title_sidebar_four_txt = sanitize_text_field($title_sidebar_four);
                update_post_meta($post_id, 'title_sidebar_four', $title_sidebar_four_txt);
            }
            if (isset($_POST['excerp_sidebar_four'])) {
				$excerp_sidebar_four = esc_html($_POST['excerp_sidebar_four']);
				$excerp_sidebar_four_txt = sanitize_text_field($excerp_sidebar_four);
                update_post_meta($post_id, 'excerp_sidebar_four', $excerp_sidebar_four_txt);
            }

            //five
            if (isset($_POST['title_sidebar_five'])) {
				$title_sidebar_five = esc_html($_POST['title_sidebar_five']);
				$title_sidebar_five_txt = sanitize_text_field($title_sidebar_five);
                update_post_meta($post_id, 'title_sidebar_five', $title_sidebar_five_txt);
            }
            if (isset($_POST['excerp_sidebar_five'])) {
				$excerp_sidebar_five = esc_html($_POST['excerp_sidebar_five']);
				$excerp_sidebar_five_txt = sanitize_text_field($excerp_sidebar_five);
                update_post_meta($post_id, 'excerp_sidebar_five', $excerp_sidebar_five_txt);
            }
        }

        function getExcerptSidebar() {
            $page_slug = sanitize_text_field($_POST['page_slug']);
            $excerpt_name = sanitize_text_field($_POST['excerpt_name']);

            $args = array(
                'name' => $page_slug,
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


            $html = '<select name="' . $excerpt_name . '">';
            if ($get_excerpt1 != '') {
                $html .= '<option value="excerp_sidebar_one">Excerpt Sidebar 1</option>';
            }
            if ($get_excerpt2 != '') {
                $html .= '<option value="excerp_sidebar_two">Excerpt Sidebar 2</option>';
            }
            if ($get_excerpt3 != '') {
                $html .= '<option value="excerp_sidebar_three">Excerpt Sidebar 3</option>';
            }
            if ($get_excerpt4 != '') {
                $html .= '<option value="excerp_sidebar_four">Excerpt Sidebar 4</option>';
            }
            if ($get_excerpt5 != '') {
                $html .= '<option value="excerp_sidebar_five">Excerpt Sidebar 5</option>';
            }

            $html .= '</select>';
            print_r($html);
            die();
        }
        
        function getExcerptPostSidebar() {
            $post_slug = sanitize_text_field($_POST['post_slug']);
            $excerpt_name = sanitize_text_field($_POST['excerpt_name']);

            $args = array(
                'name' => $post_slug,
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


            $html = '<select name="' . $excerpt_name . '">';
            if ($get_excerpt1 != '') {
                $html .= '<option value="excerp_sidebar_one">Excerpt Sidebar 1</option>';
            }
            if ($get_excerpt2 != '') {
                $html .= '<option value="excerp_sidebar_two">Excerpt Sidebar 2</option>';
            }
            if ($get_excerpt3 != '') {
                $html .= '<option value="excerp_sidebar_three">Excerpt Sidebar 3</option>';
            }
            if ($get_excerpt4 != '') {
                $html .= '<option value="excerp_sidebar_four">Excerpt Sidebar 4</option>';
            }
            if ($get_excerpt5 != '') {
                $html .= '<option value="excerp_sidebar_five">Excerpt Sidebar 5</option>';
            }

            $html .= '</select>';
            print_r($html);
            die();
        }
        

    }

    new MM_Sidebar();
}

