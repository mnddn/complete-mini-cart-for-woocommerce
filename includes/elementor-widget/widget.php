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
        return esc_html__('Ajax Mini Cart', 'complete-mini-cart-for-woocommerce');
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
        return 'https://cmcw.mnddn.site/';
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

        // Content Section for Icon Selection

        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Icon', 'complete-mini-cart-for-woocommerce'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'cmcw_icon',
            [
                'label' => esc_html__('Icon', 'complete-mini-cart-for-woocommerce'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa-solid fa-cart-plus',
                    'library' => 'fa-solid',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section For Icon Styles

        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__('Icon Style', 'complete-mini-cart-for-woocommerce'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'color',
            [
                'label' => esc_html__('Color', 'complete-mini-cart-for-woocommerce'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => [
                    '{{WRAPPER}} .cmcw-widget-container svg' => 'color: {{VALUE}}; fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'size',
            [
                'label' => esc_html__('Size', 'complete-mini-cart-for-woocommerce'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'range' => [
                    'px' => ['min' => 10, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cmcw-widget-container' => 'height: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section For Cart Count Styles

        $this->start_controls_section(
            'section_count_style',
            [
                'label' => esc_html__('Cart Count Style', 'complete-mini-cart-for-woocommerce'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'count_bg_color',
            [
                'label' => esc_html__('Background Color', 'complete-mini-cart-for-woocommerce'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ff3a3a',
                'selectors' => [
                    '{{WRAPPER}} .cmcw-cart-count-elementor' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'count_text_color',
            [
                'label' => esc_html__('Text Color', 'complete-mini-cart-for-woocommerce'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .cmcw-cart-count-elementor' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'count_size',
            [
                'label' => esc_html__('Font Size', 'complete-mini-cart-for-woocommerce'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'range' => [
                    'px' => ['min' => 1, 'max' => 100],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cmcw-cart-count-elementor' => 'font-size: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'count_top_position',
            [
                'label' => esc_html__('Position From Top', 'complete-mini-cart-for-woocommerce'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'range' => [
                    'px' => ['min' => -20, 'max' => 50],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cmcw-cart-count-elementor' => 'top: -{{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'count_left_position',
            [
                'label' => esc_html__('Position From Left', 'complete-mini-cart-for-woocommerce'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', 'rem'],
                'default' => [
                    'unit' => 'px',
                    'size' => 5,
                ],
                'range' => [
                    'px' => ['min' => -20, 'max' => 50],
                ],
                'selectors' => [
                    '{{WRAPPER}} .cmcw-cart-count-elementor' => 'left: {{SIZE}}{{UNIT}}',
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
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>">
                <div class="cmcw-widget-container">
                    <?php \Elementor\Icons_Manager::render_icon($settings['cmcw_icon'], ['aria-hidden' => 'true']); ?>
                    <span class="cmcw-cart-count-elementor"><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
                </div>
            </a>
            <?php
        } else {
            ?>
            <a href="#"></a>
            <div class="cmcw-widget-container">
                <?php \Elementor\Icons_Manager::render_icon($settings['cmcw_icon'], ['aria-hidden' => 'true']); ?>
                <span class="cmcw-cart-count-elementor">0</span>
            </div>
            </a>
            <?php
        }
    }
}