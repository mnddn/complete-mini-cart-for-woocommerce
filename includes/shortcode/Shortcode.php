<?php

if (!defined('ABSPATH')) {
    exit;
}

class CMCW_Shortcode
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

            wp_enqueue_style('cmcw-font-awesome', CMCW_URL . '/src/css/fontawesome-all.min.css', array(), CMCW_VERSION, 'all');

            // Add the Shortcode inline style

            wp_add_inline_style('cmcw-style', $this->cmcw_get_inline_shortcode_css());
        }
    }

    public function cmcw_get_inline_shortcode_css()
    {
        $margin = get_option('cmcw_box_margin') !== '' ? get_option('cmcw_box_margin') : '0';
        $count_font_size = get_option('cmcw_count_size') !== '' ? get_option('cmcw_count_size') : '10';
        $count_position = get_option('cmcw_count_position') !== '' ? get_option('cmcw_count_position') : '5';
        $icon_font_size = get_option(option: 'cmcw_icon_size') !== '' ? get_option('cmcw_icon_size') : '20';
        $count_bg_color = get_option('cmcw_count_bg_color') !== '' ? get_option('cmcw_count_bg_color') : '#dd9933';
        $count_text_color = get_option('cmcw_text_color') !== '' ? get_option('cmcw_text_color') : '#ffffff';
        $count_icon_color = get_option('cmcw_icon_color') !== '' ? get_option('cmcw_icon_color') : '#000000'; // Need to change the name



        $style = '.cmcw-shortcode-container {
                        position: relative;
                        display: inline-block;
                        height: 20px;
                        width: 25px;
                        margin: ' . absint($margin) . 'px;
                    }

                    .cmcw-cart-count {
                        background-color: ' . esc_attr($count_bg_color) . ';
                        font-size: ' . absint($count_font_size) . 'px;
                        color: ' . esc_attr($count_text_color) . ';
                        top: -' . absint($count_position) . 'px;
                        left: ' . absint($count_position) . 'px;
                    }

                    .cmcw-shortcode-container i {
                        width: 100%;
                        height: 100%;
                        font-size: ' . absint($icon_font_size) . 'px;
                        color: ' . esc_attr($count_icon_color) . ';
                        margin-bottom: -3px;
                    }';

        return $style;
    }

    public function cmcw_mini_cart_shortcode()
    {
        if (class_exists('WooCommerce')) {
            $icon_class = get_option('cmcw_icon_name') !== '' ? get_option('cmcw_icon_name') : 'fas fa-cart-plus';

            $cart_count = esc_html(WC()->cart->get_cart_contents_count());

            $shortcode_html = '<div class="cmcw-shortcode-container"><i class="' . esc_attr(text: $icon_class) . '"></i>' . '<span class="cmcw-cart-count">'
                . $cart_count . '</span></div>';

            $filtered_html = apply_filters('cmcw_html_loaded', $shortcode_html);

            return $filtered_html;

        } else {
            return '<span class="cmcw-cart-count">0</span>'; // Default if cart is unavailable
        }
    }

    public function cmcw_update_cart_count()
    {
        if (!class_exists('WooCommerce') || !WC()->cart) {
            wp_send_json_success(array('count' => 0));
        }

        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_html = $this->cmcw_add_cart_items_to_sidebar();
        wp_send_json(array('count' => $cart_count, 'cart_html' => $cart_html));

        wp_die(); // Always die in functions echoing Ajax content
    }

    public function cmcw_add_cart_items_to_sidebar()
    {
        ob_start();

        if (WC()->cart->is_empty()) {
            echo '<p class="cmcw-no-items">Your cart is empty.</p>';
        } else {

            echo '<div class="cmcw-cart-item-wrapper">';
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = $cart_item['data'];
                $product_id = $cart_item['product_id'];
                $thumbnail = wp_kses_post($_product->get_image(array(60, 60), array('class' => 'cart-thumb')));

                echo '<div class="cmcw-cart-item">';
                echo '<div class="cmcw-cart-thumb">' . $thumbnail . '</div>';
                echo '<div class="cmcw-cart-details">';
                echo '<strong class="cmcw-cart-item-title">' . esc_html($_product->get_name()) . '</strong>';
                echo '<p class="cmcw-cart-item-price">' . wp_kses_post($_product->get_price());
                echo ' <span class="cmcw-quantity"> x ' . intval($cart_item['quantity']) . '</span> = ' . wc_price($cart_item['line_total']) . '</p>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';

            echo '<div class="cmcw-cart-footer">';
            echo '<p class="cmcw-cart-total">Total: ' . wp_kses_post(WC()->cart->get_cart_subtotal()) . '</p>';
            echo '<div class="cmcw-cart-btns">';
            echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="cmcw-cart-btn">View Cart</a>';
            echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="cmcw-cart-btn">Checkout</a>';
            echo '</div>';
            echo '</div>';
        }

        $cart_html = ob_get_clean();
        return $cart_html;
    }
}

new CMCW_Shortcode();