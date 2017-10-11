<?php
if (!defined('ABSPATH')) exit;

class Ppw_Widget extends WP_Widget
{
    public function __construct()
    {
        $widget_options = array(
            'classname' => 'ppw_widget'
        );
        parent::__construct('ppw_widget', 'Preview Post Widget', $widget_options);
    }


    public function widget($args, $instance)
    {
        global $post;
        switch ($instance['ppw_layout']) {
            case 'ppw_layout_1': ?>
                <div class="ppw_layout_1">
                    <div class="ppw_img">
                    <h1><?php the_title() ?> </h1>
                    <?php if (has_post_thumbnail()) { ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                            <?php the_post_thumbnail('large'); ?>
                        </a>
                    <?php } ?></div>
                    <div class="ppw_layout_1_description" style="display: none">
                        <?php $the_excerpt = $this->get_excerpt_by_id($post->ID);
                        ?>
                        <div class="the_excerpt">   <?php echo $the_excerpt ?></div>
                    </div>
                </div>
                <?php break;
            case 'ppw_layout_2': ?>
                <div class="ppw_layout_1 column-2">
                <div class="left-column">
                    <h2><?php the_title() ?> </h2>
                    <?php $the_excerpt = $this->get_excerpt_by_id($post->ID);
                    ?>
                    <?php echo $the_excerpt; ?>
                </div>
                <div class="right-column">
                    <?php if (has_post_thumbnail()) { ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    <?php } ?>
                </div>
                </div><?php
                break;
            case 'ppw_layout_3': ?>
                <div class="ppw_layout_1 column-2">
                <div class="left-column">
                    <?php if (has_post_thumbnail()) { ?>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    <?php } ?>

                </div>
                <div class="right-column">
                    <h2> <?php the_title() ?></h2>
                    <?php $the_excerpt = $this->get_excerpt_by_id($post->ID);
                    ?>
                    <?php echo $the_excerpt; ?>
                </div>
                </div><?php

                break;
        }
        ?>
        <?php echo $args['after_widget'];
    }

    public function get_excerpt_by_id($post_id)
    {
        $the_post = get_post($post_id);
        $the_excerpt = ($the_post ? $the_post->post_content : null);
        $excerpt_length = 35;
        $the_excerpt = strip_tags(strip_shortcodes($the_excerpt));
        $words = explode(' ', $the_excerpt, $excerpt_length + 1);

        if (count($words) > $excerpt_length) :
            array_pop($words);
            array_push($words, '…');
            $the_excerpt = implode(' ', $words);
        endif;

        return $the_excerpt;
    }

    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : ''; ?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input type="text" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>"/>
        </p>

        <?php
        $post = !empty($instance['post']) ? $instance['post'] : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id('post'); ?>">Post:</label>
            <input type="text" id="<?php echo $this->get_field_id('post'); ?>"
                   name="<?php echo $this->get_field_name('post'); ?>" value="<?php echo esc_attr($post); ?>"/>
        </p>
        <?php
        $layout = !empty($instance['ppw_layout']) ? $instance['ppw_layout'] : ''; ?>
        <p>
            <label for="<?php echo $this->get_field_id(''); ?>">Layout:
                <select class="widefat" id="<?php echo $this->get_field_id('ppw_layout'); ?>"
                        name="<?php echo $this->get_field_name('ppw_layout'); ?>">
                    <option <?php selected(!empty($instance['ppw_layout']) ? $instance['ppw_layout'] : '', 'ppw_layout_1'); ?>
                        value="ppw_layout_1" <?php echo !empty($this->get_field_id('ppw_layout')) && $this->get_field_id('ppw_layout') == 'ppw_layout_1' ? 'selected' : ''; ?> ><?php _e('Big featured image, with big title text over it', '') ?></option>
                    <option <?php selected(!empty($instance['ppw_layout']) ? $instance['ppw_layout'] : '', 'ppw_layout_2'); ?>
                        value="ppw_layout_2" <?php echo !empty($this->get_field_id('ppw_layout')) && $this->get_field_id('ppw_layout') == 'ppw_layout_2' ? 'selected' : ''; ?> ><?php _e('2 columns block with featured image floated to the left', '') ?></option>
                    <option <?php selected(!empty($instance['ppw_layout']) ? $instance['ppw_layout'] : '', 'ppw_layout_3'); ?>
                        value="ppw_layout_3" <?php echo !empty($this->get_field_id('ppw_layout')) && $this->get_field_id('ppw_layout') == 'ppw_layout_3' ? 'selected' : ''; ?> ><?php _e('2 columns block with featured image floated to the right', '') ?></option>
                </select>
            </label>
        </p>
        <?php wp_nonce_field('update', 'ppw_nonce_field'); ?>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        if (!empty($_POST) && check_admin_referer('update', 'ppw_nonce_field')) {
            $instance = $old_instance;
            $instance['title'] = (isset($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
            $instance['post'] = (isset($new_instance['post'])) ? strip_tags($new_instance['post']) : '';
            $instance['ppw_layout'] = (isset($new_instance['ppw_layout'])) ? strip_tags($new_instance['ppw_layout']) : '';
            return $instance;
        }
        return false;
    }
}

