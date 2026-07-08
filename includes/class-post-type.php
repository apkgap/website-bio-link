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

        // Add shortcode column to admin list
        add_filter('manage_wbl_social_set_posts_columns', array($this, 'add_shortcode_column'));
        add_action('manage_wbl_social_set_posts_custom_column', array($this, 'render_shortcode_column'), 10, 2);

        // Add shortcode metabox to single edit page
        add_action('add_meta_boxes', array($this, 'add_shortcode_metabox'));
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
            'show_in_rest'          => true,
        );

        register_post_type('wbl_social_set', $args);
    }

    /**
     * Add shortcode column to admin list
     */
    public function add_shortcode_column($columns)
    {
        $new_columns = array();

        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;

            // Add shortcode column after title
            if ($key === 'title') {
                $new_columns['shortcode'] = __('Shortcode', 'website-bio-link');
            }
        }

        return $new_columns;
    }

    /**
     * Render shortcode column content
     */
    public function render_shortcode_column($column, $post_id)
    {
        if ($column === 'shortcode') {
            $shortcode = '[wbl_socials id="' . $post_id . '"]';
            $unique_id = 'wbl-shortcode-' . $post_id;
?>
            <div style="display: flex; align-items: center; gap: 8px;">
                <input type="text"
                    id="<?php echo esc_attr($unique_id); ?>"
                    value="<?php echo esc_attr($shortcode); ?>"
                    readonly
                    onclick="this.select();"
                    style="background: #f0f0f1; padding: 4px 8px; border-radius: 3px; font-size: 12px; border: 1px solid #ddd; font-family: monospace; width: 250px;">
                <button type="button"
                    class="button button-small"
                    style="padding: 2px 8px; font-size: 11px;"
                    onclick="(function(btn){
                            var input = document.getElementById('<?php echo esc_js($unique_id); ?>');
                            input.select();
                            input.setSelectionRange(0, 99999);
                            try {
                                document.execCommand('copy');
                                btn.textContent='Copied!';
                                setTimeout(function(){ btn.textContent='Copy'; }, 1500);
                            } catch(err) {
                                alert('Copy failed. Please select and copy manually.');
                            }
                        })(this);">
                    Copy
                </button>
            </div>
        <?php
        }
    }

    /**
     * Add shortcode metabox to single edit page
     */
    public function add_shortcode_metabox()
    {
        add_meta_box(
            'wbl_shortcode_metabox',
            __('Shortcode', 'website-bio-link'),
            array($this, 'render_shortcode_metabox'),
            'wbl_social_set',
            'side',
            'high'
        );
    }

    /**
     * Render shortcode metabox
     */
    public function render_shortcode_metabox($post)
    {
        $shortcode = '[wbl_socials id="' . $post->ID . '"]';
        $input_id = 'wbl-shortcode-input-' . $post->ID;
        ?>
        <div style="padding: 10px 0;">
            <p style="margin: 0 0 8px 0; font-weight: 600;">
                <?php _e('Use this shortcode:', 'website-bio-link'); ?>
            </p>
            <div style="position: relative;">
                <input type="text"
                    id="<?php echo esc_attr($input_id); ?>"
                    value="<?php echo esc_attr($shortcode); ?>"
                    readonly
                    onclick="this.select();"
                    style="width: 100%; padding: 8px; font-family: monospace; font-size: 12px; background: #f0f0f1; border: 1px solid #ddd; border-radius: 4px;">
                <button type="button"
                    class="button button-primary"
                    onclick="(function(btn){
                            var input = document.getElementById('<?php echo esc_js($input_id); ?>');
                            input.select();
                            input.setSelectionRange(0, 99999);
                            try {
                                document.execCommand('copy');
                                btn.textContent='<?php _e('Copied!', 'website-bio-link'); ?>';
                                setTimeout(function(){ btn.textContent='<?php _e('Copy Shortcode', 'website-bio-link'); ?>'; }, 1500);
                            } catch(err) {
                                alert('<?php _e('Copy failed. Please select and copy manually.', 'website-bio-link'); ?>');
                            }
                        })(this);"
                    style="width: 100%; margin-top: 8px;">
                    <?php _e('Copy Shortcode', 'website-bio-link'); ?>
                </button>
            </div>
            <p style="margin: 12px 0 0 0; font-size: 12px; color: #666;">
                <?php _e('Copy and paste this shortcode into any post, page, or widget.', 'website-bio-link'); ?>
            </p>
        </div>
<?php
    }
}
