<?php
    class ciloe_newsletter_widget extends WP_Widget
    {
        function __construct()
        {
            /* Widget settings. */
            $widget_ops = array('classname' => 'ciloe_newsletter_widget', 'description' => esc_html__('A widget that displays your newsletter', 'ciloe'));
            /* Create the widget. */
            parent::__construct('ciloe_newsletter_widget', esc_html__('Ciloe: Newsletter', 'ciloe'), $widget_ops);
        }

        function widget($args, $instance)
        {
            extract($args);
            $title = apply_filters('widget_title', $instance['title']);
            $placeholder = $instance['placeholder'];
            $desc = $instance['desc'];
            $submit = $instance['submit'];
            $css_class = array('widget-ciloe-newsletter');
            echo balanceTags($before_widget);
            ?>
            <?php if ($title) : ?>
                <h2 class="widgettitle"><?php echo esc_attr($title);?></h2>
            <?php endif;?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <div class="newsletter-content">
                    <div class="header-newsletter">
                        <?php if($desc):?>
                            <div class="newsletter-subtitle"><?php echo esc_attr($desc);?></div>
                        <?php endif;?>
                    </div>
                    <form class="newsletter-form-wrap">
                        <input class="email" type="email" name="email"
                               placeholder="<?php echo esc_attr($placeholder); ?>">
                        <button type="submit" name="submit_button" class="btn-submit submit-newsletter"><?php echo esc_attr($submit);?> <i class="fa fa-caret-right"></i></button>
                    </form>
                </div>
            </div>
            <?php
            echo balanceTags($after_widget);
        }

        function update($new_instance, $old_instance)
        {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['desc'] = strip_tags($new_instance['desc']);
            $instance['placeholder'] = $new_instance['placeholder'];
            $instance['submit'] = strip_tags($new_instance['submit']);
            return $instance;
        }

        function form($instance)
        {
            $defaults = array('title' => esc_html__('Newsletter', 'ciloe'),'desc'=>'', 'placeholder' => esc_html__('Your email address...', 'ciloe'), 'submit' => esc_html__('Submit', 'ciloe'));
            $instance = wp_parse_args((array)$instance, $defaults);

            $desc_value = $instance[ 'desc' ];
            $desc_field = array(
                'id'    => $this->get_field_name( 'desc' ),
                'name'  => $this->get_field_name( 'desc' ),
                'type'  => 'textarea',
                'title' => esc_html__( 'Description: ', 'ciloe' ),
            );
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'ciloe'); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                       value="<?php echo balanceTags($instance['title']); ?>"/>
            </p>
            <?php
                echo '<p>';
                echo cs_add_element( $desc_field, $desc_value );
                echo '</p>';
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('placeholder')); ?>"><?php esc_html_e('Text placeholder:', 'ciloe'); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('placeholder')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('placeholder')); ?>"
                       value="<?php echo esc_html($instance['placeholder']); ?>"/>
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('submit')); ?>"><?php esc_html_e('Text submit:', 'ciloe'); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('submit')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('submit')); ?>"
                       value="<?php echo esc_html($instance['submit']); ?>"/>
            </p>
            <?php
        }
    }

    add_action('widgets_init', 'ciloe_newsletter_widget');
    function ciloe_newsletter_widget()
    {
        register_widget('ciloe_newsletter_widget');
    }