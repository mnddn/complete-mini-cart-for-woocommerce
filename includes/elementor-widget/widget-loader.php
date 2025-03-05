<?php

if (!defined('ABSPATH')) {
    exit;
}

class Cmcw_Widget_Loader
{
    public function __construct()
    {
        add_action('plugin_loaded', [$this, 'init']);
        add_action('wp_enqueue_scripts', [$this, 'cmcw_register_scripts']);
    }

    public function init()
    {
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice']);
            return;
        }
        add_action('elementor/widgets/register', [$this, 'cmcw_register_widget']);
    }
    public function cmcw_register_widget($widgets_manager)
    {
        // if (!did_action('elementor/loaded')) {
        //     return;
        // }

        require_once(CMCW_PATH . 'includes/elementor-widget/widget.php');

        $widgets_manager->register(new \Cmcw_Mini_Cart());

    }

    public function cmcw_register_scripts()
    {
        wp_enqueue_style('cmcw-elementor-widget-css', CMCW_URL . '/src/css/elementor-widget.css', [], CMCW_VERSION);
        // wp_enqueue_script('cmcw-widget-js', CMCW_URL . 'assets/js/widget.js', ['jquery'], CMCW_VERSION, true);
    }

    public function admin_notice()
    {
        echo '<div class="notice notice-warning"><p><strong>Mini Cart for Woocommerce</strong> plugin requires Elementor to be installed and active.</p></div>';
    }
}

new Cmcw_Widget_Loader();