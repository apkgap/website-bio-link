<?php

/**
 * Settings Page
 *
 * @package Website_Bio_Link
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Settings class
 */
class WBL_Settings
{

    /**
     * Single instance
     *
     * @var WBL_Settings
     */
    private static $instance = null;

    /**
     * Settings option name
     *
     * @var string
     */
    private $option_name = 'wbl_social_settings';

    /**
     * Get instance
     *
     * @return WBL_Settings
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
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_settings_assets'));
    }

    /**
     * Add settings page to menu
     */
    public function add_settings_page()
    {
        add_submenu_page(
            'edit.php?post_type=sky_social_set',
            __('Settings', 'website-bio-link'),
            __('Settings', 'website-bio-link'),
            'manage_options',
            'wbl-social-settings',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings()
    {
        register_setting(
            'wbl_social_settings_group',
            $this->option_name,
            array($this, 'sanitize_settings')
        );

        // General Settings Section
        add_settings_section(
            'wbl_general_section',
            __('General Settings', 'website-bio-link'),
            array($this, 'render_general_section'),
            'wbl-social-settings'
        );

        // Default Style
        add_settings_field(
            'default_style',
            __('Default Icon Style', 'website-bio-link'),
            array($this, 'render_style_field'),
            'wbl-social-settings',
            'wbl_general_section'
        );

        // Default Size
        add_settings_field(
            'default_size',
            __('Default Icon Size', 'website-bio-link'),
            array($this, 'render_size_field'),
            'wbl-social-settings',
            'wbl_general_section'
        );

        // Default Gap
        add_settings_field(
            'default_gap',
            __('Default Gap', 'website-bio-link'),
            array($this, 'render_gap_field'),
            'wbl-social-settings',
            'wbl_general_section'
        );

        // Assets Settings Section
        add_settings_section(
            'wbl_assets_section',
            __('Assets Settings', 'website-bio-link'),
            array($this, 'render_assets_section'),
            'wbl-social-settings'
        );

        // Enable FontAwesome
        add_settings_field(
            'enable_fontawesome',
            __('Load FontAwesome', 'website-bio-link'),
            array($this, 'render_fontawesome_field'),
            'wbl-social-settings',
            'wbl_assets_section'
        );

        // Enable TailwindCSS
        add_settings_field(
            'enable_tailwind',
            __('Load TailwindCSS', 'website-bio-link'),
            array($this, 'render_tailwind_field'),
            'wbl-social-settings',
            'wbl_assets_section'
        );

        // Advanced Settings Section
        add_settings_section(
            'wbl_advanced_section',
            __('Advanced Settings', 'website-bio-link'),
            array($this, 'render_advanced_section'),
            'wbl-social-settings'
        );

        // Delete Data on Uninstall
        add_settings_field(
            'delete_on_uninstall',
            __('Delete Data on Uninstall', 'website-bio-link'),
            array($this, 'render_delete_field'),
            'wbl-social-settings',
            'wbl_advanced_section'
        );
    }

    /**
     * Sanitize settings
     */
    public function sanitize_settings($input)
    {
        $sanitized = array();

        $sanitized['default_style'] = isset($input['default_style']) ? sanitize_text_field($input['default_style']) : 'circle';
        $sanitized['default_size'] = isset($input['default_size']) ? sanitize_text_field($input['default_size']) : 'medium';
        $sanitized['default_gap'] = isset($input['default_gap']) ? sanitize_text_field($input['default_gap']) : 'medium';
        $sanitized['enable_fontawesome'] = isset($input['enable_fontawesome']) ? true : false;
        $sanitized['enable_tailwind'] = isset($input['enable_tailwind']) ? true : false;
        $sanitized['delete_on_uninstall'] = isset($input['delete_on_uninstall']) ? true : false;

        return $sanitized;
    }

    /**
     * Get settings
     */
    public function get_settings()
    {
        $defaults = array(
            'default_style' => 'circle',
            'default_size' => 'medium',
            'default_gap' => 'medium',
            'enable_fontawesome' => true,
            'enable_tailwind' => true,
            'delete_on_uninstall' => true,
        );

        $settings = get_option($this->option_name, $defaults);
        return wp_parse_args($settings, $defaults);
    }

    /**
     * Render settings page
     */
    public function render_settings_page()
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        // Check if settings saved
        if (isset($_GET['settings-updated'])) {
            add_settings_error(
                'wbl_messages',
                'wbl_message',
                __('Settings Saved Successfully!', 'website-bio-link'),
                'updated'
            );
        }

        settings_errors('wbl_messages');
?>

        <div class="wrap wbl-settings-wrap">
            <h1 class="wbl-settings-title">
                <span class="dashicons dashicons-share"></span>
                <?php echo esc_html(get_admin_page_title()); ?>
            </h1>

            <div class="wbl-settings-container">
                <form action="options.php" method="post" class="wbl-settings-form">
                    <?php
                    settings_fields('wbl_social_settings_group');
                    do_settings_sections('wbl-social-settings');
                    submit_button(__('Save Settings', 'website-bio-link'));
                    ?>
                </form>

                <div class="wbl-settings-sidebar">
                    <!-- Plugin Info -->
                    <div class="wbl-info-box">
                        <h3><?php esc_html_e('Plugin Information', 'website-bio-link'); ?></h3>
                        <ul>
                            <li><strong><?php esc_html_e('Version:', 'website-bio-link'); ?></strong> <?php echo esc_html(WBL_SOCIAL_VERSION); ?></li>
                            <li><strong><?php esc_html_e('Social Sets:', 'website-bio-link'); ?></strong> <?php echo esc_html($this->count_social_sets()); ?></li>
                            <li><strong><?php esc_html_e('Total Links:', 'website-bio-link'); ?></strong> <?php echo esc_html($this->count_total_links()); ?></li>
                        </ul>
                    </div>

                    <!-- Quick Links -->
                    <div class="wbl-info-box">
                        <h3><?php esc_html_e('Quick Links', 'website-bio-link'); ?></h3>
                        <ul>
                            <li><a href="<?php echo esc_url(admin_url('post-new.php?post_type=sky_social_set')); ?>"><?php esc_html_e('Add New Set', 'website-bio-link'); ?></a></li>
                            <li><a href="<?php echo esc_url(admin_url('edit.php?post_type=sky_social_set')); ?>"><?php esc_html_e('All Social Sets', 'website-bio-link'); ?></a></li>
                            <li><a href="<?php echo esc_url(admin_url('widgets.php')); ?>"><?php esc_html_e('Widgets', 'website-bio-link'); ?></a></li>
                        </ul>
                    </div>

                    <!-- Documentation -->
                    <div class="wbl-info-box">
                        <h3><?php esc_html_e('Documentation', 'website-bio-link'); ?></h3>
                        <p><?php esc_html_e('Need help? Check out the documentation files:', 'website-bio-link'); ?></p>
                        <ul>
                            <li>README.md</li>
                            <li>SUMMARY-TH.md</li>
                            <li>INSTALLATION.md</li>
                            <li>WORDPRESS-WIDGET-GUIDE.md</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    <?php
    }

    /**
     * Section callbacks
     */
    public function render_general_section()
    {
        echo '<p>' . esc_html__('Configure default settings for social links display.', 'website-bio-link') . '</p>';
    }

    public function render_assets_section()
    {
        echo '<p>' . esc_html__('Manage external assets loading. Disable if your theme already includes these libraries.', 'website-bio-link') . '</p>';
    }

    public function render_advanced_section()
    {
        echo '<p>' . esc_html__('Advanced plugin settings. Use with caution.', 'website-bio-link') . '</p>';
    }

    /**
     * Field callbacks
     */
    public function render_style_field()
    {
        $settings = $this->get_settings();
        $value = $settings['default_style'];
    ?>
        <select name="<?php echo esc_attr($this->option_name); ?>[default_style]" class="regular-text">
            <option value="circle" <?php selected($value, 'circle'); ?>><?php esc_html_e('Circle', 'website-bio-link'); ?></option>
            <option value="rounded" <?php selected($value, 'rounded'); ?>><?php esc_html_e('Rounded Square', 'website-bio-link'); ?></option>
            <option value="flat" <?php selected($value, 'flat'); ?>><?php esc_html_e('Flat Outline', 'website-bio-link'); ?></option>
            <option value="minimal" <?php selected($value, 'minimal'); ?>><?php esc_html_e('Minimal', 'website-bio-link'); ?></option>
            <option value="glass" <?php selected($value, 'glass'); ?>><?php esc_html_e('Glassmorphism', 'website-bio-link'); ?></option>
            <option value="gradient" <?php selected($value, 'gradient'); ?>><?php esc_html_e('Gradient', 'website-bio-link'); ?></option>
        </select>
        <p class="description"><?php esc_html_e('Default style for shortcodes and widgets.', 'website-bio-link'); ?></p>
    <?php
    }

    public function render_size_field()
    {
        $settings = $this->get_settings();
        $value = $settings['default_size'];
    ?>
        <select name="<?php echo esc_attr($this->option_name); ?>[default_size]" class="regular-text">
            <option value="small" <?php selected($value, 'small'); ?>><?php esc_html_e('Small', 'website-bio-link'); ?></option>
            <option value="medium" <?php selected($value, 'medium'); ?>><?php esc_html_e('Medium', 'website-bio-link'); ?></option>
            <option value="large" <?php selected($value, 'large'); ?>><?php esc_html_e('Large', 'website-bio-link'); ?></option>
            <option value="xlarge" <?php selected($value, 'xlarge'); ?>><?php esc_html_e('Extra Large', 'website-bio-link'); ?></option>
        </select>
        <p class="description"><?php esc_html_e('Default icon size.', 'website-bio-link'); ?></p>
    <?php
    }

    public function render_gap_field()
    {
        $settings = $this->get_settings();
        $value = $settings['default_gap'];
    ?>
        <select name="<?php echo esc_attr($this->option_name); ?>[default_gap]" class="regular-text">
            <option value="small" <?php selected($value, 'small'); ?>><?php esc_html_e('Small', 'website-bio-link'); ?></option>
            <option value="medium" <?php selected($value, 'medium'); ?>><?php esc_html_e('Medium', 'website-bio-link'); ?></option>
            <option value="large" <?php selected($value, 'large'); ?>><?php esc_html_e('Large', 'website-bio-link'); ?></option>
        </select>
        <p class="description"><?php esc_html_e('Default gap between icons.', 'website-bio-link'); ?></p>
    <?php
    }

    public function render_fontawesome_field()
    {
        $settings = $this->get_settings();
        $value = $settings['enable_fontawesome'];
    ?>
        <label>
            <input type="checkbox" name="<?php echo esc_attr($this->option_name); ?>[enable_fontawesome]" value="1" <?php checked($value, true); ?> />
            <?php esc_html_e('Load FontAwesome 6 from CDN', 'website-bio-link'); ?>
        </label>
        <p class="description"><?php esc_html_e('Disable if your theme already includes FontAwesome.', 'website-bio-link'); ?></p>
    <?php
    }

    public function render_tailwind_field()
    {
        $settings = $this->get_settings();
        $value = $settings['enable_tailwind'];
    ?>
        <label>
            <input type="checkbox" name="<?php echo esc_attr($this->option_name); ?>[enable_tailwind]" value="1" <?php checked($value, true); ?> />
            <?php esc_html_e('Load TailwindCSS from CDN', 'website-bio-link'); ?>
        </label>
        <p class="description"><?php esc_html_e('Disable if your theme already includes TailwindCSS.', 'website-bio-link'); ?></p>
    <?php
    }

    public function render_delete_field()
    {
        $settings = $this->get_settings();
        $value = $settings['delete_on_uninstall'];
    ?>
        <label>
            <input type="checkbox" name="<?php echo esc_attr($this->option_name); ?>[delete_on_uninstall]" value="1" <?php checked($value, true); ?> />
            <?php esc_html_e('Delete all data when plugin is uninstalled', 'website-bio-link'); ?>
        </label>
        <p class="description" style="color: #d63638;">
            <strong><?php esc_html_e('Warning:', 'website-bio-link'); ?></strong>
            <?php esc_html_e('This will permanently delete all social sets and settings when you uninstall the plugin.', 'website-bio-link'); ?>
        </p>
<?php
    }

    /**
     * Enqueue settings page assets
     */
    public function enqueue_settings_assets($hook)
    {
        if ('sky_social_set_page_wbl-social-settings' !== $hook) {
            return;
        }

        wp_add_inline_style('wp-admin', $this->get_inline_css());
    }

    /**
     * Get inline CSS for settings page
     */
    private function get_inline_css()
    {
        return '
            .wbl-settings-wrap {
                max-width: 1200px;
            }
            .wbl-settings-title {
                display: flex;
                align-items: center;
                gap: 10px;
                font-size: 23px;
                font-weight: 400;
                margin: 0 0 20px;
                padding: 9px 0;
                line-height: 1.3;
            }
            .wbl-settings-title .dashicons {
                font-size: 28px;
                width: 28px;
                height: 28px;
                color: #2271b1;
            }
            .wbl-settings-container {
                display: grid;
                grid-template-columns: 1fr 300px;
                gap: 20px;
            }
            .wbl-settings-form {
                background: #fff;
                border: 1px solid #c3c4c7;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                padding: 20px;
            }
            .wbl-settings-form h2 {
                margin-top: 0;
                padding-top: 0;
                font-size: 18px;
                font-weight: 600;
            }
            .wbl-settings-sidebar {
                display: flex;
                flex-direction: column;
                gap: 15px;
            }
            .wbl-info-box {
                background: #fff;
                border: 1px solid #c3c4c7;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
                padding: 15px;
            }
            .wbl-info-box h3 {
                margin: 0 0 10px;
                font-size: 14px;
                font-weight: 600;
                color: #1d2327;
            }
            .wbl-info-box ul {
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .wbl-info-box li {
                padding: 5px 0;
                border-bottom: 1px solid #f0f0f1;
            }
            .wbl-info-box li:last-child {
                border-bottom: none;
            }
            .wbl-info-box a {
                text-decoration: none;
                color: #2271b1;
            }
            .wbl-info-box a:hover {
                color: #135e96;
            }
            @media (max-width: 782px) {
                .wbl-settings-container {
                    grid-template-columns: 1fr;
                }
            }
        ';
    }

    /**
     * Count social sets
     */
    private function count_social_sets()
    {
        $count = wp_count_posts('sky_social_set');
        return isset($count->publish) ? $count->publish : 0;
    }

    /**
     * Count total links
     */
    private function count_total_links()
    {
        global $wpdb;

        $query = "SELECT SUM(CHAR_LENGTH(meta_value) - CHAR_LENGTH(REPLACE(meta_value, '\"platform\"', ''))) / CHAR_LENGTH('\"platform\"') as total
                  FROM {$wpdb->postmeta}
                  WHERE meta_key = '_sky_social_items'";

        $result = $wpdb->get_var($query);
        return $result ? intval($result) : 0;
    }
}
