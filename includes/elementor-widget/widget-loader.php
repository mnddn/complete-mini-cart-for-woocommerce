<?php

if (!defined('ABSPATH')) {
    exit;
}


class Cmcw_Widget_Loader
{
    public function __construct()
    {
        $this->init();
        add_action('wp_enqueue_scripts', [$this, 'cmcw_register_scripts'], 1000);
    }

    public function init()
    {
        add_action('elementor/widgets/register', [$this, 'cmcw_register_widget']);
    }
    public function cmcw_register_widget($widgets_manager)
    {

        require_once(CMCW_PATH . 'includes/elementor-widget/widget.php');

        $widgets_manager->register(new \Cmcw_Mini_Cart());

    }

    public function cmcw_register_scripts()
    {
        wp_enqueue_style('cmcw-elementor-widget-css', CMCW_URL . '/src/css/elementor-widget.css', [], CMCW_VERSION);
    }
}