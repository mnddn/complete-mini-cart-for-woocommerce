<?php

if (!defined('ABSPATH')) {
    exit;
}

class Shortcode
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'cmcw_scripts'));
        add_shortcode('cmcw_mini_cart', array($this, 'cmcw_mini_cart_shortcode'));
        add_action('wp_ajax_nopriv_cmcw_update_cart_count', array($this, 'cmcw_update_cart_count'));
        add_action('wp_ajax_cmcw_update_cart_count', array($this, 'cmcw_update_cart_count'));
    }

    public function cmcw_scripts()  // Add this function
    {
        // Enqueue Scripts

        if (class_exists('WooCommerce')) {
            wp_enqueue_style('cmcw-style', CMCW_URL . '/src/css/style.css', array(), CMCW_VERSION, 'all');
            wp_enqueue_script('cmcw-script', CMCW_URL . '/src/js/script.js', array('jquery'), CMCW_VERSION, true);

            // Localize script to pass Ajax URL
            wp_localize_script('cmcw-script', 'cmcwCount', array(
                'cmcw_ajax_url' => admin_url('admin-ajax.php'),
            ));
        }
    }

    public function cmcw_mini_cart_shortcode()
    {
        if (class_exists('WooCommerce') && isset(WC()->cart)) {
            $shortcode_html = '<div class="cmcw-shortcode-container"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M0 24C0 10.7 10.7 0 24 0L69.5 0c22 0 41.5 12.8 50.6 32l411 0c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3l-288.5 0 5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5L488 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-288.3 0c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5L24 48C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96zM252 160c0 11 9 20 20 20l44 0 0 44c0 11 9 20 20 20s20-9 20-20l0-44 44 0c11 0 20-9 20-20s-9-20-20-20l-44 0 0-44c0-11-9-20-20-20s-20 9-20 20l0 44-44 0c-11 0-20 9-20 20z"/></svg><span class="cmcw-cart-count">' . WC()->cart->get_cart_contents_count() . '</span></div>';
            return $shortcode_html;
        } else {
            return '<span class="cmcw-cart-count">0</span>'; // Default if cart is unavailable
        }
    }

    public function cmcw_update_cart_count()
    {
        wp_send_json(array('count' => WC()->cart->get_cart_contents_count()));
    }
}

new Shortcode();