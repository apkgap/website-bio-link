<?php

/**
 * WordPress Widget for Social Links
 *
 * @package Website_Bio_Link
 */

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Social Links WordPress Widget
 */
class WBL_Social_Widget extends \WP_Widget
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct(
            'wbl_social_widget',
            __('Social Links (Bio Link)', 'website-bio-link'),
            array(
                'description' => __('Display your social media links', 'website-bio-link'),
                'classname'   => 'wbl-social-widget',
            )
        );
    }

    /**
     * Widget Frontend Display
     *
     * @param array $args     Widget arguments
     * @param array $instance Widget instance
     */
    public function widget($args, $instance)
    {
        // Get widget settings
        $title = ! empty($instance['title']) ? $instance['title'] : '';
        $social_set_id = ! empty($instance['social_set_id']) ? intval($instance['social_set_id']) : 0;
        $show_label = ! empty($instance['show_label']) ? true : false;
        $style = ! empty($instance['style']) ? $instance['style'] : 'circle';
        $size = ! empty($instance['size']) ? $instance['size'] : 'medium';
        $gap = ! empty($instance['gap']) ? $instance['gap'] : 'medium';
        $align = ! empty($instance['align']) ? $instance['align'] : 'left';

        // Start widget output
        echo $args['before_widget'];

        // Display title if set
        if (! empty($title)) {
            echo $args['before_title'] . apply_filters('widget_title', $title) . $args['after_title'];
        }

        // Display social links
        if ($social_set_id) {
            $social_items = get_post_meta($social_set_id, '_sky_social_items', true);

            if (! empty($social_items) && is_array($social_items)) {
                $render_args = array(
                    'show_label' => $show_label ? 'true' : 'false',
                    'style'      => $style,
                    'size'       => $size,
                    'gap'        => $gap,
                    'align'      => $align,
                );

                if (class_exists('WBL_Social_Renderer')) {
                    $renderer = WBL_Social_Renderer::instance();
                    echo $renderer->render_social_list($social_items, $render_args);
                }
            } else {
                echo '<p class="wbl-widget-notice">' . esc_html__('No social links found in this set.', 'website-bio-link') . '</p>';
            }
        } else {
            echo '<p class="wbl-widget-notice">' . esc_html__('Please select a social set in widget settings.', 'website-bio-link') . '</p>';
        }

        // End widget output
        echo $args['after_widget'];
    }

    /**
     * Widget Backend Form
     *
     * @param array $instance Widget instance
     */
    public function form($instance)
    {
        // Get current values
        $title = ! empty($instance['title']) ? $instance['title'] : '';
        $social_set_id = ! empty($instance['social_set_id']) ? $instance['social_set_id'] : '';
        $show_label = ! empty($instance['show_label']) ? $instance['show_label'] : false;
        $style = ! empty($instance['style']) ? $instance['style'] : 'circle';
        $size = ! empty($instance['size']) ? $instance['size'] : 'medium';
        $gap = ! empty($instance['gap']) ? $instance['gap'] : 'medium';
        $align = ! empty($instance['align']) ? $instance['align'] : 'left';

        // Get available social sets
        $social_sets = $this->get_social_sets();
?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'website-bio-link'); ?>
            </label>
            <input
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                type="text"
                value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('social_set_id')); ?>">
                <?php esc_html_e('Social Set:', 'website-bio-link'); ?>
            </label>
            <select
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('social_set_id')); ?>"
                name="<?php echo esc_attr($this->get_field_name('social_set_id')); ?>">
                <option value=""><?php esc_html_e('-- Select Social Set --', 'website-bio-link'); ?></option>
                <?php foreach ($social_sets as $id => $name) : ?>
                    <option value="<?php echo esc_attr($id); ?>" <?php selected($social_set_id, $id); ?>>
                        <?php echo esc_html($name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </p>

        <p>
            <input
                class="checkbox"
                type="checkbox"
                id="<?php echo esc_attr($this->get_field_id('show_label')); ?>"
                name="<?php echo esc_attr($this->get_field_name('show_label')); ?>"
                <?php checked($show_label, true); ?> />
            <label for="<?php echo esc_attr($this->get_field_id('show_label')); ?>">
                <?php esc_html_e('Show platform labels', 'website-bio-link'); ?>
            </label>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('style')); ?>">
                <?php esc_html_e('Icon Style:', 'website-bio-link'); ?>
            </label>
            <select
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('style')); ?>"
                name="<?php echo esc_attr($this->get_field_name('style')); ?>">
                <option value="circle" <?php selected($style, 'circle'); ?>><?php esc_html_e('Circle', 'website-bio-link'); ?></option>
                <option value="rounded" <?php selected($style, 'rounded'); ?>><?php esc_html_e('Rounded Square', 'website-bio-link'); ?></option>
                <option value="flat" <?php selected($style, 'flat'); ?>><?php esc_html_e('Flat Outline', 'website-bio-link'); ?></option>
                <option value="minimal" <?php selected($style, 'minimal'); ?>><?php esc_html_e('Minimal', 'website-bio-link'); ?></option>
                <option value="glass" <?php selected($style, 'glass'); ?>><?php esc_html_e('Glassmorphism', 'website-bio-link'); ?></option>
                <option value="gradient" <?php selected($style, 'gradient'); ?>><?php esc_html_e('Gradient', 'website-bio-link'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('size')); ?>">
                <?php esc_html_e('Icon Size:', 'website-bio-link'); ?>
            </label>
            <select
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('size')); ?>"
                name="<?php echo esc_attr($this->get_field_name('size')); ?>">
                <option value="small" <?php selected($size, 'small'); ?>><?php esc_html_e('Small', 'website-bio-link'); ?></option>
                <option value="medium" <?php selected($size, 'medium'); ?>><?php esc_html_e('Medium', 'website-bio-link'); ?></option>
                <option value="large" <?php selected($size, 'large'); ?>><?php esc_html_e('Large', 'website-bio-link'); ?></option>
                <option value="xlarge" <?php selected($size, 'xlarge'); ?>><?php esc_html_e('Extra Large', 'website-bio-link'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('gap')); ?>">
                <?php esc_html_e('Gap Between Icons:', 'website-bio-link'); ?>
            </label>
            <select
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('gap')); ?>"
                name="<?php echo esc_attr($this->get_field_name('gap')); ?>">
                <option value="small" <?php selected($gap, 'small'); ?>><?php esc_html_e('Small', 'website-bio-link'); ?></option>
                <option value="medium" <?php selected($gap, 'medium'); ?>><?php esc_html_e('Medium', 'website-bio-link'); ?></option>
                <option value="large" <?php selected($gap, 'large'); ?>><?php esc_html_e('Large', 'website-bio-link'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('align')); ?>">
                <?php esc_html_e('Alignment:', 'website-bio-link'); ?>
            </label>
            <select
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('align')); ?>"
                name="<?php echo esc_attr($this->get_field_name('align')); ?>">
                <option value="left" <?php selected($align, 'left'); ?>><?php esc_html_e('Left', 'website-bio-link'); ?></option>
                <option value="center" <?php selected($align, 'center'); ?>><?php esc_html_e('Center', 'website-bio-link'); ?></option>
                <option value="right" <?php selected($align, 'right'); ?>><?php esc_html_e('Right', 'website-bio-link'); ?></option>
            </select>
        </p>

        <p class="description">
            <?php esc_html_e('Drag this widget to any widget area (sidebar, footer, etc.) to display your social links.', 'website-bio-link'); ?>
        </p>

<?php
    }

    /**
     * Update Widget Settings
     *
     * @param array $new_instance New widget instance
     * @param array $old_instance Old widget instance
     * @return array Updated instance
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();

        $instance['title'] = ! empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        $instance['social_set_id'] = ! empty($new_instance['social_set_id']) ? intval($new_instance['social_set_id']) : 0;
        $instance['show_label'] = ! empty($new_instance['show_label']) ? true : false;
        $instance['style'] = ! empty($new_instance['style']) ? sanitize_text_field($new_instance['style']) : 'circle';
        $instance['size'] = ! empty($new_instance['size']) ? sanitize_text_field($new_instance['size']) : 'medium';
        $instance['gap'] = ! empty($new_instance['gap']) ? sanitize_text_field($new_instance['gap']) : 'medium';
        $instance['align'] = ! empty($new_instance['align']) ? sanitize_text_field($new_instance['align']) : 'left';

        return $instance;
    }

    /**
     * Get available social sets
     *
     * @return array Social sets
     */
    private function get_social_sets()
    {
        $sets = array();

        // Use get_posts instead of WP_Query to avoid global post conflicts
        $posts = get_posts(array(
            'post_type'      => 'sky_social_set',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        ));

        if (!empty($posts) && !is_wp_error($posts)) {
            foreach ($posts as $post) {
                $sets[$post->ID] = $post->post_title;
            }
        }

        return $sets;
    }
}

/**
 * Register Widget
 */
function wbl_register_social_widget()
{
    // Check if WP_Widget class exists before registering
    if (class_exists('WP_Widget')) {
        register_widget('WBL_Social_Widget');
    }
}
add_action('widgets_init', 'wbl_register_social_widget');
