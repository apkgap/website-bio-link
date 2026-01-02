<?php

/**
 * Plugin Name: Website Bio Link
 * Plugin URI: https://example.com
 * Description: Create and manage social media link sets with Custom Post Type, Shortcodes, and Elementor Widget support.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * Text Domain: website-bio-link
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WBL_SOCIAL_VERSION', '1.0.0');
define('WBL_SOCIAL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WBL_SOCIAL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WBL_SOCIAL_PLUGIN_FILE', __FILE__);

/**
 * Activation Hook
 */
function activate_wbl_social()
{
    require_once WBL_SOCIAL_PLUGIN_DIR . 'includes/class-activator.php';
    WBL_Activator::activate();
}
register_activation_hook(__FILE__, 'activate_wbl_social');

/**
 * Deactivation Hook
 */
function deactivate_wbl_social()
{
    require_once WBL_SOCIAL_PLUGIN_DIR . 'includes/class-deactivator.php';
    WBL_Deactivator::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_wbl_social');

/**
 * Main Website Bio Link Plugin Class
 */
final class Website_Bio_Link_Social
{

    /**
     * Single instance of the class
     *
     * @var Website_Bio_Link_Social
     */
    private static $instance = null;

    /**
     * Get single instance
     *
     * @return Website_Bio_Link_Social
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
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Include required files
     */
    private function includes()
    {
        // Core classes with error checking
        $core_files = array(
            'class-config.php',
            'class-svg-icons.php',
            'class-post-type.php',
            'class-meta-box.php',
            'class-renderer.php',
            'class-settings.php',
            'class-wordpress-widget.php',
        );

        foreach ($core_files as $file) {
            $file_path = WBL_SOCIAL_PLUGIN_DIR . 'includes/' . $file;
            if (file_exists($file_path)) {
                require_once $file_path;
            } else {
                error_log('Website Bio Link: Core file not found - ' . $file);
            }
        }
    }

    /**
     * Initialize hooks
     */
    private function init_hooks()
    {
        add_action('init', array($this, 'init'), 0);
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
    }

    /**
     * Initialize plugin
     */
    public function init()
    {
        // Load text domain
        load_plugin_textdomain('website-bio-link', false, dirname(plugin_basename(__FILE__)) . '/languages');

        // Initialize classes with error checking
        $classes_to_init = array(
            'WBL_Social_Post_Type',
            'WBL_Social_Meta_Box',
            'WBL_Social_Renderer',
            'WBL_Settings'
        );

        foreach ($classes_to_init as $class_name) {
            if (class_exists($class_name) && method_exists($class_name, 'instance')) {
                $class_name::instance();
            } else {
                error_log('Website Bio Link: Class ' . $class_name . ' not found or missing instance method');
            }
        }

        // Load Elementor widgets after all core classes are initialized
        $this->load_elementor_widgets();
    }

    /**
     * Load Elementor widgets
     */
    public function load_elementor_widgets()
    {
        // Check if Elementor is installed
        if (!class_exists('\Elementor\Plugin')) {
            return;
        }

        // If elementor/loaded already fired, load widgets immediately
        if (did_action('elementor/loaded')) {
            $this->init_elementor_widgets();
        } else {
            // Otherwise, wait for elementor/loaded
            add_action('elementor/loaded', array($this, 'init_elementor_widgets'));
        }
    }

    /**
     * Initialize Elementor widgets
     */
    public function init_elementor_widgets()
    {
        // Load the Elementor loader class
        $elementor_loader = WBL_SOCIAL_PLUGIN_DIR . 'includes/class-elementor-loader.php';
        if (file_exists($elementor_loader)) {
            require_once $elementor_loader;

            // Initialize the widgets loader directly
            if (class_exists('WBL_Elementor_Widgets')) {
                WBL_Elementor_Widgets::instance();
            }
        }
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function admin_scripts($hook)
    {
        global $post_type;

        // Only load on our CPT edit screen
        if (('post.php' === $hook || 'post-new.php' === $hook) && 'wbl_social_set' === $post_type) {
            // Enqueue admin CSS
            wp_enqueue_style(
                'wbl-social-admin',
                WBL_SOCIAL_PLUGIN_URL . 'assets/css/admin.css',
                array(),
                WBL_SOCIAL_VERSION
            );

            // Enqueue admin JS
            wp_enqueue_script(
                'wbl-social-admin',
                WBL_SOCIAL_PLUGIN_URL . 'assets/js/admin.js',
                array('jquery', 'jquery-ui-sortable'),
                WBL_SOCIAL_VERSION,
                true
            );

            // Localize script
            wp_localize_script(
                'wbl-social-admin',
                'wblSocialAdmin',
                array(
                    'platforms' => WBL_Social_Config::get_platforms(),
                    'confirmDelete' => __('Are you sure you want to remove this social link?', 'website-bio-link'),
                )
            );
        }
    }

    /**
     * Enqueue frontend scripts and styles
     */
    public function frontend_scripts()
    {
        // Get settings to check if we should load external assets
        $settings = $this->get_settings();

        // Enqueue FontAwesome 6 if enabled (fallback for SVG icons)
        if (!empty($settings['enable_fontawesome'])) {
            wp_enqueue_style(
                'font-awesome-6',
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
                array(),
                '6.5.1'
            );
        }

        // Enqueue TailwindCSS CDN if enabled
        if (!empty($settings['enable_tailwind'])) {
            wp_enqueue_style(
                'tailwindcss',
                'https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css',
                array(),
                '3.4.1'
            );
        }

        // Enqueue plugin styles
        wp_enqueue_style(
            'wbl-social-frontend',
            WBL_SOCIAL_PLUGIN_URL . 'assets/css/style.css',
            array(),
            WBL_SOCIAL_VERSION
        );
    }

    /**
     * Get plugin settings
     */
    private function get_settings()
    {
        if (class_exists('WBL_Settings')) {
            $settings_instance = WBL_Settings::instance();
            if (method_exists($settings_instance, 'get_settings')) {
                return $settings_instance->get_settings();
            }
        }

        // Return defaults if settings class not available
        return array(
            'enable_fontawesome' => true,
            'enable_tailwind' => true,
        );
    }
}

/**
 * Initialize the plugin
 */
function website_bio_link_social()
{
    return Website_Bio_Link_Social::instance();
}

// Start the plugin after WordPress is fully loaded
add_action('plugins_loaded', 'website_bio_link_social');
