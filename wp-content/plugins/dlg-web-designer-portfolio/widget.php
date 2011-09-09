<?php
/**
 * FooWidget Class
 */
class FooWidget extends WP_Widget {
    /** constructor */
    function FooWidget() {
        parent::WP_Widget(false, $name = 'DLG Portfolio');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
		global $wpdb;
        $title = apply_filters('widget_title', $instance['title']);
        ?>
              <?php echo $before_widget; ?>
                  <?php if ( $title )
                        echo $before_title . $title . $after_title; 
						
						
						$r = $wpdb->get_results("SELECT *  FROM wp_dlg_portfolio WHERE picture != '' AND (type = 1 OR type = 2) ORDER BY RAND() limit 3", ARRAY_A);
						
							for($i=0; $i<count($r); $i++){
	echo '<div style=" text-align:center;padding:5px;margin-bottom:5p;margin-top:10px;">
<h3><a href="?pid='.$r[$i]['id'].'">'.$r[$i]['name'].'</a></h3>
<a href="/clients/?pid='.$r[$i]['id'].'">
<img src="'.get_bloginfo('wpurl').'/wp-content/plugins/dlg-portfolio/thumbnails.php?src='.get_bloginfo('wpurl').'/wp-content/plugins/dlg-portfolio/uploads/'.$r[$i]['picture'].'&w=230&h=150" border="0">
</a>
</div>';
		}
						?>
                 <div style="text-align:right"><a href="/clients/">Click here to view more</a></div>
              <?php echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
        $title = esc_attr($instance['title']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <?php 
    }

} // class FooWidget

add_action('widgets_init', create_function('', 'return register_widget("FooWidget");'));

?>