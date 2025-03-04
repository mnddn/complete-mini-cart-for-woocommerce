<?php

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
            return '<span class="cmcw-cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
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