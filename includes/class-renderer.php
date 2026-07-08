<?php

/**
 * Frontend Renderer for Shortcode
 *
 * @package Website_Bio_Link
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Renderer class for shortcode and frontend display
 */
class WBL_Social_Renderer
{

    /**
     * Single instance
     *
     * @var WBL_Social_Renderer
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return WBL_Social_Renderer
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
        add_shortcode('wbl_socials', array($this, 'render_shortcode'));
    }

    /**
     * Render shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function render_shortcode($atts)
    {
        $atts = shortcode_atts(
            array(
                'id'         => 0,
                'show_label' => '',
                'style'      => '',
                'size'       => '',
                'gap'        => '',
                'align'      => '',
            ),
            $atts,
            'wbl_socials'
        );

        $post_id = intval($atts['id']);

        if (! $post_id) {
            return '<p class="wbl-error text-red-500">' . esc_html__('Please provide a valid social set ID.', 'website-bio-link') . '</p>';
        }

        // Get social items
        $social_items = get_post_meta($post_id, '_wbl_social_items', true);

        if (empty($social_items) || ! is_array($social_items)) {
            return '';
        }

        return $this->render_social_list($social_items, $atts, $post_id);
    }

    /**
     * Render social list HTML
     *
     * @param array $social_items Social items data
     * @param array $args         Display arguments
     * @param int   $post_id      Post ID for fetching meta settings
     * @return string HTML output
     */
    public function render_social_list($social_items, $args = array(), $post_id = 0)
    {
        $global_settings = class_exists('WBL_Settings') ? WBL_Settings::instance()->get_settings() : array();
        
        $meta_settings = $post_id ? get_post_meta($post_id, '_wbl_social_display_settings', true) : array();
        if (!is_array($meta_settings)) {
            $meta_settings = array();
        }

        // Determine settings: Shortcode args > Post Meta override > Global default
        $style = !empty($args['style']) ? $args['style'] : (!empty($meta_settings['icon_style']) ? $meta_settings['icon_style'] : ($global_settings['default_style'] ?? 'circle'));
        $size = !empty($args['size']) ? $args['size'] : (!empty($meta_settings['icon_size_preset']) ? $meta_settings['icon_size_preset'] : ($global_settings['default_size'] ?? 'medium'));
        $gap = !empty($args['gap']) ? $args['gap'] : (!empty($meta_settings['gap_preset']) ? $meta_settings['gap_preset'] : ($global_settings['default_gap'] ?? 'medium'));
        $align = !empty($args['align']) ? $args['align'] : 'left';
        
        $show_label = !empty($args['show_label']) && $args['show_label'] !== 'false' ? true : false;
        $icon_type = 'svg';

        // Check for Custom Colors based on Style
        $use_custom_colors = !empty($meta_settings['use_custom_colors']);
        $colors = array('primary' => '', 'secondary' => '', 'hover_primary' => '', 'hover_secondary' => '');

        if ($use_custom_colors && !empty($meta_settings['colors'])) {
            $colors = wp_parse_args($meta_settings['colors'], $colors);
        } else if (!empty($global_settings['default_colors'][$style])) {
            $colors = wp_parse_args($global_settings['default_colors'][$style], $colors);
        }

        $style = sanitize_text_field($style);
        $size = sanitize_text_field($size);
        $gap = sanitize_text_field($gap);
        $align = sanitize_text_field($align);

        $scope_id = 'wbl-social-' . substr(md5(uniqid(rand(), true)), 0, 8);

        // Build CSS classes
        $container_classes = array('wbl-social-list', $scope_id);
        $container_classes[] = 'wbl-social-style-' . $style;
        $container_classes[] = 'wbl-social-size-' . $size;
        $container_classes[] = 'wbl-social-gap-' . $gap;
        $container_classes[] = 'wbl-social-align-' . $align;

        // Generate inline <style> if colors are set
        $css = '';
        if (array_filter($colors)) { // checking if ANY color is set
            $p = !empty($colors['primary']) ? $colors['primary'] : '';
            $s = !empty($colors['secondary']) ? $colors['secondary'] : '';
            $hp = !empty($colors['hover_primary']) ? $colors['hover_primary'] : '';
            $hs = !empty($colors['hover_secondary']) ? $colors['hover_secondary'] : '';
            
            $cid = "." . $scope_id;
            
            if ($style === 'circle' || $style === 'rounded') {
                if ($p) $css .= "$cid .wbl-social-icon-wrapper { background-color: $p !important; } ";
                if ($s) $css .= "$cid .wbl-social-icon-wrapper { color: $s !important; } ";
                if ($hp) $css .= "$cid .wbl-social-link:hover .wbl-social-icon-wrapper { background-color: $hp !important; } ";
                if ($hs) $css .= "$cid .wbl-social-link:hover .wbl-social-icon-wrapper { color: $hs !important; } ";
            } else if ($style === 'flat') {
                if ($p) $css .= "$cid .wbl-social-icon-wrapper { border-color: $p !important; color: $p !important; } ";
                if ($hp) $css .= "$cid .wbl-social-link:hover .wbl-social-icon-wrapper { background-color: $hp !important; border-color: $hp !important; } ";
                if ($hs) $css .= "$cid .wbl-social-link:hover .wbl-social-icon-wrapper { color: $hs !important; } ";
            } else if ($style === 'minimal') {
                if ($p) $css .= "$cid .wbl-social-icon-wrapper { color: $p !important; } ";
                if ($hp) $css .= "$cid .wbl-social-link:hover .wbl-social-icon-wrapper { color: $hp !important; } ";
            } else if ($style === 'glass') {
                if ($s) $css .= "$cid .wbl-social-icon-wrapper { background-color: $s !important; border-color: $s !important; } ";
                if ($p) $css .= "$cid .wbl-social-icon-wrapper { color: $p !important; } ";
                if ($hs) $css .= "$cid .wbl-social-link:hover .wbl-social-icon-wrapper { background-color: $hs !important; } ";
                if ($hp) $css .= "$cid .wbl-social-link:hover .wbl-social-icon-wrapper { color: $hp !important; } ";
            } else if ($style === 'gradient') {
                if ($p && $s) $css .= "$cid .wbl-social-icon-wrapper { background: linear-gradient(135deg, $p 0%, $s 100%) !important; } ";
                if ($hp && $hs) $css .= "$cid .wbl-social-link:hover .wbl-social-icon-wrapper { background: linear-gradient(135deg, $hp 0%, $hs 100%) !important; } ";
            }
        }

        ob_start();
        
        if (!empty($css)) {
            echo '<style>' . $css . '</style>';
        }
?>
        <ul class="<?php echo esc_attr(implode(' ', $container_classes)); ?>">
            <?php foreach ($social_items as $item) : ?>
                <?php
                if (!class_exists('WBL_Social_Config')) {
                    continue;
                }

                $platform_data = WBL_Social_Config::get_platform_by_slug($item['platform']);
                if (! $platform_data || !is_array($platform_data)) {
                    continue;
                }

                if (!isset($platform_data['color']) || !isset($platform_data['label'])) {
                    continue;
                }

                $url = esc_url($item['url']);
                $label = ! empty($item['label']) ? $item['label'] : $platform_data['label'];
                
                // Keep original brand color as fallback for CSS var if custom colors aren't used fully
                $brand_color = $platform_data['color'];

                $icon_html = '';
                if ($icon_type === 'svg' && isset($platform_data['svg_icon'])) {
                    if (class_exists('WBL_SVG_Icons')) {
                        $svg_icons = WBL_SVG_Icons::instance();
                        $icon_html = $svg_icons->get_svg_icon($platform_data['svg_icon'], array(
                            'class' => 'wbl-social-icon',
                            'width' => '24',
                            'height' => '24',
                        ));
                    }
                } elseif (isset($platform_data['icon_class'])) {
                    $icon_html = '<i class="' . esc_attr($platform_data['icon_class']) . ' wbl-social-icon" aria-hidden="true"></i>';
                }

                if (empty($icon_html)) {
                    continue;
                }
                ?>
                <li class="wbl-social-item" data-platform="<?php echo esc_attr($item['platform']); ?>">
                    <a
                        href="<?php echo $url; ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="wbl-social-link group"
                        aria-label="<?php echo esc_attr($label); ?>"
                        style="--brand-color: <?php echo esc_attr($brand_color); ?>;">
                        <span class="wbl-social-icon-wrapper">
                            <?php echo $icon_html; ?>
                        </span>
                        <?php if ($show_label) : ?>
                            <span class="wbl-social-label"><?php echo esc_html($label); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
<?php
        return ob_get_clean();
    }
}
