<?php

/**
 * Test Elementor Widget
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
 * Test Widget for Elementor
 */
class WBL_Test_Widget extends \Elementor\Widget_Base
{

    /**
     * Get widget name
     */
    public function get_name()
    {
        return 'wbl_test_widget';
    }

    /**
     * Get widget title
     */
    public function get_title()
    {
        return __('Test Widget', 'website-bio-link');
    }

    /**
     * Get widget icon
     */
    public function get_icon()
    {
        return 'eicon-code';
    }

    /**
     * Get widget categories
     */
    public function get_categories()
    {
        return array('website-bio-link');
    }

    /**
     * Register widget controls
     */
    protected function register_controls()
    {
        $this->start_controls_section(
            'section_content',
            array(
                'label' => __('Content', 'website-bio-link'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'test_text',
            array(
                'label'       => __('Test Text', 'website-bio-link'),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'default'     => 'Hello World',
                'label_block' => true,
            )
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $test_text = isset($settings['test_text']) ? $settings['test_text'] : 'Hello World';

        echo '<div class="wbl-test-widget">';
        echo '<h3>' . esc_html($test_text) . '</h3>';
        echo '<p>This is a test widget to check if Elementor integration is working.</p>';
        echo '</div>';
    }

    /**
     * Render widget output in the editor
     */
    protected function content_template()
    {
        // Empty template - widget will use render() method
    }
}
