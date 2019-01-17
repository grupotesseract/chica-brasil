<?php

class Ciloe_Widgets_Instagram extends WP_Widget
{
    function __construct()
    {
        $widget_ops = array(
            'description' => esc_html__('Show off your favorite Instagram photos', 'ciloe')
        );

        $control_ops = array(
            'width'  => 'auto',
            'height' => 'auto',
        );

        parent::__construct('ciloe_instagram', esc_html__('Ciloe: Instagram', 'ciloe'), $widget_ops, $control_ops);
    }

    function widget($args, $instance)
    {
        extract($args);

        $title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
        $id = empty($instance['id']) ? '' : $instance['id'];
        $token = empty($instance['token']) ? '' : $instance['token'];
        $limit = empty($instance['limit']) ? 4 : ( int )$instance['limit'];
        $columns = empty($instance['columns']) ? 2 : ( int )$instance['columns'];

        echo '' . $before_widget;

        if (!empty($title)) {
            echo '' . $before_title . $title . $after_title;
        }

        if (intval($id) === 0) {
            echo '<p>' . esc_html__('No user ID specified.', 'ciloe') . '</p>';
        }

        $transient_var = $id . '_' . $limit;

        if (false === ($items = get_transient($transient_var)) && !empty($id) && !empty($token)) {

            $response = wp_remote_get('https://api.instagram.com/v1/users/' . esc_attr($id) . '/media/recent/?access_token=' . esc_attr($token) . '&count=' . esc_attr($limit));
            if (!is_wp_error($response)) {
                $response_body = json_decode($response['body']);

                if ($response_body->meta->code !== 200) {
                    echo '<p>' . esc_html__('User ID and access token do not match. Please check again.', 'ciloe') . '</p>';
                }

                $items_as_objects = $response_body->data;
                $items = array();

                foreach ($items_as_objects as $item_object) {
                    $item['link'] = $item_object->link;
                    $item['src'] = $item_object->images->low_resolution->url;
                    $items[] = $item;
                }

                set_transient($transient_var, $items, 60 * 60);
            }
        }

        $output = '<div class="ciloe-instagram clear cols-' . esc_attr($columns) . '">';

        if (isset($items) && $items) {
            foreach ($items as $item) {
                $link = $item['link'];
                $image = $item['src'];
                $output .= '<div class="item"><a target="_blank" href="' . esc_url($link) . '"><img width="320" height="320" src="' . esc_url($image) . '" alt="Instagram" /></a></div>';
            }
        }

        $output .= '</div>';

        echo '' . $output . $after_widget;
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['id'] = $new_instance['id'];
        $instance['token'] = $new_instance['token'];
        $instance['limit'] = strip_tags($new_instance['limit']);
        $instance['columns'] = strip_tags($new_instance['columns']);

        return $instance;
    }

    function form($instance)
    {
        $instance = wp_parse_args(( array )$instance, array('title' => '', 'id' => '', 'token' => '', 'limit' => 4, 'columns' => 2));
        $title = strip_tags($instance['title']);
        $id = isset($instance['id']) ? $instance['id'] : array();
        $token = isset($instance['token']) ? $instance['token'] : array();
        $limit = ( int )$instance['limit'];
        $columns = ( int )$instance['columns'];
        $allow_html = array(
            'a' => array(
                'href'   => array(),
                'target' => array(),
            ),
        );

        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'ciloe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('id')); ?>"><?php echo wp_kses('Instagram user ID (<a href="https://smashballoon.com/instagram-feed/find-instagram-user-id/" target="_blank">Lookup your User ID</a>)', $allow_html); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('id')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('id')); ?>" type="text"
                   value="<?php echo esc_attr($id); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('token')); ?>"><?php echo wp_kses('Access token (<a href="http://instagram.pixelunion.net/" target="_blank">Generate access token</a>)', $allow_html); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('token')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('token')); ?>" type="text"
                   value="<?php echo esc_attr($token); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('limit')); ?>"><?php esc_html_e('Number of Photos:', 'ciloe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('limit')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('limit')); ?>" type="number" min="1"
                   value="<?php echo esc_attr($limit); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id('columns')); ?>"><?php esc_html_e('Columns (1-5):', 'ciloe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('columns')); ?>"
                   name="<?php echo esc_attr($this->get_field_name('columns')); ?>" type="number" min="1" max="5"
                   step="1" value="<?php echo esc_attr($columns); ?>"/>
        </p>
        <?php
    }
}

add_action('widgets_init', 'Ciloe_Widgets_Instagram');
function Ciloe_Widgets_Instagram()
{
    register_widget('Ciloe_Widgets_Instagram');
}
