<?php

/**
 * Social Platforms Configuration
 *
 * @package Website_Bio_Link
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Configuration class for social media platforms
 */
class WBL_Social_Config
{

    /**
     * Get all social media platforms
     *
     * @return array Array of social platforms with label, slug, icon_class, and color
     */
    public static function get_platforms()
    {
        return array(
            array(
                'label'      => 'Facebook',
                'slug'       => 'facebook',
                'icon_class' => 'fa-brands fa-facebook',
                'svg_icon'   => 'facebook',
                'color'      => '#1877F2',
            ),
            array(
                'label'      => 'Twitter / X',
                'slug'       => 'twitter',
                'icon_class' => 'fa-brands fa-x-twitter',
                'svg_icon'   => 'x',
                'color'      => '#000000',
            ),
            array(
                'label'      => 'Instagram',
                'slug'       => 'instagram',
                'icon_class' => 'fa-brands fa-instagram',
                'svg_icon'   => 'instagram',
                'color'      => '#E4405F',
            ),
            array(
                'label'      => 'TikTok',
                'slug'       => 'tiktok',
                'icon_class' => 'fa-brands fa-tiktok',
                'svg_icon'   => 'tiktok',
                'color'      => '#000000',
            ),
            array(
                'label'      => 'LinkedIn',
                'slug'       => 'linkedin',
                'icon_class' => 'fa-brands fa-linkedin',
                'svg_icon'   => 'linkedin',
                'color'      => '#0A66C2',
            ),
            array(
                'label'      => 'YouTube',
                'slug'       => 'youtube',
                'icon_class' => 'fa-brands fa-youtube',
                'svg_icon'   => 'youtube',
                'color'      => '#FF0000',
            ),
            array(
                'label'      => 'WhatsApp',
                'slug'       => 'whatsapp',
                'icon_class' => 'fa-brands fa-whatsapp',
                'svg_icon'   => 'whatsapp',
                'color'      => '#25D366',
            ),
            array(
                'label'      => 'Telegram',
                'slug'       => 'telegram',
                'icon_class' => 'fa-brands fa-telegram',
                'svg_icon'   => 'telegram',
                'color'      => '#26A5E4',
            ),
            array(
                'label'      => 'Discord',
                'slug'       => 'discord',
                'icon_class' => 'fa-brands fa-discord',
                'svg_icon'   => 'discord',
                'color'      => '#5865F2',
            ),
            array(
                'label'      => 'Pinterest',
                'slug'       => 'pinterest',
                'icon_class' => 'fa-brands fa-pinterest',
                'svg_icon'   => 'pinterest',
                'color'      => '#E60023',
            ),
            array(
                'label'      => 'Reddit',
                'slug'       => 'reddit',
                'icon_class' => 'fa-brands fa-reddit',
                'svg_icon'   => 'reddit',
                'color'      => '#FF4500',
            ),
            array(
                'label'      => 'Snapchat',
                'slug'       => 'snapchat',
                'icon_class' => 'fa-brands fa-snapchat',
                'svg_icon'   => 'snapchat',
                'color'      => '#FFFC00',
            ),
            array(
                'label'      => 'WeChat',
                'slug'       => 'wechat',
                'icon_class' => 'fa-brands fa-weixin',
                'svg_icon'   => 'wechat',
                'color'      => '#09B83E',
            ),
            array(
                'label'      => 'Line',
                'slug'       => 'line',
                'icon_class' => 'fa-brands fa-line',
                'svg_icon'   => 'line',
                'color'      => '#00C300',
            ),
            array(
                'label'      => 'Viber',
                'slug'       => 'viber',
                'icon_class' => 'fa-brands fa-viber',
                'svg_icon'   => 'viber',
                'color'      => '#7360F2',
            ),
            array(
                'label'      => 'Signal',
                'slug'       => 'signal',
                'icon_class' => 'fa-solid fa-signal',
                'svg_icon'   => 'signal',
                'color'      => '#3A76F0',
            ),
            array(
                'label'      => 'Slack',
                'slug'       => 'slack',
                'icon_class' => 'fa-brands fa-slack',
                'svg_icon'   => 'slack',
                'color'      => '#4A154B',
            ),
            array(
                'label'      => 'Skype',
                'slug'       => 'skype',
                'icon_class' => 'fa-brands fa-skype',
                'svg_icon'   => 'skype',
                'color'      => '#00AFF0',
            ),
            array(
                'label'      => 'GitHub',
                'slug'       => 'github',
                'icon_class' => 'fa-brands fa-github',
                'svg_icon'   => 'github',
                'color'      => '#181717',
            ),
            array(
                'label'      => 'GitLab',
                'slug'       => 'gitlab',
                'icon_class' => 'fa-brands fa-gitlab',
                'svg_icon'   => 'gitlab',
                'color'      => '#FC6D26',
            ),
            array(
                'label'      => 'Bitbucket',
                'slug'       => 'bitbucket',
                'icon_class' => 'fa-brands fa-bitbucket',
                'svg_icon'   => 'bitbucket',
                'color'      => '#0052CC',
            ),
            array(
                'label'      => 'Stack Overflow',
                'slug'       => 'stackoverflow',
                'icon_class' => 'fa-brands fa-stack-overflow',
                'svg_icon'   => 'stackoverflow',
                'color'      => '#F58025',
            ),
            array(
                'label'      => 'CodePen',
                'slug'       => 'codepen',
                'icon_class' => 'fa-brands fa-codepen',
                'svg_icon'   => 'codepen',
                'color'      => '#000000',
            ),
            array(
                'label'      => 'Dribbble',
                'slug'       => 'dribbble',
                'icon_class' => 'fa-brands fa-dribbble',
                'svg_icon'   => 'dribbble',
                'color'      => '#EA4C89',
            ),
            array(
                'label'      => 'Behance',
                'slug'       => 'behance',
                'icon_class' => 'fa-brands fa-behance',
                'svg_icon'   => 'behance',
                'color'      => '#1769FF',
            ),
            array(
                'label'      => 'Flickr',
                'slug'       => 'flickr',
                'icon_class' => 'fa-brands fa-flickr',
                'svg_icon'   => 'flickr',
                'color'      => '#0063DC',
            ),
            array(
                'label'      => '500px',
                'slug'       => '500px',
                'icon_class' => 'fa-brands fa-500px',
                'svg_icon'   => '500px',
                'color'      => '#0099E5',
            ),
            array(
                'label'      => 'DeviantArt',
                'slug'       => 'deviantart',
                'icon_class' => 'fa-brands fa-deviantart',
                'svg_icon'   => 'deviantart',
                'color'      => '#05CC47',
            ),
            array(
                'label'      => 'Twitch',
                'slug'       => 'twitch',
                'icon_class' => 'fa-brands fa-twitch',
                'svg_icon'   => 'twitch',
                'color'      => '#9146FF',
            ),
            array(
                'label'      => 'Spotify',
                'slug'       => 'spotify',
                'icon_class' => 'fa-brands fa-spotify',
                'svg_icon'   => 'spotify',
                'color'      => '#1DB954',
            ),
            array(
                'label'      => 'SoundCloud',
                'slug'       => 'soundcloud',
                'icon_class' => 'fa-brands fa-soundcloud',
                'svg_icon'   => 'soundcloud',
                'color'      => '#FF5500',
            ),
            array(
                'label'      => 'Apple Music',
                'slug'       => 'apple-music',
                'icon_class' => 'fa-brands fa-apple',
                'svg_icon'   => 'apple',
                'color'      => '#FA243C',
            ),
            array(
                'label'      => 'Bandcamp',
                'slug'       => 'bandcamp',
                'icon_class' => 'fa-brands fa-bandcamp',
                'svg_icon'   => 'bandcamp',
                'color'      => '#629AA9',
            ),
            array(
                'label'      => 'Medium',
                'slug'       => 'medium',
                'icon_class' => 'fa-brands fa-medium',
                'svg_icon'   => 'medium',
                'color'      => '#000000',
            ),
            array(
                'label'      => 'Tumblr',
                'slug'       => 'tumblr',
                'icon_class' => 'fa-brands fa-tumblr',
                'svg_icon'   => 'tumblr',
                'color'      => '#36465D',
            ),
            array(
                'label'      => 'Vimeo',
                'slug'       => 'vimeo',
                'icon_class' => 'fa-brands fa-vimeo',
                'svg_icon'   => 'vimeo',
                'color'      => '#1AB7EA',
            ),
            array(
                'label'      => 'Mastodon',
                'slug'       => 'mastodon',
                'icon_class' => 'fa-brands fa-mastodon',
                'svg_icon'   => 'mastodon',
                'color'      => '#6364FF',
            ),
            array(
                'label'      => 'Shopee',
                'slug'       => 'shopee',
                'icon_class' => 'fa-brands fa-shopee',
                'svg_icon'   => 'shopee',
                'color'      => '#EE4D2D',
            ),
            array(
                'label'      => 'Threads',
                'slug'       => 'threads',
                'icon_class' => 'fa-brands fa-threads',
                'svg_icon'   => 'threads',
                'color'      => '#000000',
            ),
            array(
                'label'      => 'Lemon8',
                'slug'       => 'lemon8',
                'icon_class' => 'fa-solid fa-lemon',
                'svg_icon'   => 'lemon8',
                'color'      => '#000000',
            ),
        );
    }

    /**
     * Get platform by slug
     *
     * @param string $slug Platform slug
     * @return array|null Platform data or null if not found
     */
    public static function get_platform_by_slug($slug)
    {
        $platforms = self::get_platforms();
        foreach ($platforms as $platform) {
            if ($platform['slug'] === $slug) {
                return $platform;
            }
        }
        return null;
    }
}
