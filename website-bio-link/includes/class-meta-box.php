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
    private $meta_key = '_wbl_social_items';

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
        // Social Links Meta Box
        add_meta_box(
            'wbl_social_links',
            __('Social Links', 'website-bio-link'),
            array($this, 'render_meta_box'),
            'wbl_social_set',
            'normal',
            'high'
        );

        // Display Settings Meta Box
        add_meta_box(
            'wbl_social_display_settings',
            __('Display Settings (Override Global Settings)', 'website-bio-link'),
            array($this, 'render_display_settings_meta_box'),
            'wbl_social_set',
            'side',
            'default'
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
     * Render Display Settings Meta Box
     *
     * @param WP_Post $post Current post object
     */
    public function render_display_settings_meta_box($post)
    {
        // Get saved settings
        $settings = get_post_meta($post->ID, '_wbl_social_display_settings', true);
        if (!is_array($settings)) {
            $settings = array();
        }

        // Default values
        $defaults = array(
            'icon_style' => '',
            'icon_size_preset' => '',
            'icon_size_custom' => '',
            'gap_preset' => '',
            'gap_custom' => '',
            'layout_type' => '',
            'grid_columns' => '',
            'use_custom_colors' => false,
            'colors' => array(
                'primary' => '',
                'secondary' => '',
                'hover_primary' => '',
                'hover_secondary' => '',
            ),
        );

        $settings = wp_parse_args($settings, $defaults);
    ?>

        <div class="wbl-display-settings">
            <p class="description" style="margin-bottom: 15px;">
                <strong><?php esc_html_e('Note:', 'website-bio-link'); ?></strong>
                <?php esc_html_e('These settings will override global settings from the Settings page. Leave empty to use global defaults.', 'website-bio-link'); ?>
            </p>

            <!-- Icon Style -->
            <div class="wbl-setting-field">
                <label for="wbl_icon_style">
                    <strong><?php esc_html_e('Icon Style', 'website-bio-link'); ?></strong>
                </label>
                <select name="wbl_display_settings[icon_style]" id="wbl_icon_style" class="widefat">
                    <option value=""><?php esc_html_e('Use Global Setting', 'website-bio-link'); ?></option>
                    <option value="circle" <?php selected($settings['icon_style'], 'circle'); ?>><?php esc_html_e('Circle', 'website-bio-link'); ?></option>
                    <option value="rounded" <?php selected($settings['icon_style'], 'rounded'); ?>><?php esc_html_e('Rounded Square', 'website-bio-link'); ?></option>
                    <option value="flat" <?php selected($settings['icon_style'], 'flat'); ?>><?php esc_html_e('Flat Outline', 'website-bio-link'); ?></option>
                    <option value="minimal" <?php selected($settings['icon_style'], 'minimal'); ?>><?php esc_html_e('Minimal', 'website-bio-link'); ?></option>
                    <option value="glass" <?php selected($settings['icon_style'], 'glass'); ?>><?php esc_html_e('Glassmorphism', 'website-bio-link'); ?></option>
                    <option value="gradient" <?php selected($settings['icon_style'], 'gradient'); ?>><?php esc_html_e('Gradient', 'website-bio-link'); ?></option>
                </select>
            </div>

            <!-- Icon Size -->
            <div class="wbl-setting-field">
                <label for="wbl_icon_size_preset">
                    <strong><?php esc_html_e('Icon Size', 'website-bio-link'); ?></strong>
                </label>
                <select name="wbl_display_settings[icon_size_preset]" id="wbl_icon_size_preset" class="widefat">
                    <option value=""><?php esc_html_e('Use Global Setting', 'website-bio-link'); ?></option>
                    <option value="small" <?php selected($settings['icon_size_preset'], 'small'); ?>><?php esc_html_e('Small (16px)', 'website-bio-link'); ?></option>
                    <option value="medium" <?php selected($settings['icon_size_preset'], 'medium'); ?>><?php esc_html_e('Medium (20px)', 'website-bio-link'); ?></option>
                    <option value="large" <?php selected($settings['icon_size_preset'], 'large'); ?>><?php esc_html_e('Large (28px)', 'website-bio-link'); ?></option>
                    <option value="xlarge" <?php selected($settings['icon_size_preset'], 'xlarge'); ?>><?php esc_html_e('Extra Large (36px)', 'website-bio-link'); ?></option>
                    <option value="custom" <?php selected($settings['icon_size_preset'], 'custom'); ?>><?php esc_html_e('Custom', 'website-bio-link'); ?></option>
                </select>
                <input type="number"
                    name="wbl_display_settings[icon_size_custom]"
                    id="wbl_icon_size_custom"
                    class="widefat"
                    placeholder="<?php esc_attr_e('Custom size in px', 'website-bio-link'); ?>"
                    value="<?php echo esc_attr($settings['icon_size_custom']); ?>"
                    <?php if ($settings['icon_size_preset'] === 'custom') : ?>min="10" max="100" <?php else : ?>disabled="disabled" <?php endif; ?>
                    style="margin-top: 5px; display: <?php echo $settings['icon_size_preset'] === 'custom' ? 'block' : 'none'; ?>;" />
            </div>

            <!-- Gap -->
            <div class="wbl-setting-field">
                <label for="wbl_gap_preset">
                    <strong><?php esc_html_e('Gap Between Icons', 'website-bio-link'); ?></strong>
                </label>
                <select name="wbl_display_settings[gap_preset]" id="wbl_gap_preset" class="widefat">
                    <option value=""><?php esc_html_e('Use Global Setting', 'website-bio-link'); ?></option>
                    <option value="small" <?php selected($settings['gap_preset'], 'small'); ?>><?php esc_html_e('Small (8px)', 'website-bio-link'); ?></option>
                    <option value="medium" <?php selected($settings['gap_preset'], 'medium'); ?>><?php esc_html_e('Medium (16px)', 'website-bio-link'); ?></option>
                    <option value="large" <?php selected($settings['gap_preset'], 'large'); ?>><?php esc_html_e('Large (24px)', 'website-bio-link'); ?></option>
                    <option value="xlarge" <?php selected($settings['gap_preset'], 'xlarge'); ?>><?php esc_html_e('Extra Large (32px)', 'website-bio-link'); ?></option>
                    <option value="custom" <?php selected($settings['gap_preset'], 'custom'); ?>><?php esc_html_e('Custom', 'website-bio-link'); ?></option>
                </select>
                <input type="number"
                    name="wbl_display_settings[gap_custom]"
                    id="wbl_gap_custom"
                    class="widefat"
                    placeholder="<?php esc_attr_e('Custom gap in px', 'website-bio-link'); ?>"
                    value="<?php echo esc_attr($settings['gap_custom']); ?>"
                    <?php if ($settings['gap_preset'] === 'custom') : ?>min="0" max="100" <?php else : ?>disabled="disabled" <?php endif; ?>
                    style="margin-top: 5px; display: <?php echo $settings['gap_preset'] === 'custom' ? 'block' : 'none'; ?>;" />
            </div>

            <!-- Layout Type -->
            <div class="wbl-setting-field">
                <label for="wbl_layout_type">
                    <strong><?php esc_html_e('Layout Type', 'website-bio-link'); ?></strong>
                </label>
                <select name="wbl_display_settings[layout_type]" id="wbl_layout_type" class="widefat">
                    <option value=""><?php esc_html_e('Use Global Setting', 'website-bio-link'); ?></option>
                    <option value="horizontal" <?php selected($settings['layout_type'], 'horizontal'); ?>><?php esc_html_e('Horizontal', 'website-bio-link'); ?></option>
                    <option value="vertical" <?php selected($settings['layout_type'], 'vertical'); ?>><?php esc_html_e('Vertical', 'website-bio-link'); ?></option>
                    <option value="inline" <?php selected($settings['layout_type'], 'inline'); ?>><?php esc_html_e('Inline', 'website-bio-link'); ?></option>
                    <option value="grid" <?php selected($settings['layout_type'], 'grid'); ?>><?php esc_html_e('Grid', 'website-bio-link'); ?></option>
                </select>
            </div>

            <!-- Grid Columns (shown only when grid is selected) -->
            <div class="wbl-setting-field" id="wbl_grid_columns_field" style="display: <?php echo $settings['layout_type'] === 'grid' ? 'block' : 'none'; ?>;">
                <label for="wbl_grid_columns">
                    <strong><?php esc_html_e('Grid Columns', 'website-bio-link'); ?></strong>
                </label>
                <input type="number"
                    name="wbl_display_settings[grid_columns]"
                    id="wbl_grid_columns"
                    class="widefat"
                    value="<?php echo esc_attr($settings['grid_columns']); ?>"
                    placeholder="3"
                    <?php if ($settings['layout_type'] === 'grid') : ?>min="1" max="12" <?php else : ?>disabled="disabled" <?php endif; ?> />
            </div>

            <!-- Custom Colors -->
            <div class="wbl-setting-field">
                <label>
                    <strong><?php esc_html_e('Colors', 'website-bio-link'); ?></strong>
                </label>

                <!-- Color Source Toggle -->
                <div class="wbl-color-source-toggle" style="margin: 10px 0;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox"
                            name="wbl_display_settings[use_custom_colors]"
                            id="wbl_use_custom_colors"
                            value="1"
                            <?php checked(!empty($settings['use_custom_colors']), true); ?>
                            style="margin: 0;" />
                        <span><?php esc_html_e('Use Custom Colors (Override Settings)', 'website-bio-link'); ?></span>
                    </label>
                    <p class="description" style="margin: 5px 0 0 0;">
                        <?php esc_html_e('Check to use custom colors for this Social Set. Uncheck to use colors from Settings page.', 'website-bio-link'); ?>
                    </p>
                </div>

                <!-- Custom Colors Fields (shown only when toggle is ON and icon style is selected) -->
                <div id="wbl_custom_colors_fields" style="display: <?php echo !empty($settings['use_custom_colors']) && !empty($settings['icon_style']) ? 'block' : 'none'; ?>; margin-top: 15px; padding: 15px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">

                    <!-- Color fields for Circle & Rounded -->
                    <div class="wbl-color-group" data-styles="circle,rounded" style="display: none;">
                        <p class="description" style="margin-bottom: 10px;">
                            <strong><?php esc_html_e('Circle & Rounded Square Colors:', 'website-bio-link'); ?></strong><br>
                            <?php esc_html_e('Primary = Background, Secondary = Icon Color', 'website-bio-link'); ?>
                        </p>

                        <div class="wbl-color-field">
                            <label for="wbl_color_primary"><?php esc_html_e('Primary Color (Background)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][primary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['primary']); ?>" />
                        </div>

                        <div class="wbl-color-field">
                            <label for="wbl_color_secondary"><?php esc_html_e('Secondary Color (Icon)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][secondary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['secondary']); ?>" />
                        </div>

                        <div class="wbl-color-field">
                            <label for="wbl_color_hover_primary"><?php esc_html_e('Hover Primary (Background)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][hover_primary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['hover_primary']); ?>" />
                        </div>

                        <div class="wbl-color-field">
                            <label for="wbl_color_hover_secondary"><?php esc_html_e('Hover Secondary (Icon)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][hover_secondary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['hover_secondary']); ?>" />
                        </div>
                    </div>

                    <!-- Color fields for Flat -->
                    <div class="wbl-color-group" data-styles="flat" style="display: none;">
                        <p class="description" style="margin-bottom: 10px;">
                            <strong><?php esc_html_e('Flat Outline Colors:', 'website-bio-link'); ?></strong><br>
                            <?php esc_html_e('Primary = Border & Icon Color, Hover = Background becomes Primary', 'website-bio-link'); ?>
                        </p>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Primary Color (Border & Icon)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][primary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['primary']); ?>" />
                        </div>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Hover Primary (Background)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][hover_primary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['hover_primary']); ?>" />
                        </div>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Hover Secondary (Icon Color)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][hover_secondary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['hover_secondary']); ?>" />
                        </div>
                    </div>

                    <!-- Color fields for Minimal -->
                    <div class="wbl-color-group" data-styles="minimal" style="display: none;">
                        <p class="description" style="margin-bottom: 10px;">
                            <strong><?php esc_html_e('Minimal Colors:', 'website-bio-link'); ?></strong><br>
                            <?php esc_html_e('Primary = Icon Color only', 'website-bio-link'); ?>
                        </p>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Primary Color (Icon)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][primary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['primary']); ?>" />
                        </div>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Hover Primary (Icon)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][hover_primary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['hover_primary']); ?>" />
                        </div>
                    </div>

                    <!-- Color fields for Glass -->
                    <div class="wbl-color-group" data-styles="glass" style="display: none;">
                        <p class="description" style="margin-bottom: 10px;">
                            <strong><?php esc_html_e('Glassmorphism Colors:', 'website-bio-link'); ?></strong><br>
                            <?php esc_html_e('Primary = Icon Color, Secondary = Background (use rgba)', 'website-bio-link'); ?>
                        </p>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Primary Color (Icon)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][primary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['primary']); ?>" />
                        </div>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Secondary Color (Background)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][secondary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['secondary']); ?>"
                                placeholder="rgba(255,255,255,0.15)" />
                        </div>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Hover Primary (Icon)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][hover_primary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['hover_primary']); ?>" />
                        </div>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Hover Secondary (Background)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][hover_secondary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['hover_secondary']); ?>"
                                placeholder="rgba(255,255,255,0.25)" />
                        </div>
                    </div>

                    <!-- Color fields for Gradient -->
                    <div class="wbl-color-group" data-styles="gradient" style="display: none;">
                        <p class="description" style="margin-bottom: 10px;">
                            <strong><?php esc_html_e('Gradient Colors:', 'website-bio-link'); ?></strong><br>
                            <?php esc_html_e('Primary = Start Color, Secondary = End Color', 'website-bio-link'); ?>
                        </p>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Primary Color (Gradient Start)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][primary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['primary']); ?>" />
                        </div>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Secondary Color (Gradient End)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][secondary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['secondary']); ?>" />
                        </div>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Hover Primary (Gradient Start)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][hover_primary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['hover_primary']); ?>" />
                        </div>

                        <div class="wbl-color-field">
                            <label><?php esc_html_e('Hover Secondary (Gradient End)', 'website-bio-link'); ?></label>
                            <input type="text"
                                name="wbl_display_settings[colors][hover_secondary]"
                                class="wbl-color-picker-small"
                                value="<?php echo esc_attr($settings['colors']['hover_secondary']); ?>" />
                        </div>
                    </div>

                    <!-- Warning when no icon style selected -->
                    <div id="wbl_no_style_warning" style="display: none; padding: 10px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 4px;">
                        <p style="margin: 0; color: #856404;">
                            <strong><?php esc_html_e('Note:', 'website-bio-link'); ?></strong>
                            <?php esc_html_e('Please select an Icon Style above to configure custom colors.', 'website-bio-link'); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .wbl-display-settings {
                padding: 10px 0;
            }

            .wbl-setting-field {
                margin-bottom: 20px;
            }

            .wbl-setting-field label {
                display: block;
                margin-bottom: 5px;
            }

            .wbl-setting-field select,
            .wbl-setting-field input[type="number"] {
                width: 100%;
            }

            .wbl-color-field {
                margin-bottom: 10px;
            }

            .wbl-color-field label {
                display: block;
                margin-bottom: 3px;
                font-size: 12px;
                font-weight: 500;
            }

            .wbl-color-picker-small {
                width: 100%;
            }

            .wbl-color-source-toggle {
                padding: 10px;
                background: #f0f0f1;
                border-radius: 4px;
            }
        </style>

        <script>
            jQuery(document).ready(function($) {
                // Initialize color pickers
                $('.wbl-color-picker-small').wpColorPicker();

                // Function to update color fields based on icon style
                function updateColorFields() {
                    var iconStyle = $('#wbl_icon_style').val();
                    var useCustomColors = $('#wbl_use_custom_colors').is(':checked');

                    // Hide all color groups first
                    $('.wbl-color-group').hide();
                    $('#wbl_no_style_warning').hide();

                    if (useCustomColors) {
                        $('#wbl_custom_colors_fields').show();

                        if (iconStyle && iconStyle !== '') {
                            // Show relevant color group based on selected style
                            $('.wbl-color-group').each(function() {
                                var styles = $(this).data('styles').toString().split(',');
                                if (styles.indexOf(iconStyle) !== -1) {
                                    $(this).show();
                                }
                            });
                        } else {
                            // Show warning if no style selected
                            $('#wbl_no_style_warning').show();
                        }
                    } else {
                        $('#wbl_custom_colors_fields').hide();
                    }
                }

                // Show/hide custom size input
                $('#wbl_icon_size_preset').on('change', function() {
                    if ($(this).val() === 'custom') {
                        $('#wbl_icon_size_custom').show().prop('disabled', false).attr('min', '10').attr('max', '100');
                    } else {
                        $('#wbl_icon_size_custom').hide().prop('disabled', true).removeAttr('min').removeAttr('max');
                    }
                });

                // Show/hide custom gap input
                $('#wbl_gap_preset').on('change', function() {
                    if ($(this).val() === 'custom') {
                        $('#wbl_gap_custom').show().prop('disabled', false).attr('min', '0').attr('max', '100');
                    } else {
                        $('#wbl_gap_custom').hide().prop('disabled', true).removeAttr('min').removeAttr('max');
                    }
                });

                // Show/hide grid columns
                $('#wbl_layout_type').on('change', function() {
                    if ($(this).val() === 'grid') {
                        $('#wbl_grid_columns_field').show();
                        $('#wbl_grid_columns').prop('disabled', false).attr('min', '1').attr('max', '12');
                    } else {
                        $('#wbl_grid_columns_field').hide();
                        $('#wbl_grid_columns').prop('disabled', true).removeAttr('min').removeAttr('max');
                    }
                });

                // Update color fields when icon style changes
                $('#wbl_icon_style').on('change', updateColorFields);

                // Update color fields when custom colors toggle changes
                $('#wbl_use_custom_colors').on('change', updateColorFields);

                // Initial update on page load
                updateColorFields();
            });
        </script>

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
                <select name="wbl_social_items[<?php echo esc_attr($index); ?>][platform]" class="wbl-social-platform">
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
                    name="wbl_social_items[<?php echo esc_attr($index); ?>][url]"
                    value="<?php echo esc_attr($url); ?>"
                    placeholder="https://example.com/profile"
                    class="wbl-social-url" />
            </td>
            <td class="px-4 py-3">
                <input
                    type="text"
                    name="wbl_social_items[<?php echo esc_attr($index); ?>][label]"
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
        if (!isset($post->post_type) || 'wbl_social_set' !== $post->post_type) {
            return;
        }

        // Check permissions
        if (! current_user_can('edit_post', $post_id)) {
            return;
        }

        // Sanitize and save data
        $social_items = array();

        if (isset($_POST['wbl_social_items']) && is_array($_POST['wbl_social_items'])) {
            foreach ($_POST['wbl_social_items'] as $item) {
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

        // Save Display Settings
        if (isset($_POST['wbl_display_settings']) && is_array($_POST['wbl_display_settings'])) {
            $display_settings = $_POST['wbl_display_settings'];

            $sanitized_settings = array(
                'icon_style' => isset($display_settings['icon_style']) ? sanitize_text_field($display_settings['icon_style']) : '',
                'icon_size_preset' => isset($display_settings['icon_size_preset']) ? sanitize_text_field($display_settings['icon_size_preset']) : '',
                'icon_size_custom' => isset($display_settings['icon_size_custom']) ? absint($display_settings['icon_size_custom']) : '',
                'gap_preset' => isset($display_settings['gap_preset']) ? sanitize_text_field($display_settings['gap_preset']) : '',
                'gap_custom' => isset($display_settings['gap_custom']) ? absint($display_settings['gap_custom']) : '',
                'layout_type' => isset($display_settings['layout_type']) ? sanitize_text_field($display_settings['layout_type']) : '',
                'grid_columns' => isset($display_settings['grid_columns']) ? absint($display_settings['grid_columns']) : '',
                'use_custom_colors' => isset($display_settings['use_custom_colors']) ? true : false,
                'colors' => array(
                    'primary' => isset($display_settings['colors']['primary']) ? sanitize_hex_color($display_settings['colors']['primary']) : '',
                    'secondary' => isset($display_settings['colors']['secondary']) ? sanitize_hex_color($display_settings['colors']['secondary']) : '',
                    'hover_primary' => isset($display_settings['colors']['hover_primary']) ? sanitize_hex_color($display_settings['colors']['hover_primary']) : '',
                    'hover_secondary' => isset($display_settings['colors']['hover_secondary']) ? sanitize_hex_color($display_settings['colors']['hover_secondary']) : '',
                ),
            );

            update_post_meta($post_id, '_wbl_social_display_settings', $sanitized_settings);
        }
    }
}
