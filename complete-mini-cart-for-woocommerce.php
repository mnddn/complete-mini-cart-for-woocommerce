<?php

/*
 * Plugin Name:       Complete Mini Cart for WooCommerce
 * Plugin URI:        https://cmcw.mnddn.site/
 * Description:       This plugin adds a mini cart feature to your WooCommerce store. An Elementor Widget and a shortcode. All that you needed in one simple plugin.
 * Version:           2.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Moin Munna
 * Author URI:        https://portfolio.mnddn.site/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       complete-mini-cart-for-woocommerce
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

class CMCW_Plugin
{
    /**
     * Singleton instance
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     */
    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor: Setup plugin
     */
    private function __construct()
    {
        if (!$this->cmcw_is_woocommerce_active()) {
            add_action('admin_notices', [$this, 'cmcw_admin_notice_woocommerce_required']);
            return;
        }

        $this->define_constants();
        $this->load_dependencies();
        $this->load_admin_submenu_page();
        add_action('plugins_loaded', [$this, 'init']);
        add_filter('walker_nav_menu_start_el', [$this, 'enable_short_code_support'], 10, 1);
    }

    public function cmcw_is_woocommerce_active()
    {
        return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))
            || (is_multisite() && isset(get_site_option('active_sitewide_plugins')['woocommerce/woocommerce.php']));
    }

    public function cmcw_admin_notice_woocommerce_required()
    {
        $plugin_file = 'woocommerce/woocommerce.php';

        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p><strong>' . esc_html__('Complete Mini Cart for WooCommerce', 'complete-mini-cart-for-woocommerce') . '</strong> ';
        echo esc_html__('requires WooCommerce to be installed and activated to work properly.', 'complete-mini-cart-for-woocommerce') . '</p>';

        if (current_user_can('activate_plugins') && file_exists(WP_PLUGIN_DIR . '/' . $plugin_file)) {
            $activation_url = wp_nonce_url(
                self_admin_url('plugins.php?action=activate&plugin=' . $plugin_file),
                'activate-plugin_' . $plugin_file
            );
            echo '<p><a href="' . esc_url($activation_url) . '" class="button-primary">' . esc_html__('Activate WooCommerce', 'complete-mini-cart-for-woocommerce') . '</a></p>';
        } elseif (current_user_can('install_plugins')) {
            $install_url = wp_nonce_url(
                self_admin_url('update.php?action=install-plugin&plugin=woocommerce'),
                'install-plugin_woocommerce'
            );
            echo '<p><a href="' . esc_url($install_url) . '" class="button-primary">' . esc_html__('Install WooCommerce', 'complete-mini-cart-for-woocommerce') . '</a></p>';
        }

        echo '</div>';
    }

    /**
     * Define plugin constants
     */
    private function define_constants()
    {
        define('CMCW_PATH', plugin_dir_path(__FILE__));
        define('CMCW_URL', plugin_dir_url(__FILE__));
        define('CMCW_VERSION', '2.0.0');
    }

    /**
     * Load required plugin files
     */
    private function load_dependencies()
    {
        // Load the shortcode class
        require_once CMCW_PATH . 'includes/shortcode/Shortcode.php';
    }

    /**
     * Initialize plugin features
     */
    public function init()
    {
        // Check if Elementor is active
        if (did_action('elementor/loaded')) {
            // Load Elementor Widget
            require_once CMCW_PATH . 'includes/elementor-widget/widget-loader.php';
            new Cmcw_Widget_Loader();
        }

        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'cmcw_add_plugin_action_links']);

        require_once CMCW_PATH . 'includes/sidebar/Sidebar.php';
    }

    function cmcw_add_plugin_action_links($links)
    {
        $settings_url = admin_url('admin.php?page=cmcw_shortcode');
        $settings_link = sprintf(
            '<a href="%s">%s</a>',
            esc_url($settings_url),
            esc_html__('Settings', 'complete-mini-cart-for-woocommerce')
        );

        array_unshift($links, $settings_link);
        return $links;
    }

    /**
     * Admin notice if Elementor is not active
     */
    public function missing_elementor_notice()
    {
        echo '<div class="notice notice-error"><p><strong>CMCW Plugin</strong> requires Elementor to be installed and activated.</p></div>';
    }

    public function enable_short_code_support($item_output)
    {
        return do_shortcode($item_output); // Process shortcode in menu item
    }

    public function load_admin_submenu_page()
    {
        require_once CMCW_PATH . '/includes/admin/AdminLoaderCMCW.php';
    }
}

// Initialize the plugin
CMCW_Plugin::get_instance();