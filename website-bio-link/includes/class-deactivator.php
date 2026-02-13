<?php

/**
 * Plugin Deactivation Handler
 *
 * @package Website_Bio_Link
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Deactivation class
 */
class WBL_Deactivator
{

    /**
     * Run deactivation tasks
     */
    public static function deactivate()
    {
        // Flush rewrite rules
        flush_rewrite_rules();

        // Clear any scheduled events (if any)
        self::clear_scheduled_events();

        // Note: We don't delete data on deactivation
        // Data will only be deleted on uninstall
    }

    /**
     * Clear scheduled events
     */
    private static function clear_scheduled_events()
    {
        // Clear any wp-cron events if plugin uses them
        // Example: wp_clear_scheduled_hook( 'wbl_social_daily_cleanup' );
    }
}
