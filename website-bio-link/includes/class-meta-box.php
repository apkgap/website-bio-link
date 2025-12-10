<?php

/**
 * Meta Box for Social Links Repeater
 *
 * @package Website_Bio_Link
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Meta Box class
 */
class WBL_Social_Meta_Box
{

    /**
     * Single instance
     *
     * @var WBL_Social_Meta_Box
     */
    private static $instance = null;

    /**
     * Meta key for storing social items
     *
     * @var string
     */
    private $meta_key = '_sky_social_items';

    /**
     * Get instance
     *
     * @return WBL_Social_Meta_Box
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
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        add_action('save_post', array($this, 'save_meta_box'), 10, 2);
    }

    /**
     * Add meta box
     */
    public function add_meta_box()
    {
        add_meta_box(
            'sky_social_links',
            __('Social Links', 'website-bio-link'),
            array($this, 'render_meta_box'),
            'sky_social_set',
            'normal',
            'high'
        );
    }

    /**
     * Render meta box content
     *
     * @param WP_Post $post Current post object
     */
    public function render_meta_box($post)
    {
        // Add nonce for security
        wp_nonce_field('WBL_Social_Meta_Box', 'WBL_Social_Meta_Box_nonce');

        // Get saved data
        $social_items = get_post_meta($post->ID, $this->meta_key, true);
        if (! is_array($social_items)) {
            $social_items = array();
        }

        // Get platforms
        $platforms = WBL_Social_Config::get_platforms();
?>

        <div class="wbl-social-repeater-wrapper">
            <div class="overflow-x-auto">
                <table class="wbl-social-repeater widefat" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider" style="width: 40px;"><?php esc_html_e('Sort', 'website-bio-link'); ?></th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider" style="width: 30%;"><?php esc_html_e('Platform', 'website-bio-link'); ?></th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider" style="width: 40%;"><?php esc_html_e('URL', 'website-bio-link'); ?></th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider" style="width: 25%;"><?php esc_html_e('Label Override', 'website-bio-link'); ?></th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider" style="width: 80px;"><?php esc_html_e('Action', 'website-bio-link'); ?></th>
                        </tr>
                    </thead>
                    <tbody class="wbl-social-repeater-body bg-white divide-y divide-gray-200">
                        <?php
                        if (! empty($social_items)) {
                            foreach ($social_items as $index => $item) {
                                $this->render_repeater_row($index, $item, $platforms);
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                <button type="button" class="wbl-social-add-row inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                    <span class="dashicons dashicons-plus-alt" style="margin-top: 3px; margin-right: 5px;"></span>
                    <?php esc_html_e('Add Social Link', 'website-bio-link'); ?>
                </button>
            </div>

            <!-- Template Row (Hidden) -->
            <table style="display: none;">
                <tbody>
                    <?php $this->render_repeater_row('{{INDEX}}', array(), $platforms, true); ?>
                </tbody>
            </table>
        </div>

        <style>
            .wbl-social-repeater-wrapper {
                margin-top: 10px;
            }

            .wbl-social-repeater tbody tr {
                cursor: move;
            }

            .wbl-social-repeater tbody tr:hover {
                background-color: #f9fafb;
            }

            .wbl-social-repeater tbody tr.ui-sortable-helper {
                background-color: #fff;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            .wbl-social-repeater select,
            .wbl-social-repeater input[type="text"],
            .wbl-social-repeater input[type="url"] {
                width: 100%;
                padding: 8px 12px;
                border: 1px solid #d1d5db;
                border-radius: 6px;
                font-size: 14px;
            }

            .wbl-social-repeater select:focus,
            .wbl-social-repeater input[type="text"]:focus,
            .wbl-social-repeater input[type="url"]:focus {
                outline: none;
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            }
        </style>

    <?php
    }

    /**
     * Render a single repeater row
     *
     * @param int|string $index Row index
     * @param array      $item  Item data
     * @param array      $platforms Available platforms
     * @param bool       $is_template Whether this is a template row
     */
    private function render_repeater_row($index, $item = array(), $platforms = array(), $is_template = false)
    {
        $platform = isset($item['platform']) ? $item['platform'] : '';
        $url = isset($item['url']) ? $item['url'] : '';
        $label = isset($item['label']) ? $item['label'] : '';

        $row_class = $is_template ? 'wbl-social-repeater-row-template' : 'wbl-social-repeater-row';
    ?>
        <tr class="<?php echo esc_attr($row_class); ?> border-b border-gray-200">
            <td class="px-4 py-3 text-center">
                <span class="dashicons dashicons-menu text-gray-400 cursor-move"></span>
            </td>
            <td class="px-4 py-3">
                <select name="sky_social_items[<?php echo esc_attr($index); ?>][platform]" class="wbl-social-platform">
                    <option value=""><?php esc_html_e('Select Platform', 'website-bio-link'); ?></option>
                    <?php foreach ($platforms as $p) : ?>
                        <option value="<?php echo esc_attr($p['slug']); ?>" <?php selected($platform, $p['slug']); ?>>
                            <?php echo esc_html($p['label']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td class="px-4 py-3">
                <input
                    type="url"
                    name="sky_social_items[<?php echo esc_attr($index); ?>][url]"
                    value="<?php echo esc_attr($url); ?>"
                    placeholder="https://example.com/profile"
                    class="wbl-social-url" />
            </td>
            <td class="px-4 py-3">
                <input
                    type="text"
                    name="sky_social_items[<?php echo esc_attr($index); ?>][label]"
                    value="<?php echo esc_attr($label); ?>"
                    placeholder="<?php esc_attr_e('Optional label', 'website-bio-link'); ?>"
                    class="wbl-social-label" />
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" class="wbl-social-remove-row inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <span class="dashicons dashicons-trash" style="font-size: 16px; width: 16px; height: 16px;"></span>
                </button>
            </td>
        </tr>
<?php
    }

    /**
     * Save meta box data
     *
     * @param int     $post_id Post ID
     * @param WP_Post $post    Post object
     */
    public function save_meta_box($post_id, $post)
    {
        // Check if our nonce is set
        if (! isset($_POST['WBL_Social_Meta_Box_nonce'])) {
            return;
        }

        // Verify nonce
        if (! wp_verify_nonce($_POST['WBL_Social_Meta_Box_nonce'], 'WBL_Social_Meta_Box')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check post type and post object
        if (!isset($post->post_type) || 'sky_social_set' !== $post->post_type) {
            return;
        }

        // Check permissions
        if (! current_user_can('edit_post', $post_id)) {
            return;
        }

        // Sanitize and save data
        $social_items = array();

        if (isset($_POST['sky_social_items']) && is_array($_POST['sky_social_items'])) {
            foreach ($_POST['sky_social_items'] as $item) {
                // Skip empty rows or invalid data
                if (!is_array($item) || empty($item['platform']) || empty($item['url'])) {
                    continue;
                }

                // Validate platform
                $platform = sanitize_text_field($item['platform']);
                $url = isset($item['url']) ? esc_url_raw($item['url']) : '';
                $label = isset($item['label']) ? sanitize_text_field($item['label']) : '';

                // Only save if we have valid data
                if (!empty($platform) && !empty($url)) {
                    $social_items[] = array(
                        'platform' => $platform,
                        'url'      => $url,
                        'label'    => $label,
                    );
                }
            }
        }

        // Update post meta with error checking
        if (!empty($social_items)) {
            update_post_meta($post_id, $this->meta_key, $social_items);
        } else {
            // Delete meta if no items
            delete_post_meta($post_id, $this->meta_key);
        }
    }
}
