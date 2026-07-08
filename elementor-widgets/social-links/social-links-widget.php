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
     * Get widget scripts dependencies
     */
    /**
     * Get style dependencies
     */
    public function get_style_depends()
    {
        return array('wbl-social-links-widget');
    }

    /**
     * Get script dependencies
     */
    public function get_script_depends()
    {
        return array();
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
            'layout_type',
            array(
                'label'   => __('Layout Type', 'website-bio-link'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'horizontal' => __('Horizontal (Flex Row)', 'website-bio-link'),
                    'vertical'   => __('Vertical (Flex Column)', 'website-bio-link'),
                    'inline'     => __('Inline', 'website-bio-link'),
                    'grid'       => __('Grid', 'website-bio-link'),
                ),
                'default' => 'horizontal',
                'selectors_dictionary' => array(
                    'horizontal' => 'flex',
                    'vertical'   => 'flex',
                    'inline'     => 'inline-flex',
                    'grid'       => 'grid',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-list' => 'display: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'flex_direction',
            array(
                'label'   => __('Direction', 'website-bio-link'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'row'    => __('Row', 'website-bio-link'),
                    'column' => __('Column', 'website-bio-link'),
                ),
                'default' => 'row',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-list' => 'flex-direction: {{VALUE}};',
                ),
                'condition' => array(
                    'layout_type' => array('horizontal', 'vertical', 'inline'),
                ),
            )
        );

        $this->add_responsive_control(
            'grid_columns',
            array(
                'label'      => __('Grid Columns', 'website-bio-link'),
                'type'       => \Elementor\Controls_Manager::NUMBER,
                'min'        => 1,
                'max'        => 12,
                'default'    => 3,
                'selectors'  => array(
                    '{{WRAPPER}} .wbl-social-list' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
                ),
                'condition' => array(
                    'layout_type' => 'grid',
                ),
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
                'condition' => array(
                    'layout_type!' => 'grid',
                ),
            )
        );

        $this->add_responsive_control(
            'grid_align',
            array(
                'label'   => __('Grid Alignment', 'website-bio-link'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'start'  => __('Start', 'website-bio-link'),
                    'center' => __('Center', 'website-bio-link'),
                    'end'    => __('End', 'website-bio-link'),
                ),
                'default' => 'start',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-list' => 'justify-items: {{VALUE}};',
                ),
                'condition' => array(
                    'layout_type' => 'grid',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_size_preset',
            array(
                'label'   => __('Icon Size Preset', 'website-bio-link'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'custom' => __('Custom', 'website-bio-link'),
                    'small'  => __('Small (16px)', 'website-bio-link'),
                    'medium' => __('Medium (20px)', 'website-bio-link'),
                    'large'  => __('Large (28px)', 'website-bio-link'),
                    'xlarge' => __('Extra Large (36px)', 'website-bio-link'),
                ),
                'default' => 'custom',
                'description' => __('Choose a preset size or select Custom to use slider below', 'website-bio-link'),
                'selectors_dictionary' => array(
                    'small'  => '16px',
                    'medium' => '20px',
                    'large'  => '28px',
                    'xlarge' => '36px',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-icon-wrapper i' => 'font-size: {{VALUE}};',
                    '{{WRAPPER}} .wbl-social-icon-wrapper svg' => 'width: {{VALUE}}; height: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'icon_size',
            array(
                'label'      => __('Custom Icon Size', 'website-bio-link'),
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
                'condition' => array(
                    'icon_size_preset' => 'custom',
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
            'gap_preset',
            array(
                'label'   => __('Gap Preset', 'website-bio-link'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'custom' => __('Custom', 'website-bio-link'),
                    'small'  => __('Small (8px)', 'website-bio-link'),
                    'medium' => __('Medium (16px)', 'website-bio-link'),
                    'large'  => __('Large (24px)', 'website-bio-link'),
                    'xlarge' => __('Extra Large (32px)', 'website-bio-link'),
                ),
                'default' => 'custom',
                'description' => __('Choose a preset gap or select Custom to use slider below', 'website-bio-link'),
                'selectors_dictionary' => array(
                    'small'  => '8px',
                    'medium' => '16px',
                    'large'  => '24px',
                    'xlarge' => '32px',
                ),
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-list' => 'gap: {{VALUE}};',
                ),
            )
        );

        $this->add_responsive_control(
            'gap',
            array(
                'label'      => __('Custom Gap Between Icons', 'website-bio-link'),
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
                'condition' => array(
                    'gap_preset' => 'custom',
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
            'color_source',
            array(
                'label'   => __('Color Source', 'website-bio-link'),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'brand'    => __('Brand Colors (Official Platform Colors)', 'website-bio-link'),
                    'settings' => __('Default Colors (From Settings)', 'website-bio-link'),
                    'custom'   => __('Custom Colors (Override All)', 'website-bio-link'),
                ),
                'default' => 'brand',
                'description' => __('Priority: Custom > Settings > Brand. Choose your color source.', 'website-bio-link'),
                'separator' => 'before',
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
                'description'  => __('DEPRECATED: Use "Color Source" above instead. This will be removed in future versions.', 'website-bio-link'),
                'separator'    => 'after',
            )
        );

        $this->end_controls_section();

        // =====================================================
        // STYLE TAB - Icon Colors (Normal & Hover)
        // =====================================================
        $this->start_controls_section(
            'section_icon_colors',
            array(
                'label'     => __('Custom Icon Colors', 'website-bio-link'),
                'tab'       => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => array(
                    'color_source' => 'custom',
                ),
            )
        );

        $this->add_control(
            'custom_colors_note',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => __('<strong>💡 Smart Color System:</strong> Color fields will adapt based on your selected Icon Style. Each style has different color requirements.', 'website-bio-link'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            )
        );

        // Warning when no icon style selected
        $this->add_control(
            'no_style_warning',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => __('<strong>⚠️ Note:</strong> Please select an Icon Style in the "Style Preset" section above to configure custom colors.', 'website-bio-link'),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
                'condition' => array(
                    'icon_style' => '',
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

        // Circle & Rounded - Primary Color (Background)
        $this->add_control(
            'circle_rounded_description',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => __('<strong>Circle & Rounded Square:</strong><br>Primary = Background, Secondary = Icon Color', 'website-bio-link'),
                'content_classes' => 'elementor-descriptor',
                'condition' => array(
                    'icon_style' => array('circle', 'rounded'),
                ),
            )
        );

        $this->add_control(
            'icon_primary_color',
            array(
                'label'     => __('Primary Color (Background)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#3b82f6',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-circle .wbl-social-icon-wrapper' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-style-rounded .wbl-social-icon-wrapper' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => array('circle', 'rounded'),
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
                ),
                'condition' => array(
                    'icon_style' => array('circle', 'rounded'),
                ),
            )
        );

        // Flat - Primary Color (Border & Icon)
        $this->add_control(
            'flat_description',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => __('<strong>Flat Outline:</strong><br>Primary = Border & Icon Color', 'website-bio-link'),
                'content_classes' => 'elementor-descriptor',
                'condition' => array(
                    'icon_style' => 'flat',
                ),
            )
        );

        $this->add_control(
            'flat_primary_color',
            array(
                'label'     => __('Primary Color (Border & Icon)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#3b82f6',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-flat .wbl-social-icon-wrapper' => 'border-color: {{VALUE}}; color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => 'flat',
                ),
            )
        );

        // Minimal - Primary Color (Icon only)
        $this->add_control(
            'minimal_description',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => __('<strong>Minimal:</strong><br>Primary = Icon Color only', 'website-bio-link'),
                'content_classes' => 'elementor-descriptor',
                'condition' => array(
                    'icon_style' => 'minimal',
                ),
            )
        );

        $this->add_control(
            'minimal_primary_color',
            array(
                'label'     => __('Primary Color (Icon)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#3b82f6',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-minimal .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => 'minimal',
                ),
            )
        );

        // Glassmorphism - Icon & Background
        $this->add_control(
            'glass_description',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => __('<strong>Glassmorphism:</strong><br>Primary = Icon, Secondary = Background (use rgba)', 'website-bio-link'),
                'content_classes' => 'elementor-descriptor',
                'condition' => array(
                    'icon_style' => 'glass',
                ),
            )
        );

        $this->add_control(
            'glass_primary_color',
            array(
                'label'     => __('Primary Color (Icon)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-glass .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => 'glass',
                ),
            )
        );

        $this->add_control(
            'glass_bg_color',
            array(
                'label'     => __('Secondary Color (Background)', 'website-bio-link'),
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

        // Gradient - Start & End Colors
        $this->add_control(
            'gradient_description',
            array(
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw'  => __('<strong>Gradient:</strong><br>Primary = Start Color, Secondary = End Color', 'website-bio-link'),
                'content_classes' => 'elementor-descriptor',
                'condition' => array(
                    'icon_style' => 'gradient',
                ),
            )
        );

        $this->add_control(
            'gradient_start_color',
            array(
                'label'     => __('Primary Color (Gradient Start)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#3b82f6',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-gradient .wbl-social-icon-wrapper' => 'background: linear-gradient(135deg, {{VALUE}} 0%, {{icon_gradient_end_color.VALUE}} 100%);',
                ),
                'condition' => array(
                    'icon_style' => 'gradient',
                ),
            )
        );

        $this->add_control(
            'gradient_end_color',
            array(
                'label'     => __('Secondary Color (Gradient End)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#8b5cf6',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-style-gradient .wbl-social-icon-wrapper' => 'background: linear-gradient(135deg, {{gradient_start_color.VALUE}} 0%, {{VALUE}} 100%);',
                ),
                'condition' => array(
                    'icon_style' => 'gradient',
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

        // Circle & Rounded - Hover Colors
        $this->add_control(
            'icon_primary_color_hover',
            array(
                'label'     => __('Hover Primary (Background)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-style-circle .wbl-social-link:hover .wbl-social-icon-wrapper' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-social-style-rounded .wbl-social-link:hover .wbl-social-icon-wrapper' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => array('circle', 'rounded'),
                ),
            )
        );

        $this->add_control(
            'icon_secondary_color_hover',
            array(
                'label'     => __('Hover Secondary (Icon)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-style-circle .wbl-social-link:hover .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .wbl-social-style-rounded .wbl-social-link:hover .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => array('circle', 'rounded'),
                ),
            )
        );

        // Flat - Hover Colors
        $this->add_control(
            'flat_hover_primary_color',
            array(
                'label'     => __('Hover Primary (Background)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#3b82f6',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-style-flat .wbl-social-link:hover .wbl-social-icon-wrapper' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => 'flat',
                ),
            )
        );

        $this->add_control(
            'flat_hover_secondary_color',
            array(
                'label'     => __('Hover Secondary (Icon)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-style-flat .wbl-social-link:hover .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => 'flat',
                ),
            )
        );

        // Minimal - Hover Color
        $this->add_control(
            'minimal_hover_color',
            array(
                'label'     => __('Hover Primary (Icon)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-style-minimal .wbl-social-link:hover .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => 'minimal',
                ),
            )
        );

        // Glass - Hover Colors
        $this->add_control(
            'glass_hover_primary_color',
            array(
                'label'     => __('Hover Primary (Icon)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#ffffff',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-style-glass .wbl-social-link:hover .wbl-social-icon-wrapper' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => 'glass',
                ),
            )
        );

        $this->add_control(
            'glass_hover_bg_color',
            array(
                'label'     => __('Hover Secondary (Background)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.25)',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-style-glass .wbl-social-link:hover .wbl-social-icon-wrapper' => 'background-color: {{VALUE}};',
                ),
                'condition' => array(
                    'icon_style' => 'glass',
                ),
            )
        );

        // Gradient - Hover Colors
        $this->add_control(
            'gradient_hover_start_color',
            array(
                'label'     => __('Hover Primary (Gradient Start)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#2563eb',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-style-gradient .wbl-social-link:hover .wbl-social-icon-wrapper' => 'background: linear-gradient(135deg, {{VALUE}} 0%, {{gradient_hover_end_color.VALUE}} 100%);',
                ),
                'condition' => array(
                    'icon_style' => 'gradient',
                ),
            )
        );

        $this->add_control(
            'gradient_hover_end_color',
            array(
                'label'     => __('Hover Secondary (Gradient End)', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#7c3aed',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-style-gradient .wbl-social-link:hover .wbl-social-icon-wrapper' => 'background: linear-gradient(135deg, {{gradient_hover_start_color.VALUE}} 0%, {{VALUE}} 100%);',
                ),
                'condition' => array(
                    'icon_style' => 'gradient',
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

        $this->add_responsive_control(
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
                    '{{WRAPPER}} .wbl-social-style-flat .wbl-social-icon-wrapper' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
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
                    '{{WRAPPER}} .wbl-social-style-glass .wbl-social-icon-wrapper' => 'backdrop-filter: blur({{SIZE}}{{UNIT}}); -webkit-backdrop-filter: blur({{SIZE}}{{UNIT}});',
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
                    '{{WRAPPER}} .wbl-social-style-glass .wbl-social-icon-wrapper' => 'border: 1px solid {{VALUE}};',
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
                    'icon_style' => 'gradient',
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
                    '{{WRAPPER}} .wbl-social-style-gradient .wbl-social-icon-wrapper' => 'background: linear-gradient({{SIZE}}{{UNIT}}, var(--gradient-start) 0%, var(--gradient-end) 100%);',
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
                    '{{WRAPPER}} .wbl-social-style-gradient .wbl-social-icon-wrapper' => '--gradient-start: {{VALUE}};',
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
                    '{{WRAPPER}} .wbl-social-style-gradient .wbl-social-icon-wrapper' => '--gradient-end: {{VALUE}};',
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
        $this->add_control(
            'glass_border_color',
            array(
                'label'     => __('Border Color', 'website-bio-link'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => 'rgba(255, 255, 255, 0.2)',
                'selectors' => array(
                    '{{WRAPPER}} .wbl-social-style-glass .wbl-social-icon-wrapper' => 'border: 1px solid {{VALUE}};',
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
                    'icon_style' => 'gradient',
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
                    '{{WRAPPER}} .wbl-social-style-gradient .wbl-social-icon-wrapper' => 'background: linear-gradient({{SIZE}}{{UNIT}}, var(--gradient-start) 0%, var(--gradient-end) 100%);',
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
                    '{{WRAPPER}} .wbl-social-style-gradient .wbl-social-icon-wrapper' => '--gradient-start: {{VALUE}};',
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
                    '{{WRAPPER}} .wbl-social-style-gradient .wbl-social-icon-wrapper' => '--gradient-end: {{VALUE}};',
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

    private function get_social_sets()
    {
        // Use static cache to prevent repeated queries
        static $cached_sets = null;

        if ($cached_sets !== null) {
            return $cached_sets;
        }

        $sets = array();

        // Check if function exists before using
        if (!function_exists('get_posts')) {
            $sets[''] = __('WordPress functions not available', 'website-bio-link');
            $cached_sets = $sets;
            return $sets;
        }

        // Limit to 100 posts to prevent memory issues
        $args = array(
            'post_type'      => 'wbl_social_set',
            'posts_per_page' => 100,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
            'fields'         => 'ids', // Only get IDs to save memory
            'no_found_rows'  => true,  // Skip pagination count
            'update_post_meta_cache' => false, // Skip meta cache
            'update_post_term_cache' => false, // Skip term cache
        );

        $post_ids = get_posts($args);

        if (!empty($post_ids) && !is_wp_error($post_ids)) {
            foreach ($post_ids as $post_id) {
                $sets[$post_id] = get_the_title($post_id);
            }
        }

        if (empty($sets)) {
            $sets[''] = __('No social sets found. Please create one first.', 'website-bio-link');
        }

        // Cache the result
        $cached_sets = $sets;
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
                echo '</div>';
            }
            return;
        }

        $social_items = get_post_meta($social_set_id, '_wbl_social_items', true);

        if (empty($social_items) || !is_array($social_items)) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<div class="wbl-elementor-notice" style="padding: 20px; background: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 4px;">';
                echo '<p style="margin: 0; color: #92400e;"><strong>' . esc_html__('This social set is empty', 'website-bio-link') . '</strong></p>';
                echo '</div>';
            }
            return;
        }

        $icon_style = !empty($settings['icon_style']) ? $settings['icon_style'] : 'circle';
        $hover_animation = !empty($settings['hover_animation']) ? $settings['hover_animation'] : 'none';
        $show_label = $settings['show_label'] === 'true';

        $wrapper_classes = array(
            'wbl-social-widget',
            'wbl-social-style-' . $icon_style,
            'wbl-hover-' . $hover_animation
        );

        $args = array(
            'show_label' => $show_label,
        );

        // Load widget styles
        wp_enqueue_style('wbl-social-links-widget');

        echo '<div class="' . esc_attr(implode(' ', $wrapper_classes)) . '">';
        echo '<ul class="wbl-social-list">';
        foreach ($social_items as $item) {
            $card_path = WBL_SOCIAL_PLUGIN_DIR . 'elementor-widgets/social-links/card.php';
            if (file_exists($card_path)) {
                include $card_path;
            }
        }
        echo '</ul>';
        echo '</div>';
    }

    /**
     * Render widget output in the editor (JavaScript template)
     */
    protected function content_template()
    {
?>
        <#
            var iconStyle = settings.icon_style || 'circle';
            var hoverAnimation = settings.hover_animation || 'none';
            var showLabel = settings.show_label === 'true';

            var wrapperClasses = [
                'wbl-social-widget',
                'wbl-social-style-' + iconStyle,
                'wbl-hover-' + hoverAnimation
            ];

            if (!settings.social_set_id) {
        #>
            <div class="wbl-elementor-notice" style="padding: 20px; background: #f3f4f6; border-left: 4px solid #3b82f6; border-radius: 4px;">
                <p style="margin: 0; color: #374151;"><strong><?php echo esc_html__('Please select a social set', 'website-bio-link'); ?></strong></p>
            </div>
        <# } else { #>
            <div class="{{ wrapperClasses.join(' ') }}">
                <ul class="wbl-social-list">
                    <li class="wbl-social-item">
                        <a href="#" class="wbl-social-link">
                            <span class="wbl-social-icon-wrapper" style="--brand-color: #1877F2;">
                                <svg viewBox="0 0 24 24" class="wbl-social-icon" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" /></svg>
                            </span>
                            <# if (showLabel) { #>
                                <span class="wbl-social-label">Facebook</span>
                            <# } #>
                        </a>
                    </li>
                    <li class="wbl-social-item">
                        <a href="#" class="wbl-social-link">
                            <span class="wbl-social-icon-wrapper" style="--brand-color: #E4405F;">
                                <svg viewBox="0 0 24 24" class="wbl-social-icon" fill="currentColor"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z" /></svg>
                            </span>
                            <# if (showLabel) { #>
                                <span class="wbl-social-label">Instagram</span>
                            <# } #>
                        </a>
                    </li>
                </ul>
            </div>
        <# } #>
<?php
    }
}
