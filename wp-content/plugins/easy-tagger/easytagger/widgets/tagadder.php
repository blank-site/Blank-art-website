<?php


class TagAdder extends WP_Widget {
	function __construct(){
            parent::__construct('tagadder','ET Tag Adder');
            
            wp_enqueue_script('ettagadderscript',ET_URL .'/inc/js/etTagAdder.js',array('jquery') );
            wp_localize_script('ettagadderscript', 'l10nobject', 
                 array(
                    'err_validation' => __('Invalid input: tags must be one-word terms','easytagger'),
                     'tags' => __('Tags','easytagger'),
                     'succadd' => __('successfully added','easytagger')
                  )
             );
            wp_enqueue_style('ettagadderstyle',ET_URL .'/css/tagadder-style.css');
            
            add_action('wp_ajax_add_tags','add_tags');
            add_action('wp_ajax_nopriv_add_tags','add_tags');
        }

	function form($instance) {
            
           $defaults = array(
               'description' => __('Please enter tags separated by commas','easytagger'),
               'button_text' => __('Add','easytagger'),
               'input_size' => 10
           );
           
           $instance = wp_parse_args((array)$instance, $defaults);
            
          $field_id = $this->get_field_id('description');
          $field_name = $this->get_field_name('description');
          $field_id2 = $this->get_field_id('button_text');
          $field_name2 = $this->get_field_name('button_text');
          $field_id3 = $this->get_field_id('input_size');
          $field_name3 = $this->get_field_name('input_size');
	  ?><br/><p><label for="<?php echo $field_id; ?>"><?php _e('Stimulus label:','easytagger'); ?><input type="text" class="widefat" id="<?php echo $field_id; ?>" name="<?php echo $field_name; ?>" value="<?php echo $instance['description']; ?> " /><label></p>
             <br/><p><label for="<?php echo $field_id2; ?>"><?php _e('Button text:','easytagger'); ?><input type="text" class="widefat" id="<?php echo $field_id2; ?>" name="<?php echo $field_name2; ?>" value="<?php echo $instance['button_text']; ?> " /><label></p>
             <br/><p><label for="<?php echo $field_id3; ?>"><?php _e('Input size:','easytagger'); ?><input type="text" class="widefat" id="<?php echo $field_id3; ?>" name="<?php echo $field_name3; ?>" value="<?php echo $instance['input_size']; ?> " /><label></p>


         <?php
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
                $instance['description'] = strip_tags($new_instance['description']);
                $instance['button_text'] = $new_instance['button_text'];
                $instance['input_size'] = $new_instance['input_size'];
                return $instance;
	}

	function widget($args, $instance=array()) {
            global $wp_query,$etdb;
            
            if(! is_user_logged_in() )
            {
                ?> <a href="<?php echo wp_login_url() ?>"><?php _e('You must login to add tags','easytagger'); ?></a>
                <?php
            }
            else if( $etdb->is_banned(wp_get_current_user()->ID) ){
                ?><p><?php _e('Sorry you are banned from tagging','easytagger'); ?></p><?php
            }
            else
            {
                extract($args,EXTR_SKIP);
                echo $before_widget;
                
                if( isset($args['description']) )
                    $instance = $args;
                
                 ?> 
                <div id="tagadder">
                     <p><?php echo $instance['description'];  ?></p>
                     <p>
                     <input type="hidden" id="tagadderadminurl" value="<?php echo admin_url('admin-ajax.php'); ?>" />
                     <input type="hidden" id="tagadderpostid" value="<?php echo $wp_query->post->ID; ?>" />
                     <input type="hidden" id="tagadderuserid" value="<?php echo get_current_user_id(); ?>" />
                     <input size="<?php echo $instance['input_size']; ?>" type="text" id="addTagsInput" value="" />
                     <input type="submit" name="addTagsSubmit" id="addTagsSubmit" value="<?php echo $instance['button_text']; ?>" /></p>
                     <p id="tagAdderError">Text 1</p>
                     <p id="tagAdderAdded">Text 2</p>
                </div>
                 
                <?php
                echo $after_widget;
            }
              
                
	}

}

add_action(
        'widgets_init',
        create_function('','register_widget("TagAdder");')
 );


function et_tagadder($args=array()){
    
      $defaults = array(
               'description' => __('Please enter tags separated by commas','easytagger'),
               'button_text' => __('Add','easytagger'),
               'input_size' => 10
      );
      
    $args = wp_parse_args($args,$defaults);
    
   $ta = new TagAdder();
   echo $ta->widget($args);
}

?>
