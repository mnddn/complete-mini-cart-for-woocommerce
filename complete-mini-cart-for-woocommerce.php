<?php

/*
 * Plugin Name:       Mini Cart for WooCommerce
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       This plugin adds a mini cart feature to your WooCommerce store. An Elementor Widget, a Block and a shortcode. All that you needed in one simple plugin.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Moin Munna
 * Author URI:        https://portfolio.mnddn.site/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       cmcw
 * Requires Plugins:  woocommerce
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

// Define the plugin path
define('CMCW_PATH', plugin_dir_path(__FILE__));

// Define the plugin URL
define('CMCW_URL', plugin_dir_url(__FILE__));

// Define the plugin version
define('CMCW_VERSION', '1.0.0');

// Load the plugin

require_once CMCW_PATH . 'includes/Shortcode.php';