<?php

class TagSearch extends WP_Widget {
	function __construct(){
            parent::__construct('tagsearch','ET Tag Search Widget');
           
            wp_enqueue_style('tagsearchstyle', ET_URL . '/css/tagsearch-style.css');
            wp_enqueue_script('tagsearchscript', ET_URL . '/inc/js/etTagSearch.js');
            wp_enqueue_script('etautocompletescript', ET_URL . '/inc/js/jquery.autocomplete/jquery.autocomplete.js');
            wp_enqueue_style('etautocompletestyle', ET_URL . '/inc/js/jquery.autocomplete/jquery.autocomplete.css');
            
            add_action('wp_ajax_fetch_tags','fetch_tags');
            add_action('wp_ajax_nopriv_fetch_tags','fetch_tags');
        }

	function form($instance) {
            
           $defaults = array(
               'search_button_text' => __('Search','easytagger'),
               'search_input_width' => 100,
               'search_title' => __('Search by tags','easytagger')
           );
           
           $instance = wp_parse_args((array)$instance, $defaults);
            
          $field_id = $this->get_field_id('search_button_text');
          $field_name = $this->get_field_name('search_button_text');
          $field_id2 = $this->get_field_id('search_input_width');
          $field_name2 = $this->get_field_name('search_input_width');
          $field_id3 = $this->get_field_id('search_title');
          $field_name3 = $this->get_field_name('search_title');
	  echo "\r\n".'<p><label for="'.$field_id.'">'.__('Search button text','easytagger').': <input type="text" class="widefat" id="'.$field_id.'" name="'.$field_name.'" value="'. $instance['search_button_text'] .'" /><label></p>';
          echo "\r\n".'<p><label for="'.$field_id2.'">'.__('Search input width','easytagger').': <input type="text" class="widefat" id="'.$field_id2.'" name="'.$field_name2.'" value="'. $instance['search_input_width'] .'" /><label></p>';
          echo "\r\n".'<p><label for="'.$field_id3.'">'.__('Search title','easytagger').': <input type="text" class="widefat" id="'.$field_id3.'" name="'.$field_name3.'" value="'. $instance['search_title'] .'" /><label></p>';
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
                $instance['search_button_text'] = $new_instance['search_button_text'];
                $instance['search_input_width'] = $new_instance['search_input_width'];
                $instance['search_title'] = $new_instance['search_title'];
                return $instance;
	}

	function widget($args, $instance) {
            extract($args,EXTR_SKIP);
            
            echo $before_widget;
            ?>
            <p> <?php echo $instance['search_title']; ?><br/>
                <input id="tagsSearchInput" size="<?php echo $instance['search_input_width']; ?>" />
                <input id="tagsSearchSubmit" type="submit" value="<?php echo $instance['search_button_text']; ?>" />
                <input id="tagsSearchAdminUrl" type="hidden" value="<?php echo admin_url('admin-ajax.php'); ?>" />
                <input id="tagsSearchSiteUrl" type="hidden" value="<?php echo site_url(); ?>" />
                <input id="tagsSearchRedirection" type="hidden" value="<?php echo get_option('et_redirection',1);?>" />
                <input id="tagsSearchRedirectionUrl" type="hidden" value="<?php echo get_option('et_redirect_url'); ?>" />
            </p>
            
            <?
            echo $after_widget;
	}

}

add_action(
        'widgets_init',
        create_function('','register_widget("TagSearch");')
 );

?>
