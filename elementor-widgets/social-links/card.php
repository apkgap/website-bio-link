<?php
/**
 * Social Link Card Pattern
 */
if (!defined('ABSPATH')) exit;

// Expecting $item, $args
$platform = $item['platform'] ?? '';
$url = $item['url'] ?? '#';
$label = !empty($item['label']) ? $item['label'] : ucfirst($platform);

$platform_data = class_exists('WBL_Social_Config') ? WBL_Social_Config::get_platform_by_slug($platform) : null;
$brand_color = $platform_data['color'] ?? '#3b82f6';

$icon_html = '';
if (class_exists('WBL_SVG_Icons') && $platform_data && !empty($platform_data['svg_icon'])) {
    $icon_html = WBL_SVG_Icons::instance()->get_svg_icon($platform_data['svg_icon'], [
        'class' => 'wbl-social-icon',
        'width' => '24',
        'height' => '24',
    ]);
}

if (empty($icon_html) && $platform_data && !empty($platform_data['icon_class'])) {
    $icon_html = '<i class="' . esc_attr($platform_data['icon_class']) . ' wbl-social-icon"></i>';
}

if (empty($icon_html)) return;
?>

<li class="wbl-social-item" data-platform="<?php echo esc_attr($platform); ?>">
    <a href="<?php echo esc_url($url); ?>" class="wbl-social-link" target="_blank" rel="noopener noreferrer" style="--brand-color: <?php echo esc_attr($brand_color); ?>;">
        <span class="wbl-social-icon-wrapper">
            <?php echo $icon_html; ?>
        </span>
        <?php if ($args['show_label']) : ?>
            <span class="wbl-social-label"><?php echo esc_html($label); ?></span>
        <?php endif; ?>
    </a>
</li>
