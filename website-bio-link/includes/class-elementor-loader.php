<?php

/**
 * Elementor Widgets Loader
 *
 * @package Website_Bio_Link
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Main Elementor Widgets Class
 */
class WBL_Elementor_Widgets
{

    /**
     * Single instance
     *
     * @var WBL_Elementor_Widgets
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return WBL_Elementor_Widgets
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        // Use a later priority to ensure Elementor is fully loaded
        add_action('elementor/widgets/register', array($this, 'register_widgets'), 10);
        add_action('elementor/elements/categories_registered', array($this, 'add_elementor_category'), 10);
    }

    /**
     * Register widgets
     *
     * @param object $widgets_manager Elementor widgets manager
     */
    public function register_widgets($widgets_manager)
    {
        // Check if widgets manager is valid
        if (!is_object($widgets_manager) || !method_exists($widgets_manager, 'register')) {
            return;
        }

        // Include test widget file with error checking
        $test_widget_file = WBL_SOCIAL_PLUGIN_DIR . 'elementor-widgets/test-widget.php';
        if (!file_exists($test_widget_file)) {
            return;
        }

        require_once $test_widget_file;

        // Check if test widget class exists before registering
        if (!class_exists('\WBL_Test_Widget')) {
            return;
        }

        // Register test widget with error handling
        try {
            $widget_instance = new \WBL_Test_Widget();
            $widgets_manager->register($widget_instance);
            // Widget registered successfully
        } catch (\Throwable $e) {
            // Failed to register test widget
        }

        // Social links widget
        $widget_file = WBL_SOCIAL_PLUGIN_DIR . 'elementor-widgets/social-links-widget.php';
        if (file_exists($widget_file)) {
            require_once $widget_file;
            if (class_exists('\WBL_Social_Links_Widget')) {
                try {
                    $widget_instance = new \WBL_Social_Links_Widget();
                    $widgets_manager->register($widget_instance);
                    // Widget registered successfully
                } catch (\Throwable $e) {
                    // Failed to register social links widget
                }
            }
        }
    }

    /**
     * Add custom Elementor category
     *
     * @param object $elements_manager Elementor elements manager
     */
    public function add_elementor_category($elements_manager)
    {
        // Check if elements manager is valid
        if (!is_object($elements_manager) || !method_exists($elements_manager, 'add_category')) {
            return;
        }

        try {
            $elements_manager->add_category(
                'website-bio-link',
                array(
                    'title' => __('Website Bio Link', 'website-bio-link'),
                    'icon'  => 'fa fa-plug',
                )
            );
        } catch (\Exception $e) {
            // Failed to add category
        }
    }
}
