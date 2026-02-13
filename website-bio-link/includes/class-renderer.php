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
                'show_label' => 'false',
                'style'      => 'circle',
                'size'       => 'medium',
                'gap'        => 'medium',
                'align'      => 'left',
            ),
            $atts,
            'wbl_socials'
        );

        $post_id = intval($atts['id']);

        if (! $post_id) {
            return '<p class="text-red-500">' . esc_html__('Please provide a valid social set ID.', 'website-bio-link') . '</p>';
        }

        // Get social items
        $social_items = get_post_meta($post_id, '_wbl_social_items', true);

        if (empty($social_items) || ! is_array($social_items)) {
            return '';
        }

        return $this->render_social_list($social_items, $atts);
    }

    /**
     * Render social list HTML
     *
     * @param array $social_items Social items data
     * @param array $args         Display arguments
     * @return string HTML output
     */
    public function render_social_list($social_items, $args = array())
    {
        $defaults = array(
            'show_label' => 'false',
            'style'      => 'circle',
            'size'       => 'medium',
            'gap'        => 'medium',
            'align'      => 'left',
            'icon_type'  => 'svg', // 'svg' or 'fontawesome'
        );

        $args = wp_parse_args($args, $defaults);

        $show_label = filter_var($args['show_label'], FILTER_VALIDATE_BOOLEAN);
        $style = sanitize_text_field($args['style']);
        $size = sanitize_text_field($args['size']);
        $gap = sanitize_text_field($args['gap']);
        $align = sanitize_text_field($args['align']);
        $icon_type = sanitize_text_field($args['icon_type']);

        // Build CSS classes
        $container_classes = array('wbl-social-list');
        $container_classes[] = 'wbl-social-style-' . $style;
        $container_classes[] = 'wbl-social-size-' . $size;
        $container_classes[] = 'wbl-social-gap-' . $gap;
        $container_classes[] = 'wbl-social-align-' . $align;

        ob_start();
?>
        <ul class="<?php echo esc_attr(implode(' ', $container_classes)); ?>">
            <?php foreach ($social_items as $item) : ?>
                <?php
                $platform_data = WBL_Social_Config::get_platform_by_slug($item['platform']);
                if (! $platform_data || !is_array($platform_data)) {
                    continue;
                }

                // Validate required platform data
                if (!isset($platform_data['color']) || !isset($platform_data['label'])) {
                    continue;
                }

                $url = esc_url($item['url']);
                $label = ! empty($item['label']) ? $item['label'] : $platform_data['label'];
                $color = $platform_data['color'];

                // Get icon (SVG or FontAwesome)
                $icon_html = '';
                if ($icon_type === 'svg' && isset($platform_data['svg_icon'])) {
                    // Use SVG icon
                    $svg_icons = WBL_SVG_Icons::instance();
                    $icon_html = $svg_icons->get_svg_icon($platform_data['svg_icon'], array(
                        'class' => 'wbl-social-icon',
                        'width' => '24',
                        'height' => '24',
                    ));
                } elseif (isset($platform_data['icon_class'])) {
                    // Use FontAwesome as fallback
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
                        style="--brand-color: <?php echo esc_attr($color); ?>;">
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
