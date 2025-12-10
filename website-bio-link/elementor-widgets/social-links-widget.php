<?php

/**
 * Elementor Social Links Widget - Complete Customizable Version
 *
 * @package Website_Bio_Link
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if Elementor is active before defining the widget
if (!class_exists('\Elementor\Widget_Base') || !class_exists('\Elementor\Plugin')) {
    return;
}

/**
 * Social Links Widget for Elementor
 */
class WBL_Social_Links_Widget extends \Elementor\Widget_Base
{

    /**
     * Get widget name
     */
    public function get_name()
    {
        return 'wbl_social_links';
    }

    /**
     * Get widget title
     */
    public function get_title()
    {
        return __('Social Links', 'website-bio-link');
    }

    /**
     * Get widget icon
     */
    public function get_icon()
    {
        return 'eicon-social-icons';
    }

    /**
     * Get widget categories
     */
    public function get_categories()
    {
        return array('website-bio-link');
    }

    /**
     * Get widget keywords
     */
    public function get_keywords()
    {
        return array('social', 'links', 'icons', 'share', 'bio', 'profile');
    }

    /**
     * Register widget controls
     */
    protected function register_controls()
    {

        // =====================================================
        // CONTENT TAB - Social Set Selection
        // =====================================================
        $this->start_controls_section(
            'section_content',
            array(
                'label' => __('Social Set', 'website-bio-link'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'social_set_id',
            array(
                'label'       => __('Select Social Set', 'website-bio-link'),
                'type'        => \Elementor\Controls_Manager::SELECT2,
                'options'     => $this->get_social_sets(),
                'default'     => '',
                'label_block' => true,
                'description' => __('Choose which social media set to display', 'website-bio-link'),
            )
        );

        $this->add_control(
            'show_label',
            array(
                'label'        => __('Show Labels', 'website-bio-link'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'website-bio-link'),
                'label_off'    => __('No', 'website-bio-link'),
                'return_value' => 'true',
                'default'      => 'false',
                'description'  => __('Display platform names next to icons', 'website-bio-link'),
            )
        );

        $this->end_controls_section();

        // =====================================================
        // CONTENT TAB - Layout Settings
        // =====================================================
        $this->start_controls_section(
            'section_layout',
            array(
                'label' => __('Layout', 'website-bio-link'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_responsive_control(
            'align',
            array(
                'label'   => __('Alignment', 'website-bio-link'),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => array(
                    'flex-start' => array(
                        'title' => __('Left', 'website-bio-link'),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => __('Center', 'website-bio-link'),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'flex-end' => array(
                        'title' => __('Right', 'website-bio-link'),
                        'icon'  => 'eicon-text-align-right',
                    ),
                    'space-between' => array(
                        'title' => __('Space Between', 'website-bio-link'),
                        'icon'  => 'eicon-text-align-justify',
                    ),
                ),
                'default' => 'flex-start',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-list' => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'      => __('Icon Size', 'website-bio-link'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px', 'em', 'rem'),
                'range'      => array(
                    'px' => array(
                        'min' => 10,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => 0.5,
                        'max' => 5,
                    ),
                ),
                'default' => array(
                    'size' => 20,
                    'unit' => 'px',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-icon-wrapper i' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .wbl-social-icon-wrapper svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_wrapper_size',
            array(
                'label'      => __('Icon Wrapper Size', 'website-bio-link'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range'      => array(
                    'px' => array(
                        'min' => 30,
                        'max' => 150,
                    ),
                ),
                'default' => array(
                    'size' => 44,
                    'unit' => 'px',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-icon-wrapper' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'gap',
            array(
                'label'      => __('Gap Between Icons', 'website-bio-link'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px', 'em'),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                    'em' => array(
                        'min' => 0,
                        'max' => 10,
                    ),
                ),
                'default' => array(
                    'size' => 16,
                    'unit' => 'px',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-list' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        // =====================================================
        // STYLE TAB - Style Preset
        // =====================================================
        $this->start_controls_section(
            'section_style_preset',
            array(
                'label' => __('Style Preset', 'website-bio-link'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'icon_style',
            array(
                'label'   => __('Style Preset', 'website-bio-link'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'circle'   => __('Circle', 'website-bio-link'),
                    'rounded'  => __('Rounded Square', 'website-bio-link'),
                    'flat'     => __('Flat Outline', 'website-bio-link'),
                    'minimal'  => __('Minimal', 'website-bio-link'),
                    'glass'    => __('Glassmorphism', 'website-bio-link'),
                    'gradient' => __('Gradient', 'website-bio-link'),
                ),
                'default' => 'circle',
                'description' => __('Choose a style preset, then customize colors below', 'website-bio-link'),
            )
        );

        $this->add_control(
            'use_brand_colors',
            array(
                'label'        => __('Use Brand Colors', 'website-bio-link'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __('Yes', 'website-bio-link'),
                'label_off'    => __('No', 'website-bio-link'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'description'  => __('Use official brand colors for each platform. Turn off to customize colors manually.', 'website-bio-link'),
                'separator'    => 'after',
            )
        );

        $this->add_control(
            'icon_type',
            array(
                'label'   => __('Icon Type', 'website-bio-link'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'svg'         => __('SVG Icons', 'website-bio-link'),
                    'fontawesome' => __('FontAwesome Icons', 'website-bio-link'),
                ),
                'default' => 'svg',
                'description' => __('Choose between SVG icons or FontAwesome icons', 'website-bio-link'),
            )
        );

        $this->end_controls_section();

        // =====================================================
        // STYLE TAB - Icon Colors (Normal & Hover)
        // =====================================================
        $this->start_controls_section(
            'section_icon_colors',
            array(
                'label'     => __('Icon Colors', 'website-bio-link'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'use_brand_colors!' => 'yes',
                ),
            )
        );

        $this->start_controls_tabs('icon_color_tabs');

        // Normal State
        $this->start_controls_tab(
            'icon_color_normal',
            array(
                'label' => __('Normal', 'website-bio-link'),
            )
        );

        $this->add_control(
            'icon_primary_color',
            array(
                'label'     => __('Primary Color', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#3b82f6',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-circle .wbl-social-icon-wrapper' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-rounded .wbl-social-icon-wrapper' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-flat .wbl-social-icon-wrapper' => 'border-color: {{VALUE}}; color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-minimal .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-glass .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-gradient .wbl-social-icon-wrapper' => 'background: linear-gradient(135deg, {{VALUE}} 0%, {{VALUE}}cc 100%);',
                ),
            )
        );

        $this->add_control(
            'icon_secondary_color',
            array(
                'label'     => __('Secondary Color (Icon)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-circle .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-rounded .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-gradient .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => array('circle', 'rounded', 'gradient'),
                ),
            )
        );

        $this->add_control(
            'icon_bg_color',
            array(
                'label'     => __('Background Color', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.15)',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-glass .wbl-social-icon-wrapper' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => 'glass',
                ),
            )
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'icon_color_hover',
            array(
                'label' => __('Hover', 'website-bio-link'),
            )
        );

        $this->add_control(
            'icon_primary_color_hover',
            array(
                'label'     => __('Primary Color', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-circle .wbl-social-link:hover .wbl-social-icon-wrapper' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-rounded .wbl-social-link:hover .wbl-social-icon-wrapper' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-flat .wbl-social-link:hover .wbl-social-icon-wrapper' => 'border-color: {{VALUE}}; background-color: {{VALUE}}; color: #fff;',
                    '{{WRAPPER}} .wbl-style-minimal .wbl-social-link:hover .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-glass .wbl-social-link:hover .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-gradient .wbl-social-link:hover .wbl-social-icon-wrapper' => 'background: linear-gradient(135deg, {{VALUE}} 0%, {{VALUE}}cc 100%);',
                ),
            )
        );

        $this->add_control(
            'icon_secondary_color_hover',
            array(
                'label'     => __('Secondary Color (Icon)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-circle .wbl-social-link:hover .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-rounded .wbl-social-link:hover .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-gradient .wbl-social-link:hover .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => array('circle', 'rounded', 'gradient'),
                ),
            )
        );

        $this->add_control(
            'icon_bg_color_hover',
            array(
                'label'     => __('Background Color', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.25)',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-glass .wbl-social-link:hover .wbl-social-icon-wrapper' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => 'glass',
                ),
            )
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();

        // =====================================================
        // STYLE TAB - Icon Border
        // =====================================================
        $this->start_controls_section(
            'section_icon_border',
            array(
                'label' => __('Icon Border', 'website-bio-link'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'icon_border_radius',
            array(
                'label'      => __('Border Radius', 'website-bio-link'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => array('px', '%'),
                'default'    => array(
                    'top'    => 50,
                    'right'  => 50,
                    'bottom' => 50,
                    'left'   => 50,
                    'unit'   => '%',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-icon-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            array(
                'name'      => 'icon_border',
                'label'     => __('Border', 'website-bio-link'),
                'selector'  => '{{WRAPPER}} .wbl-social-icon-wrapper',
                'condition' => array(
                    'icon_style!' => 'flat',
                ),
            )
        );

        $this->add_control(
            'flat_border_width',
            array(
                'label'      => __('Border Width', 'website-bio-link'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range'      => array(
                    'px' => array(
                        'min' => 1,
                        'max' => 10,
                    ),
                ),
                'default' => array(
                    'size' => 2,
                    'unit' => 'px',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-flat .wbl-social-icon-wrapper' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
                ),
                'condition' => array(
                    'icon_style' => 'flat',
                ),
            )
        );

        $this->end_controls_section();

        // =====================================================
        // STYLE TAB - Icon Shadow
        // =====================================================
        $this->start_controls_section(
            'section_icon_shadow',
            array(
                'label' => __('Icon Shadow', 'website-bio-link'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'icon_box_shadow',
                'label'    => __('Box Shadow', 'website-bio-link'),
                'selector' => '{{WRAPPER}} .wbl-social-icon-wrapper',
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            array(
                'name'     => 'icon_box_shadow_hover',
                'label'    => __('Box Shadow (Hover)', 'website-bio-link'),
                'selector' => '{{WRAPPER}} .wbl-social-link:hover .wbl-social-icon-wrapper',
            )
        );

        $this->end_controls_section();

        // =====================================================
        // STYLE TAB - Hover Animation
        // =====================================================
        $this->start_controls_section(
            'section_hover_animation',
            array(
                'label' => __('Hover Animation', 'website-bio-link'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'hover_animation',
            array(
                'label'   => __('Hover Animation', 'website-bio-link'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'none'       => __('None', 'website-bio-link'),
                    'grow'       => __('Grow', 'website-bio-link'),
                    'shrink'     => __('Shrink', 'website-bio-link'),
                    'pulse'      => __('Pulse', 'website-bio-link'),
                    'bounce'     => __('Bounce', 'website-bio-link'),
                    'float'      => __('Float', 'website-bio-link'),
                    'rotate'     => __('Rotate', 'website-bio-link'),
                    'wobble'     => __('Wobble', 'website-bio-link'),
                ),
                'default' => 'grow',
            )
        );

        $this->add_control(
            'transition_duration',
            array(
                'label'      => __('Transition Duration', 'website-bio-link'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('s', 'ms'),
                'range'      => array(
                    's' => array(
                        'min'  => 0,
                        'max'  => 2,
                        'step' => 0.1,
                    ),
                    'ms' => array(
                        'min'  => 0,
                        'max'  => 2000,
                        'step' => 100,
                    ),
                ),
                'default' => array(
                    'size' => 0.3,
                    'unit' => 's',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-icon-wrapper' => 'transition: all {{SIZE}}{{UNIT}} ease;',
                ),
            )
        );

        $this->end_controls_section();

        // =====================================================
        // STYLE TAB - Glassmorphism Settings
        // =====================================================
        $this->start_controls_section(
            'section_glass_settings',
            array(
                'label'     => __('Glassmorphism Settings', 'website-bio-link'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'icon_style' => 'glass',
                ),
            )
        );

        $this->add_control(
            'glass_blur',
            array(
                'label'      => __('Blur Amount', 'website-bio-link'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'default' => array(
                    'size' => 10,
                    'unit' => 'px',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-glass .wbl-social-icon-wrapper' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}});',
                ),
            )
        );

        $this->add_control(
            'glass_border_color',
            array(
                'label'     => __('Border Color', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.2)',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-glass .wbl-social-icon-wrapper' => 'border: 1px solid {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // =====================================================
        // STYLE TAB - Gradient Settings
        // =====================================================
        $this->start_controls_section(
            'section_gradient_settings',
            array(
                'label'     => __('Gradient Settings', 'website-bio-link'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'icon_style'        => 'gradient',
                    'use_brand_colors!' => 'yes',
                ),
            )
        );

        $this->add_control(
            'gradient_type',
            array(
                'label'   => __('Gradient Type', 'website-bio-link'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'linear' => __('Linear', 'website-bio-link'),
                    'radial' => __('Radial', 'website-bio-link'),
                ),
                'default' => 'linear',
            )
        );

        $this->add_control(
            'gradient_angle',
            array(
                'label'      => __('Gradient Angle', 'website-bio-link'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('deg'),
                'range'      => array(
                    'deg' => array(
                        'min' => 0,
                        'max' => 360,
                    ),
                ),
                'default' => array(
                    'size' => 135,
                    'unit' => 'deg',
                ),
                'condition' => array(
                    'gradient_type' => 'linear',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-gradient .wbl-social-icon-wrapper' => 'background: linear-gradient({{SIZE}}{{UNIT}}, var(--gradient-start) 0%, var(--gradient-end) 100%);',
                ),
            )
        );

        $this->add_control(
            'gradient_color_start',
            array(
                'label'     => __('Start Color', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#3b82f6',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-gradient .wbl-social-icon-wrapper' => '--gradient-start: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'gradient_color_end',
            array(
                'label'     => __('End Color', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#8b5cf6',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-gradient .wbl-social-icon-wrapper' => '--gradient-end: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        // =====================================================
        // STYLE TAB - Label Typography
        // =====================================================
        $this->start_controls_section(
            'section_label_style',
            array(
                'label'     => __('Label Style', 'website-bio-link'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'show_label' => 'true',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'label_typography',
                'label'    => __('Typography', 'website-bio-link'),
                'selector' => '{{WRAPPER}} .wbl-social-label',
            )
        );

        $this->add_control(
            'label_color',
            array(
                'label'     => __('Color', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#374151',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-label' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'label_color_hover',
            array(
                'label'     => __('Hover Color', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#1f2937',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-link:hover .wbl-social-label' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'label_spacing',
            array(
                'label'      => __('Spacing', 'website-bio-link'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px', 'em'),
                'range'      => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                    'em' => array(
                        'min' => 0,
                        'max' => 3,
                    ),
                ),
                'default' => array(
                    'size' => 8,
                    'unit' => 'px',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-link' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    /**
     * Get all social sets for dropdown
     */
    private function get_social_sets()
    {
        $sets = array();

        // Check if function exists before using
        if (!function_exists('get_posts')) {
            $sets[''] = __('WordPress functions not available', 'website-bio-link');
            return $sets;
        }

        $args = array(
            'post_type'      => 'sky_social_set',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        );

        $posts = get_posts($args);

        if (!empty($posts) && !is_wp_error($posts)) {
            foreach ($posts as $post) {
                $sets[$post->ID] = $post->post_title;
            }
        }

        if (empty($sets)) {
            $sets[''] = __('No social sets found. Please create one first.', 'website-bio-link');
        }

        return $sets;
    }

    /**
     * Render widget output on the frontend
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $social_set_id = !empty($settings['social_set_id']) ? intval($settings['social_set_id']) : 0;

        if (!$social_set_id) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="wbl-elementor-notice" style="padding: 20px; background: #f3f4f6; border-left: 4px solid #3b82f6; border-radius: 4px;">';
                echo '<p style="margin: 0; color: #374151;"><strong>' . esc_html__('Please select a social set', 'website-bio-link') . '</strong></p>';
                echo '<p style="margin: 8px 0 0; color: #6b7280; font-size: 14px;">' . esc_html__('Go to Content → Social Set and choose which set to display.', 'website-bio-link') . '</p>';
                echo '</div>';
            }
            return;
        }

        // Get social items with error checking
        $social_items = get_post_meta($social_set_id, '_sky_social_items', true);

        if (empty($social_items) || !is_array($social_items)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="wbl-elementor-notice" style="padding: 20px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px;">';
                echo '<p style="margin: 0; color: #92400e;"><strong>' . esc_html__('This social set is empty', 'website-bio-link') . '</strong></p>';
                echo '<p style="margin: 8px 0 0; color: #78350f; font-size: 14px;">' . esc_html__('Please add social links to this set in WordPress admin.', 'website-bio-link') . '</p>';
                echo '</div>';
            }
            return;
        }

        // Build wrapper classes
        $icon_style = !empty($settings['icon_style']) ? $settings['icon_style'] : 'circle';
        $hover_animation = !empty($settings['hover_animation']) ? $settings['hover_animation'] : 'grow';
        $use_brand_colors = $settings['use_brand_colors'] === 'yes';
        $icon_type = !empty($settings['icon_type']) ? $settings['icon_type'] : 'svg';

        $wrapper_classes = array(
            'wbl-social-widget',
            'wbl-social-style-' . $icon_style,
            'wbl-hover-' . $hover_animation,
        );

        if ($use_brand_colors) {
            $wrapper_classes[] = 'wbl-brand-colors';
        }

        // Prepare arguments with proper sanitization
        $args = array(
            'show_label'       => isset($settings['show_label']) && $settings['show_label'] === 'true',
            'style'            => $icon_style,
            'use_brand_colors' => $use_brand_colors,
            'hover_animation'  => $hover_animation,
            'icon_type'        => $icon_type,
            'wrapper_classes'  => implode(' ', $wrapper_classes),
        );

        // Render output
        echo '<div class="' . esc_attr($args['wrapper_classes']) . '">';

        // Render using the renderer class with proper checks
        if (class_exists('WBL_Social_Renderer') && method_exists('WBL_Social_Renderer', 'instance')) {
            $renderer = WBL_Social_Renderer::instance();
            if (method_exists($renderer, 'render_social_list')) {
                echo $renderer->render_social_list($social_items, $args);
            } else {
                $this->render_fallback_social_list($social_items, $args);
            }
        } else {
            $this->render_fallback_social_list($social_items, $args);
        }

        echo '</div>';

        // Add hover animation styles
        $this->render_hover_animation_styles($hover_animation);
    }

    /**
     * Fallback render method when WBL_Social_Renderer is not available
     */
    private function render_fallback_social_list($social_items, $args)
    {
        $brand_colors = array(
            'facebook'  => '#1877F2',
            'instagram' => '#E4405F',
            'twitter'   => '#1DA1F2',
            'youtube'   => '#FF0000',
            'linkedin'  => '#0A66C2',
            'tiktok'    => '#000000',
            'pinterest' => '#E60023',
            'snapchat'  => '#FFFC00',
            'whatsapp'  => '#25D366',
            'telegram'  => '#0088CC',
            'discord'   => '#5865F2',
            'github'    => '#181717',
            'dribbble'  => '#EA4C89',
            'behance'   => '#1769FF',
            'medium'    => '#000000',
            'spotify'   => '#1DB954',
            'twitch'    => '#9146FF',
            'reddit'    => '#FF4500',
            'line'      => '#00C300',
        );

        echo '<ul class="wbl-social-list">';

        foreach ($social_items as $item) {
            $platform = isset($item['platform']) ? $item['platform'] : '';
            $url = isset($item['url']) ? $item['url'] : '#';
            $label = isset($item['label']) ? $item['label'] : ucfirst($platform);

            $brand_color = isset($brand_colors[$platform]) ? $brand_colors[$platform] : '#3b82f6';
            $style_attr = $args['use_brand_colors'] ? 'style="--brand-color: ' . esc_attr($brand_color) . ';"' : '';

            // Get platform data for icon
            $platform_data = WBL_Social_Config::get_platform_by_slug($platform);
            $icon_html = '';

            if ($platform_data) {
                if ($args['icon_type'] === 'svg' && isset($platform_data['svg_icon'])) {
                    // Use SVG icon
                    $svg_icons = WBL_SVG_Icons::instance();
                    $icon_html = $svg_icons->get_svg_icon($platform_data['svg_icon'], array(
                        'class' => 'wbl-social-icon',
                        'width' => '24',
                        'height' => '24',
                    ));
                } elseif (isset($platform_data['icon_class'])) {
                    // Use FontAwesome as fallback
                    $icon_html = '<i class="' . esc_attr($platform_data['icon_class']) . '"></i>';
                }
            }

            if (empty($icon_html)) {
                continue;
            }

            echo '<li class="wbl-social-item">';
            echo '<a href="' . esc_url($url) . '" class="wbl-social-link" target="_blank" rel="noopener noreferrer">';
            echo '<span class="wbl-social-icon-wrapper" ' . $style_attr . '>';
            echo $icon_html;
            echo '</span>';

            if ($args['show_label']) {
                echo '<span class="wbl-social-label">' . esc_html($label) . '</span>';
            }

            echo '</a>';
            echo '</li>';
        }

        echo '</ul>';
    }

    /**
     * Render hover animation CSS
     */
    private function render_hover_animation_styles($animation)
    {
        if ($animation === 'none') {
            return;
        }

        $css = '<style>';

        switch ($animation) {
            case 'grow':
                $css .= '
                    .wbl-hover-grow .wbl-social-link:hover .wbl-social-icon-wrapper {
                        transform: scale(1.1);
                    }
                ';
                break;

            case 'shrink':
                $css .= '
                    .wbl-hover-shrink .wbl-social-link:hover .wbl-social-icon-wrapper {
                        transform: scale(0.9);
                    }
                ';
                break;

            case 'pulse':
                $css .= '
                    @keyframes wbl-pulse {
                        0% { transform: scale(1); }
                        50% { transform: scale(1.1); }
                        100% { transform: scale(1); }
                    }
                    .wbl-hover-pulse .wbl-social-link:hover .wbl-social-icon-wrapper {
                        animation: wbl-pulse 0.5s ease-in-out;
                    }
                ';
                break;

            case 'bounce':
                $css .= '
                    @keyframes wbl-bounce {
                        0%, 100% { transform: translateY(0); }
                        50% { transform: translateY(-10px); }
                    }
                    .wbl-hover-bounce .wbl-social-link:hover .wbl-social-icon-wrapper {
                        animation: wbl-bounce 0.5s ease;
                    }
                ';
                break;

            case 'float':
                $css .= '
                    .wbl-hover-float .wbl-social-link:hover .wbl-social-icon-wrapper {
                        transform: translateY(-5px);
                    }
                ';
                break;

            case 'rotate':
                $css .= '
                    .wbl-hover-rotate .wbl-social-link:hover .wbl-social-icon-wrapper {
                        transform: rotate(360deg);
                    }
                ';
                break;

            case 'wobble':
                $css .= '
                    @keyframes wbl-wobble {
                        0% { transform: rotate(0deg); }
                        25% { transform: rotate(-10deg); }
                        50% { transform: rotate(10deg); }
                        75% { transform: rotate(-5deg); }
                        100% { transform: rotate(0deg); }
                    }
                    .wbl-hover-wobble .wbl-social-link:hover .wbl-social-icon-wrapper {
                        animation: wbl-wobble 0.5s ease;
                    }
                ';
                break;
        }

        $css .= '</style>';
        echo $css;
    }

    /**
     * Render widget output in the editor (JavaScript template)
     */
    protected function content_template()
    {
?>
        <#
            // Get settings
            var iconStyle=settings.icon_style || 'circle' ;
            var hoverAnimation=settings.hover_animation || 'grow' ;
            var showLabel=settings.show_label==='true' ;
            var useBrandColors=settings.use_brand_colors==='yes' ;
            var iconType=settings.icon_type || 'svg' ;

            // Build wrapper classes - must match CSS class names
            var wrapperClasses=[ 'wbl-social-widget' , 'wbl-social-style-' + iconStyle, 'wbl-hover-' + hoverAnimation
            ];

            if (useBrandColors) {
            wrapperClasses.push('wbl-brand-colors');
            }

            // Check if social set is selected
            if (!settings.social_set_id) {
            #>
            <div class="wbl-elementor-notice" style="padding: 20px; background: #f3f4f6; border-left: 4px solid #3b82f6; border-radius: 4px;">
                <p style="margin: 0; color: #374151;"><strong><?php echo esc_html__('Please select a social set', 'website-bio-link'); ?></strong></p>
                <p style="margin: 8px 0 0; color: #6b7280; font-size: 14px;"><?php echo esc_html__('Go to Content → Social Set and choose which set to display.', 'website-bio-link'); ?></p>
            </div>
            <#
                } else {
                // Sample preview icons
                var sampleIcons=[
                { platform: 'facebook' , icon: 'fa-brands fa-facebook' , color: '#1877F2' },
                { platform: 'instagram' , icon: 'fa-brands fa-instagram' , color: '#E4405F' },
                { platform: 'twitter' , icon: 'fa-brands fa-x-twitter' , color: '#000000' },
                { platform: 'youtube' , icon: 'fa-brands fa-youtube' , color: '#FF0000' },
                { platform: 'linkedin' , icon: 'fa-brands fa-linkedin' , color: '#0A66C2' }
                ];
                #>
                <div class="{{ wrapperClasses.join(' ') }}">
                    <ul class="wbl-social-list">
                        <# _.each(sampleIcons, function(item) {
                            var brandColor=useBrandColors ? item.color : (settings.icon_primary_color || '#3b82f6' );
                            #>
                            <li class="wbl-social-item">
                                <a href="#" class="wbl-social-link" style="--brand-color: {{ brandColor }};">
                                    <span class="wbl-social-icon-wrapper">
                                        <# if (iconType==='svg' ) { #>
                                            <svg role="img" viewBox="0 0 24 24" class="wbl-social-icon" aria-hidden="true" fill="currentColor">
                                                <# if (item.platform==='facebook' ) { #>
                                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                                    <# } else if (item.platform==='instagram' ) { #>
                                                        <path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" />
                                                        <# } else if (item.platform==='twitter' ) { #>
                                                            <path d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z" />
                                                            <# } else if (item.platform==='youtube' ) { #>
                                                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                                                                <# } else if (item.platform==='linkedin' ) { #>
                                                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
                                                                    <# } #>
                                            </svg>
                                            <# } else { #>
                                                <i class="{{ item.icon }}" aria-hidden="true"></i>
                                                <# } #>
                                    </span>
                                    <# if (showLabel) { #>
                                        <span class="wbl-social-label" style="text-transform: capitalize;">{{ item.platform }}</span>
                                        <# } #>
                                </a>
                            </li>
                            <# }); #>
                    </ul>
                    <p style="margin-top: 12px; font-size: 12px; color: #9ca3af; font-style: italic;">
                        <?php echo esc_html__('Preview mode - Actual icons from your selected social set will appear on the frontend', 'website-bio-link'); ?>
                    </p>
                </div>
                <#
                    }
                    #>
            <?php
        }
    }
