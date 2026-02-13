<?php

/**
 * Plugin Uninstall Handler
 *
 * Fired when the plugin is uninstalled.
 *
 * @package Website_Bio_Link
 */

// If uninstall not called from WordPress, exit
if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Delete all plugin data
 */
function wbl_social_uninstall()
{
    global $wpdb;

    // Delete all social sets
    $social_sets = get_posts(
        array(
            'post_type'      => 'wbl_social_set',
            'posts_per_page' => -1,
            'post_status'    => 'any',
        )
    );

    foreach ($social_sets as $set) {
        // Delete post meta
        delete_post_meta($set->ID, '_wbl_social_items');
        delete_post_meta($set->ID, '_wbl_social_display_settings');

        // Delete post
        wp_delete_post($set->ID, true);
    }

    // Delete plugin options
    delete_option('wbl_social_version');
    delete_option('wbl_social_activated');
    delete_option('wbl_social_settings');

    // Delete transients
    delete_transient('wbl_social_cache');
    delete_transient('wbl_total_links_count');

    // Clear any scheduled hooks
    wp_clear_scheduled_hook('wbl_social_daily_cleanup');

    // Flush rewrite rules
    flush_rewrite_rules();
}

// Run uninstall
wbl_social_uninstall();
