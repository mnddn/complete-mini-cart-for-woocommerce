<?php

class CMCW_Sidebar
{
    private static $instance = null;

    private function __construct()
    {
        // Private constructor to prevent direct instantiation
        $this->init();
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function init()
    {
        // Initialize the sidebar functionality here
        $this->cmcw_add_sidebar();
    }

    public function cmcw_add_sidebar()
    {
        add_filter(
            'cmcw_html_loaded',
            array($this, 'cmcw_add_sidebar_html'),
            10,
            1
        );

        add_filter(
            'cmcw_widget_loaded',
            array($this, 'cmcw_add_sidebar_html_for_widget'),
            10,
            1
        );
    }

    public function cmcw_add_sidebar_html($content)
    {
        $sidebar_html = '<div id="cmcw-cart-sidebar" class="cmcw-cart-sidebar">
                            <div class="cmcw-cart-sidebar-header">
                                <h4>Your Cart</h4>
                                <i id="cmcw-cart-close" class="fa-solid fa-xmark"></i>
                            </div>
                            <div id="cmcw-cart-contents">
                            </div>
                        </div>';
        return $content . $sidebar_html;
    }

    public function cmcw_add_sidebar_html_for_widget($content)
    {
        $sidebar_html = '<div id="cmcw-widget-cart-sidebar" class="cmcw-widget-cart-sidebar">
                            <div class="cmcw-cart-sidebar-header">
                                <h4>Your Cart</h4>
                                <i id="cmcw-widget-cart-close" class="fa-solid fa-xmark"></i>
                            </div>
                            <div id="cmcw-widget-cart-contents">
                            </div>
                        </div>';
        return $content . $sidebar_html;
    }


}

// Usage example
CMCW_Sidebar::getInstance();