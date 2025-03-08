<?php

if (!defined('ABSPATH')) {
    exit;
}

class Shortcode
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', array($this, 'cmcw_scripts'), 100);
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
            // fontawesome

            wp_enqueue_style('cmcw-font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
        }

    }

    public function cmcw_mini_cart_shortcode()
    {
        if (class_exists('WooCommerce') && isset(WC()->cart)) {
            $icon_class = get_option('icon_name', 'fa-solid fa-cart-plus');

            $cart_count = WC()->cart->get_cart_contents_count();
            $style = '<style>
            .cmcw-shortcode-container {
            position: relative;
            display: inline-block;
            height: 20px;
            width: 25px;
            margin:' . get_option('box_margin', '0') . 'px;
            }
            .cmcw-cart-count {
            background-color:' . get_option('count_bg_color', '#ff3a3a') . '; 
            font-size:' . get_option('count_size', '10') . 'px;
            color: ' . get_option('text_color', '#e8e8e8') . ';
            top: -5px;
            left: 0px;
            }
            .cmcw-shortcode-container i {
            width: 100%;
            height: 100%;
            font-size: ' . get_option('icon_size', '20') . 'px;
            color: ' . get_option('icon_color', '##FF3A3A') . ';
            margin-bottom: -3px;
            }
            </style>';

            $shortcode_html = '<div class="cmcw-shortcode-container">' . $style . '<i class="' . esc_attr($icon_class) . '"></i>' . '<span class="cmcw-cart-count">'
                . $cart_count . '</span></div>';
            error_log('Icon Class: ' . get_option('icon_name', 'fas fa-cart-plus'));

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