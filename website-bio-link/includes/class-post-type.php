<?php

/**
 * Custom Post Type Registration
 *
 * @package Website_Bio_Link
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Custom Post Type class
 */
class WBL_Social_Post_Type
{

    /**
     * Single instance
     *
     * @var WBL_Social_Post_Type
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return WBL_Social_Post_Type
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
        add_action('init', array($this, 'register_post_type'));
    }

    /**
     * Register Custom Post Type
     */
    public function register_post_type()
    {
        $labels = array(
            'name'                  => _x('Social Sets', 'Post Type General Name', 'website-bio-link'),
            'singular_name'         => _x('Social Set', 'Post Type Singular Name', 'website-bio-link'),
            'menu_name'             => __('Social Sets', 'website-bio-link'),
            'name_admin_bar'        => __('Social Set', 'website-bio-link'),
            'archives'              => __('Social Set Archives', 'website-bio-link'),
            'attributes'            => __('Social Set Attributes', 'website-bio-link'),
            'parent_item_colon'     => __('Parent Social Set:', 'website-bio-link'),
            'all_items'             => __('All Social Sets', 'website-bio-link'),
            'add_new_item'          => __('Add New Social Set', 'website-bio-link'),
            'add_new'               => __('Add New Set', 'website-bio-link'),
            'new_item'              => __('New Social Set', 'website-bio-link'),
            'edit_item'             => __('Edit Social Set', 'website-bio-link'),
            'update_item'           => __('Update Social Set', 'website-bio-link'),
            'view_item'             => __('View Social Set', 'website-bio-link'),
            'view_items'            => __('View Social Sets', 'website-bio-link'),
            'search_items'          => __('Search Social Set', 'website-bio-link'),
            'not_found'             => __('Not found', 'website-bio-link'),
            'not_found_in_trash'    => __('Not found in Trash', 'website-bio-link'),
            'featured_image'        => __('Featured Image', 'website-bio-link'),
            'set_featured_image'    => __('Set featured image', 'website-bio-link'),
            'remove_featured_image' => __('Remove featured image', 'website-bio-link'),
            'use_featured_image'    => __('Use as featured image', 'website-bio-link'),
            'insert_into_item'      => __('Insert into social set', 'website-bio-link'),
            'uploaded_to_this_item' => __('Uploaded to this social set', 'website-bio-link'),
            'items_list'            => __('Social sets list', 'website-bio-link'),
            'items_list_navigation' => __('Social sets list navigation', 'website-bio-link'),
            'filter_items_list'     => __('Filter social sets list', 'website-bio-link'),
        );

        $args = array(
            'label'                 => __('Social Set', 'website-bio-link'),
            'description'           => __('Social Media Link Sets', 'website-bio-link'),
            'labels'                => $labels,
            'supports'              => array('title'),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 25,
            'menu_icon'             => 'dashicons-share',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capability_type'       => 'post',
            'show_in_rest'          => false,
        );

        register_post_type('sky_social_set', $args);
    }
}
