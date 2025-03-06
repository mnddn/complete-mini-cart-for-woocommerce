<?php

if (!defined('ABSPATH')) {
    exit;
}

class Cmcw_Mini_Cart extends \Elementor\Widget_Base
{
    /**
     * Get widget name.
     *
     * Retrieve cmcw_mini_cart widget name.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget name.
     */
    public function get_name(): string
    {
        return 'cmcw_mini_cart';
    }

    /**
     * Get widget title.
     *
     * Retrieve cmcw_mini_cart widget title.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget title.
     */
    public function get_title(): string
    {
        return esc_html__('Ajax Mini Cart', 'cmcw');
    }
    /**
     * Get widget icon.
     *
     * Retrieve cmcw_mini_cart widget icon.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget icon.
     */
    public function get_icon(): string
    {
        return 'eicon-product-info';
    }
    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the cmcw_mini_cart widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget categories.
     */
    public function get_categories(): array
    {
        return ['general'];
    }
    /**
     * Get widget keywords.
     *
     * Retrieve the list of keywords the cmcw_mini_cart widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */
    public function get_keywords(): array
    {
        return ['mini', 'cart', 'woocommerce', 'shop', 'store'];
    }

    /**
     * Add style Dependencies.
     *
     * Retrieve the list of style handles the cmcw_mini_cart widget belongs to.
     *
     * @since 1.0.0
     * @access public
     * @return array Widget keywords.
     */

    public function get_style_depends(): array
    {
        return ['style-handle'];
    }
    /**
     * Get custom help URL.
     *
     * Retrieve a URL where the user can get more information about the widget.
     *
     * @since 1.0.0
     * @access public
     * @return string Widget help URL.
     */
    public function get_custom_help_url(): string
    {
        return 'https://example.com/widget-name';
    }

    /**
     * Register cmcw_mini_cart widget controls.
     *
     * Add input fields to allow the user to customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls(): void
    {

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Icon', 'cmcw'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'icon',
            [
                'label' => esc_html__('Icon', 'cmcw'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-cart-plus',
                    'library' => 'fa-solid',
                ],
                'recommended' => [
                    'fa-solid' => [
                        'circle',
                        'dot-circle',
                        'square-full',
                    ],
                    'fa-regular' => [
                        'circle',
                        'dot-circle',
                        'square-full',
                    ]
                ],
            ]
        );

        $this->end_controls_section();

    }
    /**
     * Render cmcw_mini_cart widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render(): void
    {
        $settings = $this->get_settings_for_display();

        if (class_exists('WooCommerce') && isset(WC()->cart)) {
            ?>
            <a href="<?php echo wc_get_cart_url(); ?>">
                <div class="cmcw-widget-container">
                    <style>
                        .cmcw-widget-container svg {
                            max-width: 24px;
                            /* Set default width */
                            max-height: 24px;
                            /* Set default height */
                            fill: #000000;
                            /* Set default color */
                        }
                    </style>
                    <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                    <span class="cmcw-cart-count-elementor"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                </div>
            </a>
            <?php
        } else {
            ?>
            <a href="<?php wc_get_cart_url(); ?>"></a>
            <div class="cmcw-widget-container">
                <style>
                    .cmcw-widget-container svg {
                        max-width: 24px;
                        /* Set default width */
                        max-height: 24px;
                        /* Set default height */
                        fill: #000000;
                        /* Set default color */
                    }
                </style>
                <?php \Elementor\Icons_Manager::render_icon($settings['icon'], ['aria-hidden' => 'true']); ?>
                <span class="cmcw-cart-count-elementor">0</span>
            </div>
            </a>
            <?php
        }
    }
}