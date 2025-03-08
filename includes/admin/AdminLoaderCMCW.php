<?php

if (!defined('ABSPATH')) {
    exit;
}

class AdminLoaderCMCW
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
        add_action('admin_enqueue_scripts', [$this, 'load_scripts'], 1000);
        $this->init();

    }

    /**
     * Load required plugin files
     */
    public function load_scripts()
    {
        // Load scripts
        wp_enqueue_style('cmcw-admin-css', CMCW_URL . 'assets/css/admin.css', [], CMCW_VERSION);
        wp_enqueue_script('cmcw-admin-js', CMCW_URL . 'assets/js/admin.js', ['jquery'], CMCW_VERSION, true);
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('cmcw-admin_js', CMCW_URL . '/src/js/admin.js', array('wp-color-picker'), CMCW_VERSION, true);
        // FontAwesome (for icons)
        wp_enqueue_style('cmcw-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');

        // FontAwesome Icon Picker
        wp_enqueue_style('cmcw-iconpicker-css', 'https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/css/fontawesome-iconpicker.min.css');
        wp_enqueue_script('cmcw-iconpicker-js', 'https://cdnjs.cloudflare.com/ajax/libs/fontawesome-iconpicker/3.2.0/js/fontawesome-iconpicker.min.js', array('jquery'), null, true);

    }

    /**
     * Initialize plugin features
     */
    public function init()
    {
        // Add Submenu Under Woocommerce Settings
        add_action('admin_menu', [$this, 'cmcw_register_submenu'], 1000);
        add_action('admin_init', [$this, 'register_settings']);
    }
    public function cmcw_register_submenu()
    {
        add_submenu_page(
            'woocommerce',
            esc_html__('Complete Mini Cart for Woocommerce', 'cmcw'),
            esc_html__('Mini Cart', 'cmcw'),
            'manage_options',
            'cmcw_shortcode',
            [$this, 'submenu_section_template'],
            1000
        );
    }

    public function register_settings()
    {
        if (!get_option('cmcw_settings')) {
            add_option('cmcw_settings');
        }

        register_setting('cmcw_settings', 'icon_name');
        register_setting('cmcw_settings', 'count_bg_color');
        register_setting('cmcw_settings', 'icon_color');
        register_setting('cmcw_settings', 'text_color');
        register_setting('cmcw_settings', 'icon_size');
        register_setting('cmcw_settings', 'count_size');
        register_setting('cmcw_settings', 'box_margin');
        register_setting('cmcw_settings', 'count_position');

        add_settings_section(
            'cmcw_settings_section',
            'Shortcode Styles',
            [$this, 'cmcw_settings_section_callback'],
            'cmcw_shortcode'
        );

        add_settings_field('icon_name', 'Icon Name', [$this, 'icon_name_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('count_bg_color', 'Cart Count BG Color', [$this, 'count_bg_color_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('text_color', 'Cart Count Text Color', [$this, 'text_color_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('icon_color', 'Icon Color', [$this, 'icon_color_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('icon_size', 'Icon Size', [$this, 'icon_size_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('count_size', 'Cart Count Size', [$this, 'count_size_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('count_position', 'Cart Count Position', [$this, 'count_position_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('box_margin', 'Box Margin', [$this, 'box_margin_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
    }

    public function submenu_section_template()
    {
        require_once CMCW_PATH . 'templates/admin_submenu_markup.php';
    }

    public function cmcw_settings_section_callback()
    {
        require_once CMCW_PATH . 'templates/admin_submenu_markup.php';
    }

    public function icon_name_callback()
    {
        $value = get_option('icon_name');
        ?>
        <input class="cmcw-icon-name" type="text" name="icon_name" value="<?php echo esc_attr($value) ?>" />
        <i class="<?php echo esc_attr($value) ?>"
            title="Enter the icon name from Font Awesome. For example, 'fas fa-shopping-cart'"></i>
        <?php
    }

    public function count_bg_color_callback()
    {
        $value = get_option('count_bg_color');
        echo '<input class="cmcw-count-bg-color" type="text" name="count_bg_color" value="' . esc_attr($value) . '" />';
    }

    public function text_color_callback()
    {
        $value = get_option('text_color');
        echo '<input class="cmcw-text-color" type="text" name="text_color" value="' . esc_attr($value) . '" />';
    }

    public function icon_color_callback()
    {
        $value = get_option('icon_color');
        echo '<input class="cmcw-icon-color" type="text" name="icon_color" value="' . esc_attr($value) . '" />';
    }

    public function icon_size_callback()
    {
        $value = get_option('icon_size');
        echo '<input type="number" name="icon_size" value="' . esc_attr($value) . '" />';
    }

    public function count_size_callback()
    {
        $value = get_option('count_size');
        echo '<input type="number" name="count_size" value="' . esc_attr($value) . '" />';
    }
    public function count_position_callback()
    {
        $value = get_option('count_position');
        echo '<input type="number" name="count_position" value="' . esc_attr($value) . '" />';
    }

    public function box_margin_callback()
    {
        $value = get_option('box_margin');
        echo '<input type="number" name="box_margin" value="' . esc_attr($value) . '" />';
    }
}

AdminLoaderCMCW::get_instance();