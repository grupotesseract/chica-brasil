<?php

    class cosre_socials_list_widget extends WP_Widget
    {
        function __construct()
        {
            /* Widget settings. */
            $widget_ops = array('classname' => 'cosre_socials_list_widget', 'description' => esc_html__('A widget that displays your socials_list', 'cosre'));
            /* Create the widget. */
            parent::__construct('cosre_socials_list_widget', esc_html__('Cosre: Socials', 'cosre'), $widget_ops);
        }

        function widget($args, $instance)
        {
            extract($args);
            $title = apply_filters('widget_title', $instance['title']);
            $tt_checkbox_var = $instance['tt_checkbox_var'];
            $fb_checkbox_var = $instance['fb_checkbox_var'];
            $gg_checkbox_var = $instance['gg_checkbox_var'];
            $yt_checkbox_var = $instance['yt_checkbox_var'];
            $drib_checkbox_var = $instance['drib_checkbox_var'];
            $behan_checkbox_var = $instance['behan_checkbox_var'];
            $tumb_checkbox_var = $instance['tumb_checkbox_var'];
            $inst_checkbox_var = $instance['inst_checkbox_var'];
            $pin_checkbox_var = $instance['pin_checkbox_var'];
            $vimeo_checkbox_var = $instance['vimeo_checkbox_var'];
            $linkedin_checkbox_var = $instance['linkedin_checkbox_var'];
            $rss_checkbox_var = $instance['rss_checkbox_var'];
            $css_class = array('widget-cosre-socials_list');
            echo balanceTags($before_widget);

            if ($title) {
                echo balanceTags($before_title . $title . $after_title);
            } ?>
            <div class="<?php echo esc_attr(implode(' ', $css_class)); ?>">
                <?php if ($tt_checkbox_var): ?>
                    <a class="social" target="_blank" href="<?php echo esc_url($tt_checkbox_var) ?>"
                       title="<?php echo esc_html__('Twitter', 'cosre') ?>"><i class="fa fa-twitter"></i></a>
                <?php endif; ?>
                <?php if ($fb_checkbox_var): ?>
                    <a class="social" target="_blank" href="<?php echo esc_url($fb_checkbox_var) ?>"
                       title="<?php echo esc_html__('Facebook', 'cosre') ?>"><i class="fa fa-facebook"></i></a>
                <?php endif; ?>
                <?php if ($gg_checkbox_var): ?>
                    <a class="social" target="_blank" href="<?php echo esc_url($gg_checkbox_var) ?>"
                       title="<?php echo esc_html__('Google Plus', 'cosre') ?>"><i class="fa fa-google-plus"></i></a>
                <?php endif; ?>
                <?php if ($yt_checkbox_var): ?>
                    <a class="social" target="_blank" href="<?php echo esc_url($yt_checkbox_var) ?>"
                       title="<?php echo esc_html__('Youtube', 'cosre') ?>"><i class="fa fa-youtube"></i></a>
                <?php endif; ?>
                <?php if ($drib_checkbox_var): ?>
                    <a class="social" target="_blank" href="<?php echo esc_url($drib_checkbox_var) ?>"
                       title="<?php echo esc_html__('Dribbble', 'cosre') ?>"><i class="fa fa-dribbble"></i></a>
                <?php endif; ?>
                <?php if ($behan_checkbox_var): ?>
                    <a class="social" target="_blank" href="<?php echo esc_url($behan_checkbox_var) ?>"
                       title="<?php echo esc_html__('Behance', 'cosre') ?>"><i class="fa fa-behance"></i></a>
                <?php endif; ?>
                <?php if ($tumb_checkbox_var): ?>
                    <a class="social" target="_blank" href="<?php echo esc_url($tumb_checkbox_var) ?>"
                       title="<?php echo esc_html__('Tumblr', 'cosre') ?>"><i class="fa fa-tumblr"></i></a>
                <?php endif; ?>
                <?php if ($inst_checkbox_var): ?>
                    <a class="social" target="_blank" href="<?php echo esc_url($inst_checkbox_var) ?>"
                       title="<?php echo esc_html__('Instagram', 'cosre') ?>"><i class="fa fa-instagram"></i></a>
                <?php endif; ?>
                <?php if ($pin_checkbox_var): ?>
                    <a class="social" target="_blank" href="<?php echo esc_url($pin_checkbox_var) ?>"
                       title="<?php echo esc_html__('Pinterest', 'cosre') ?>"><i class="fa fa-pinterest-p"></i></a>
                <?php endif; ?>
                <?php if ($vimeo_checkbox_var): ?>
                    <a class="social" target="_blank" href="<?php echo esc_url($vimeo_checkbox_var) ?>"
                       title="<?php echo esc_html__('Vimeo', 'cosre') ?>"><i class="fa fa-vimeo"></i></a>
                <?php endif; ?>
                <?php if ($linkedin_checkbox_var): ?>
                    <a class="social" target="_blank" href="<?php echo esc_url($linkedin_checkbox_var) ?>"
                       title="<?php echo esc_html__('Linkedin', 'cosre') ?>"><i class="fa fa-linkedin"></i></a>
                <?php endif; ?>
                <?php if ($rss_checkbox_var): ?>
                    <a class="social" target="_blank" href="<?php echo esc_url($rss_checkbox_var) ?>"
                       title="<?php echo esc_html__('RSS', 'cosre') ?>"><i class="fa fa-rss"></i></a>
                <?php endif; ?>
            </div>
            <?php
            echo balanceTags($after_widget);
        }

        function update($new_instance, $old_instance)
        {
            $instance = $old_instance;
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['tt_checkbox_var'] = $new_instance['tt_checkbox_var'];
            $instance['fb_checkbox_var'] = $new_instance['fb_checkbox_var'];
            $instance['gg_checkbox_var'] = $new_instance['gg_checkbox_var'];
            $instance['yt_checkbox_var'] = $new_instance['yt_checkbox_var'];
            $instance['drib_checkbox_var'] = $new_instance['drib_checkbox_var'];
            $instance['behan_checkbox_var'] = $new_instance['behan_checkbox_var'];
            $instance['tumb_checkbox_var'] = $new_instance['tumb_checkbox_var'];
            $instance['inst_checkbox_var'] = $new_instance['inst_checkbox_var'];
            $instance['pin_checkbox_var'] = $new_instance['pin_checkbox_var'];
            $instance['vimeo_checkbox_var'] = $new_instance['vimeo_checkbox_var'];
            $instance['linkedin_checkbox_var'] = $new_instance['linkedin_checkbox_var'];
            $instance['rss_checkbox_var'] = $new_instance['rss_checkbox_var'];
            return $instance;
        }

        function form($instance)
        {
            $defaults = array( 'title' => esc_html__('Social', 'cosre'), 'fb_checkbox_var' => esc_html__('https://facebook.com', 'cosre'), 'tt_checkbox_var' => esc_html__('https://twitter.com', 'cosre'),'gg_checkbox_var'=> esc_html__('https://google-plus.com', 'cosre'),'yt_checkbox_var'       => '','drib_checkbox_var'     => '','behan_checkbox_var'    => '','tumb_checkbox_var'     => '','inst_checkbox_var'     => '','pin_checkbox_var'      => '','vimeo_checkbox_var'    => '','linkedin_checkbox_var' => '','rss_checkbox_var'      => '');
            $instance = wp_parse_args( (array) $instance, $defaults );

            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'cosre'); ?></label>
                <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>"
                       name="<?php echo esc_attr($this->get_field_name('title')); ?>"
                       value="<?php echo balanceTags($instance['title']); ?>"/>
            </p>
            <p>
                <label for="<?php echo  $this->get_field_id('tt_checkbox_var'); ?>"><?php echo esc_html__('Twitter', 'cosre'); ?></label>
                <input class="widefat" type="text" id="<?php echo  $this->get_field_id('tt_checkbox_var'); ?>"
                       name="<?php echo  $this->get_field_name('tt_checkbox_var'); ?>"
                       value="<?php echo balanceTags($instance['tt_checkbox_var']); ?>"/>
            </p>
            <p>
                <label for="<?php echo  $this->get_field_id('fb_checkbox_var'); ?>"><?php echo esc_html__('Facebook', 'cosre'); ?></label>
                <input class="widefat" type="text" id="<?php echo  $this->get_field_id('fb_checkbox_var'); ?>"
                       name="<?php echo  $this->get_field_name('fb_checkbox_var'); ?>"
                       value="<?php echo balanceTags($instance['fb_checkbox_var']); ?>"/>
            </p>
            <p>
                <label for="<?php echo  $this->get_field_id('gg_checkbox_var'); ?>"><?php echo esc_html__('Google Plus', 'cosre'); ?></label>
                <input class="widefat" type="text" id="<?php echo  $this->get_field_id('gg_checkbox_var'); ?>"
                       name="<?php echo  $this->get_field_name('gg_checkbox_var'); ?>"
                       value="<?php echo balanceTags($instance['gg_checkbox_var']); ?>"/>
            </p>
            <p>
                <label for="<?php echo  $this->get_field_id('yt_checkbox_var'); ?>"><?php echo esc_html__('Youtube', 'cosre'); ?></label>
                <input class="widefat" type="text" id="<?php echo  $this->get_field_id('yt_checkbox_var'); ?>"
                       name="<?php echo  $this->get_field_name('yt_checkbox_var'); ?>"
                       value="<?php echo balanceTags($instance['yt_checkbox_var']); ?>"/>
            </p>

            <p>
                <label for="<?php echo  $this->get_field_id('drib_checkbox_var'); ?>"><?php echo esc_html__('Dribbble', 'cosre'); ?></label>
                <input class="widefat" type="text" id="<?php echo  $this->get_field_id('drib_checkbox_var'); ?>"
                       name="<?php echo  $this->get_field_name('drib_checkbox_var'); ?>"
                       value="<?php echo balanceTags($instance['drib_checkbox_var']); ?>"/>
            </p>

            <p>
                <label for="<?php echo  $this->get_field_id('behan_checkbox_var'); ?>"><?php echo esc_html__('Behance', 'cosre'); ?></label>
                <input class="widefat" type="text" id="<?php echo  $this->get_field_id('behan_checkbox_var'); ?>"
                       name="<?php echo  $this->get_field_name('behan_checkbox_var'); ?>"
                       value="<?php echo balanceTags($instance['behan_checkbox_var']); ?>"/>
            </p>

            <p>
                <label for="<?php echo  $this->get_field_id('tumb_checkbox_var'); ?>"><?php echo esc_html__('Tumblr', 'cosre'); ?></label>
                <input class="widefat" type="text" id="<?php echo  $this->get_field_id('tumb_checkbox_var'); ?>"
                       name="<?php echo  $this->get_field_name('tumb_checkbox_var'); ?>"
                       value="<?php echo balanceTags($instance['tumb_checkbox_var']); ?>"/>
            </p>

            <p>
                <label for="<?php echo  $this->get_field_id('inst_checkbox_var'); ?>"><?php echo esc_html__('Instagram', 'cosre'); ?></label>
                <input class="widefat" type="text" id="<?php echo  $this->get_field_id('inst_checkbox_var'); ?>"
                       name="<?php echo  $this->get_field_name('inst_checkbox_var'); ?>"
                       value="<?php echo balanceTags($instance['inst_checkbox_var']); ?>"/>
            </p>

            <p>
                <label for="<?php echo  $this->get_field_id('pin_checkbox_var'); ?>"><?php echo esc_html__('Pinterest', 'cosre'); ?></label>
                <input class="widefat" type="text" id="<?php echo  $this->get_field_id('pin_checkbox_var'); ?>"
                       name="<?php echo  $this->get_field_name('pin_checkbox_var'); ?>"
                       value="<?php echo balanceTags($instance['pin_checkbox_var']); ?>"/>
            </p>

            <p>
                <label for="<?php echo  $this->get_field_id('vimeo_checkbox_var'); ?>"><?php echo esc_html__('Vimeo', 'cosre'); ?></label>
                <input class="widefat" type="text" id="<?php echo  $this->get_field_id('vimeo_checkbox_var'); ?>"
                       name="<?php echo  $this->get_field_name('vimeo_checkbox_var'); ?>"
                       value="<?php echo balanceTags($instance['vimeo_checkbox_var']); ?>"/>
            </p>

            <p>
                <label for="<?php echo  $this->get_field_id('linkedin_checkbox_var'); ?>"><?php echo esc_html__('Linkedin', 'cosre'); ?></label>
                <input class="widefat" type="text" id="<?php echo  $this->get_field_id('linkedin_checkbox_var'); ?>"
                       name="<?php echo  $this->get_field_name('linkedin_checkbox_var'); ?>"
                       value="<?php echo balanceTags($instance['linkedin_checkbox_var']); ?>"/>
            </p>

            <p>
                <label for="<?php echo  $this->get_field_id('rss_checkbox_var'); ?>"><?php echo esc_html__('Rss', 'cosre'); ?></label>
                <input class="widefat" type="text" id="<?php echo  $this->get_field_id('inst_checkbox_var'); ?>"
                       name="<?php echo  $this->get_field_name('rss_checkbox_var'); ?>"
                       value="<?php echo balanceTags($instance['rss_checkbox_var']); ?>"/>
            </p>
            <?php
        }
    }

    add_action('widgets_init', 'cosre_socials_list_widget');
    function cosre_socials_list_widget()
    {
        register_widget('cosre_socials_list_widget');
    }