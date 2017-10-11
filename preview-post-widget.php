<?php
/*
Plugin Name:  Preview Post Widgets
Plugin URI:
Description:   Preview Post Widgets  it is a free plugin forwordpress which should render specified post with one of 3 pre­defined layouts.
Version: 0.0.1
Author: Julia_Klimenko
Text Domain: preview-post-widgets
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/
if (!defined('ABSPATH')) exit;

define('PPW_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PPW_PLUGIN_URL', plugin_dir_url(__FILE__));

if (!class_exists('Preview_Post_Widgets')) {
    class Preview_Post_Widgets
    {
        public function __construct()
        {
            require_once(plugin_dir_path(__FILE__) . '/classes/class-ppw-widget.php');
            add_action('widgets_init', array($this, 'ppw_register_widget'));
            add_action('wp_enqueue_scripts', array($this, 'ppw_theme_add_script'), 10);
        }

        public function ppw_register_widget()
        {
            register_widget('Ppw_Widget');
        }

        public function ppw_theme_add_script()
        {
            wp_enqueue_style('front_css', PPW_PLUGIN_URL . 'assets/css/front.css');
            wp_enqueue_script('front_js', PPW_PLUGIN_URL . 'assets/js/front.js', array('jquery'));
        }
    }

    new Preview_Post_Widgets();


}