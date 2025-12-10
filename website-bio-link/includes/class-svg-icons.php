<?php

/**
 * SVG Icons Manager
 *
 * @package Website_Bio_Link
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * SVG Icons Manager Class
 */
class WBL_SVG_Icons
{

    /**
     * Single instance
     *
     * @var WBL_SVG_Icons
     */
    private static $instance = null;

    /**
     * SVG icons directory path
     *
     * @var string
     */
    private $svg_dir;

    /**
     * SVG icons directory URL
     *
     * @var string
     */
    private $svg_url;

    /**
     * Cache for SVG content
     *
     * @var array
     */
    private $svg_cache = array();

    /**
     * Get instance
     *
     * @return WBL_SVG_Icons
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
        $this->svg_dir = WBL_SOCIAL_PLUGIN_DIR . 'assets/svg-icons/';
        $this->svg_url = WBL_SOCIAL_PLUGIN_URL . 'assets/svg-icons/';
    }

    /**
     * Get SVG icon HTML
     *
     * @param string $icon_name Icon name/slug
     * @param array  $args      Additional arguments
     * @return string SVG HTML or empty string
     */
    public function get_svg_icon($icon_name, $args = array())
    {
        $defaults = array(
            'class'        => '',
            'width'        => '24',
            'height'       => '24',
            'fill'         => 'currentColor',
            'aria-hidden'  => 'true',
            'role'         => 'img',
        );

        $args = wp_parse_args($args, $defaults);

        // Check cache first
        if (isset($this->svg_cache[$icon_name])) {
            $svg_content = $this->svg_cache[$icon_name];
        } else {
            $svg_content = $this->load_svg_file($icon_name);
            if ($svg_content) {
                $this->svg_cache[$icon_name] = $svg_content;
            }
        }

        if (empty($svg_content)) {
            return '';
        }

        // Parse SVG and modify attributes
        $svg_content = $this->modify_svg_attributes($svg_content, $args);

        return $svg_content;
    }

    /**
     * Load SVG file content
     *
     * @param string $icon_name Icon name
     * @return string|false SVG content or false
     */
    private function load_svg_file($icon_name)
    {
        // Map platform names to SVG file names
        $filename = $this->map_platform_to_filename($icon_name);
        $file_path = $this->svg_dir . $filename . '.svg';

        if (!file_exists($file_path)) {
            return false;
        }

        $content = file_get_contents($file_path);
        if ($content === false) {
            return false;
        }

        return $content;
    }

    /**
     * Map platform slug to SVG filename
     *
     * @param string $platform Platform slug
     * @return string SVG filename
     */
    private function map_platform_to_filename($platform)
    {
        $mapping = array(
            'facebook'   => 'facebook',
            'instagram'  => 'instagram',
            'twitter'    => 'x', // Twitter is now X
            'x'          => 'x',
            'youtube'    => 'youtube',
            'linkedin'   => 'linkedin',
            'tiktok'     => 'tiktok',
            'whatsapp'   => 'whatsapp',
            'telegram'   => 'telegram',
            'discord'    => 'discord',
            'pinterest'  => 'pinterest',
            'reddit'     => 'reddit',
            'snapchat'   => 'snapchat',
            'wechat'     => 'wechat',
            'line'       => 'line',
            'viber'      => 'viber',
            'signal'     => 'signal',
            'slack'      => 'slack',
            'skype'      => 'skype',
            'github'     => 'github',
            'gitlab'     => 'gitlab',
            'bitbucket'  => 'bitbucket',
            'stackoverflow' => 'stackoverflow',
            'codepen'    => 'codepen',
            'dribbble'   => 'dribbble',
            'behance'    => 'behance',
            'flickr'     => 'flickr',
            '500px'      => '500px',
            'deviantart' => 'deviantart',
            'twitch'     => 'twitch',
            'spotify'    => 'spotify',
            'soundcloud' => 'soundcloud',
            'apple-music' => 'apple',
            'bandcamp'   => 'bandcamp',
            'medium'     => 'medium',
            'tumblr'     => 'tumblr',
            'vimeo'      => 'vimeo',
            'mastodon'   => 'mastodon',
        );

        return isset($mapping[$platform]) ? $mapping[$platform] : $platform;
    }

    /**
     * Modify SVG attributes
     *
     * @param string $svg_content SVG content
     * @param array  $args        Attributes to modify
     * @return string Modified SVG content
     */
    private function modify_svg_attributes($svg_content, $args)
    {
        // Load SVG as DOM
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadXML($svg_content);
        libxml_clear_errors();

        $svg = $dom->getElementsByTagName('svg')->item(0);
        if (!$svg) {
            return $svg_content;
        }

        // Set attributes
        if (!empty($args['class'])) {
            $existing_class = $svg->getAttribute('class');
            $svg->setAttribute('class', trim($existing_class . ' ' . $args['class']));
        }

        if (!empty($args['width'])) {
            $svg->setAttribute('width', $args['width']);
        }

        if (!empty($args['height'])) {
            $svg->setAttribute('height', $args['height']);
        }

        if (!empty($args['fill'])) {
            // Only set fill if it's not already present in path elements
            $paths = $dom->getElementsByTagName('path');
            foreach ($paths as $path) {
                if (!$path->hasAttribute('fill')) {
                    $path->setAttribute('fill', $args['fill']);
                }
            }
        }

        // Set other attributes
        foreach (array('aria-hidden', 'role', 'aria-label') as $attr) {
            if (isset($args[$attr]) && $args[$attr] !== '') {
                $svg->setAttribute($attr, $args[$attr]);
            }
        }

        // Remove title element if present (for accessibility)
        $titles = $dom->getElementsByTagName('title');
        foreach ($titles as $title) {
            $title->parentNode->removeChild($title);
        }

        return $dom->saveXML($svg);
    }

    /**
     * Get SVG URL
     *
     * @param string $icon_name Icon name
     * @return string SVG URL or empty string
     */
    public function get_svg_url($icon_name)
    {
        $filename = $this->map_platform_to_filename($icon_name);
        $file_path = $this->svg_dir . $filename . '.svg';

        if (!file_exists($file_path)) {
            return '';
        }

        return $this->svg_url . $filename . '.svg';
    }

    /**
     * Check if SVG icon exists
     *
     * @param string $icon_name Icon name
     * @return bool True if exists
     */
    public function svg_exists($icon_name)
    {
        $filename = $this->map_platform_to_filename($icon_name);
        $file_path = $this->svg_dir . $filename . '.svg';
        return file_exists($file_path);
    }

    /**
     * Get available SVG icons
     *
     * @return array List of available icon names
     */
    public function get_available_icons()
    {
        $icons = array();

        if (!is_dir($this->svg_dir)) {
            return $icons;
        }

        $files = glob($this->svg_dir . '*.svg');
        foreach ($files as $file) {
            $filename = basename($file, '.svg');
            $icons[] = $filename;
        }

        return $icons;
    }
}
