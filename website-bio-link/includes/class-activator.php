<?php

/**
 * Plugin Activation Handler
 *
 * @package Website_Bio_Link
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Activation class
 */
class WBL_Activator
{

    /**
     * Run activation tasks
     */
    public static function activate()
    {
        // Register custom post type
        self::register_post_type();

        // Flush rewrite rules
        flush_rewrite_rules();

        // Set default options
        self::set_default_options();

        // Create default social set (optional)
        self::create_default_set();
    }

    /**
     * Register post type for activation
     */
    private static function register_post_type()
    {
        register_post_type(
            'wbl_social_set',
            array(
                'public' => false,
                'show_ui' => true,
            )
        );
    }

    /**
     * Set default plugin options
     */
    private static function set_default_options()
    {
        // Add default options
        add_option('wbl_social_version', WBL_SOCIAL_VERSION);
        add_option('wbl_social_activated', current_time('mysql'));

        // Set default settings
        $default_settings = array(
            'enable_fontawesome' => true,
            'enable_tailwind' => true,
            'default_style' => 'circle',
            'default_size' => 'medium',
        );

        add_option('wbl_social_settings', $default_settings);
    }

    /**
     * Create a default social set (optional)
     */
    private static function create_default_set()
    {
        // Check if any social sets exist
        $existing_sets = get_posts(
            array(
                'post_type' => 'wbl_social_set',
                'posts_per_page' => 1,
                'post_status' => 'any',
            )
        );

        // Only create if no sets exist
        if (empty($existing_sets)) {
            $default_set_id = wp_insert_post(
                array(
                    'post_title' => __('Example Social Links', 'website-bio-link'),
                    'post_type' => 'wbl_social_set',
                    'post_status' => 'publish',
                    'post_author' => get_current_user_id(),
                )
            );

            // Add example social links
            if ($default_set_id && ! is_wp_error($default_set_id)) {
                $example_links = array(
                    array(
                        'platform' => 'facebook',
                        'url' => 'https://facebook.com/yourpage',
                        'label' => 'Facebook',
                    ),
                    array(
                        'platform' => 'twitter',
                        'url' => 'https://twitter.com/yourhandle',
                        'label' => 'Twitter',
                    ),
                    array(
                        'platform' => 'instagram',
                        'url' => 'https://instagram.com/yourprofile',
                        'label' => 'Instagram',
                    ),
                );

                update_post_meta($default_set_id, '_wbl_social_items', $example_links);
            }
        }
    }
}
