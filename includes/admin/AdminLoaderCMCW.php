<?php

if (!defined('ABSPATH')) {
    exit;
}

class CMCW_AdminLoader
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
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('cmcw-admin_js', CMCW_URL . '/src/js/admin.js', array('wp-color-picker'), CMCW_VERSION, true);
        // FontAwesome (for icons)
        wp_enqueue_style('cmcw-fontawesome', CMCW_URL . '/src/css/fontawesome-all.min.css', [], CMCW_VERSION);

        // FontAwesome Icon Picker
        wp_enqueue_style('cmcw-iconpicker-css', CMCW_URL . '/src/css/fontawesome-iconpicker.min.css', ['cmcw-fontawesome'], CMCW_VERSION);
        wp_enqueue_script('cmcw-iconpicker-js', CMCW_URL . '/src/js/fontawesome-iconpicker.min.js', array('jquery'), null, true);

    }

    /**
     * Initialize plugin features
     */
    public function init()
    {
        // Add options
        $this->cmcw_add_options();
        // Add Submenu Under Woocommerce Settings
        add_action('admin_menu', [$this, 'cmcw_register_submenu'], 1000);
        add_action('admin_init', [$this, 'register_settings']);
    }
    public function cmcw_register_submenu()
    {
        add_submenu_page(
            'woocommerce',
            esc_html__('Complete Mini Cart for Woocommerce', 'complete-mini-cart-for-woocommerce'),
            esc_html__('Mini Cart', 'complete-mini-cart-for-woocommerce'),
            'manage_options',
            'cmcw_shortcode',
            [$this, 'submenu_section_template'],
            1000
        );
    }

    public static function sanitize_callback_text($input)
    {
        return sanitize_text_field($input); // (6) Sanitize the input properly
    }

    public function sanitize_icon_name($input)
    {
        return sanitize_text_field($input); // Ensures valid FontAwesome class names
    }

    public function cmcw_add_options()
    {
        add_option('cmcw_box_margin', '0');
        add_option('cmcw_count_size', '10');
        add_option('cmcw_count_position', '5');
        add_option('cmcw_icon_size', '20');
        add_option('cmcw_count_bg_color', '#dd9933');
        add_option('cmcw_text_color', '#ffffff');
        add_option('cmcw_icon_color', '#000000');
        add_option('cmcw_icon_name', 'fas fa-cart-plus');
    }

    public function register_settings()
    {

        register_setting('cmcw_options_group', 'cmcw_icon_name', array(
            'type' => 'string',
            'sanitize_callback' => array($this, 'sanitize_icon_name'),
        ));

        register_setting('cmcw_options_group', 'cmcw_count_bg_color', array(
            'type' => 'string',
            'sanitize_callback' => array($this, 'sanitize_callback_text'),
        ));

        register_setting('cmcw_options_group', 'cmcw_icon_color', array(
            'type' => 'string',
            'sanitize_callback' => array($this, 'sanitize_callback_text'),
        ));

        register_setting('cmcw_options_group', 'cmcw_text_color', array(
            'type' => 'string',
            'sanitize_callback' => array($this, 'sanitize_callback_text'),
        ));

        register_setting('cmcw_options_group', 'cmcw_icon_size', array(
            'type' => 'integer',
            'sanitize_callback' => array($this, 'sanitize_callback_text'),
        ));

        register_setting('cmcw_options_group', 'cmcw_count_size', array(
            'type' => 'integer',
            'sanitize_callback' => array($this, 'sanitize_callback_text'),
        ));

        register_setting('cmcw_options_group', 'cmcw_box_margin', array(
            'type' => 'integer',
            'sanitize_callback' => array($this, 'sanitize_callback_text'),
        ));

        register_setting('cmcw_options_group', 'cmcw_count_position', array(
            'type' => 'integer',
            'sanitize_callback' => array($this, 'sanitize_callback_text'),
        ));

        // Adding Settings section

        add_settings_section(
            'cmcw_settings_section',
            'Shortcode Styles',
            [$this, 'cmcw_settings_section_callback'],
            'cmcw_shortcode'
        );

        add_settings_field('cmcw_icon_name', 'Icon Name', [$this, 'icon_name_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('cmcw_count_bg_color', 'Cart Count BG Color', [$this, 'count_bg_color_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('cmcw_text_color', 'Cart Count Text Color', [$this, 'text_color_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('cmcw_icon_color', 'Icon Color', [$this, 'icon_color_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('cmcw_icon_size', 'Icon Size', [$this, 'icon_size_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('cmcw_count_size', 'Cart Count Size', [$this, 'count_size_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('cmcw_count_position', 'Cart Count Position', [$this, 'count_position_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
        add_settings_field('cmcw_box_margin', 'Box Margin', [$this, 'box_margin_callback'], 'cmcw_shortcode', 'cmcw_settings_section');
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
        $value = get_option('cmcw_icon_name');
        ?>
        <input class="cmcw-icon-name" type="text" name="cmcw_icon_name" value="<?php echo esc_attr($value) ?>" />
        <i class="<?php echo esc_attr($value) ?>"
            title="Enter the icon name from Font Awesome. For example, 'fas fa-shopping-cart'"></i>
        <?php
    }

    public function count_bg_color_callback()
    {
        $value = get_option('cmcw_count_bg_color', '#ff3a3a');
        echo '<input class="cmcw-count-bg-color" type="text" name="cmcw_count_bg_color" value="' . esc_attr($value) . '" />';
    }

    public function text_color_callback()
    {
        $value = get_option('cmcw_text_color', '#e8e8e8');
        echo '<input class="cmcw-text-color" type="text" name="cmcw_text_color" value="' . esc_attr($value) . '" />';
    }

    public function icon_color_callback()
    {
        $value = get_option('cmcw_icon_color', '#e8e8e8');
        echo '<input class="cmcw-icon-color" type="text" name="cmcw_icon_color" value="' . esc_attr($value) . '" />';
    }

    public function icon_size_callback()
    {
        $value = get_option('cmcw_icon_size', '20');
        echo '<input type="number" name="cmcw_icon_size" value="' . esc_attr($value) . '" />';
    }

    public function count_size_callback()
    {
        $value = get_option('cmcw_count_size', '10');
        echo '<input type="number" name="cmcw_count_size" value="' . esc_attr($value) . '" />';
    }
    public function count_position_callback()
    {
        $value = get_option('cmcw_count_position', '5');
        echo '<input type="number" name="cmcw_count_position" value="' . esc_attr($value) . '" />';
    }

    public function box_margin_callback()
    {
        $value = get_option('cmcw_box_margin', '0');
        echo '<input type="number" name="cmcw_box_margin" value="' . esc_attr($value) . '" />';
    }
}

CMCW_AdminLoader::get_instance();