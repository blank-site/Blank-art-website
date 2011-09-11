<?php
/*
Plugin Name: Simple MailChimp Email List Subscriber
Plugin URI: http://www.najeebmedia.com/2011/07/13/mailchimp-wordpress-plugin-by-nmedia/
Description: This is just one from field collection email and sending this to your MailChimp Account List.
Version: 1.1
Author: Najeeb Ahmad
Author URI: http://www.najeebmedia.com/
*/



//ini_set('display_errors',1);
//error_reporting(E_ALL);

class nmMailChimp extends WP_Widget {
	
	/**
	* constructor
	*/	 
	function nmMailChimp() {
		parent::WP_Widget('nmedia_mail_chimp', 'MailChimp Widget', array('description' => 'MailChimp Widget by najeebmedia.com.Has be modified by LIUYI. Do not update it'));	
		
	}
	
	
  
  public function set_up_admin_page () {
		
  	add_submenu_page('options-general.php', 'MailChimp Widget Options', 
					 'MailChimp Widget', 'activate_plugins', __FILE__, array('nmMailChimp', 'admin_page'));		
	}
	
	
	public function admin_page()
	{
		$file = dirname(__FILE__).'/options.php';
		include($file);
	}
	
  
  /**
	 * display widget
	 */	 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['nm_mc_title']) ? '&nbsp;' : apply_filters('widget_title', $instance['nm_mc_title']);
		//if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		
		nmMailChimp::nm_load_form($instance['nm_mc_list_id'], $instance['nm_mc_show_names'], $instance['nm_mc_bg']);
		
		echo $after_widget;
	}
	
	/**
	 *	update/save function
	 */	 	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['nm_mc_title'] = strip_tags($new_instance['nm_mc_title']);
		$instance['nm_mc_list_id'] = strip_tags($new_instance['nm_mc_list_id']);
		$instance['nm_mc_show_names'] = strip_tags($new_instance['nm_mc_show_names']);
		$instance['nm_mc_bg'] = strip_tags($new_instance['nm_mc_bg']);
		return $instance;
	}
	
	/**
	 *	admin control form
	 */	 	
	function form($instance) {
		$default = 	array( 'nm_mc_title' 	=> __('MailChimp Widget'),
						   'nm_mc_list_id' => __('0'),
						   'nm_mc_show_names' => __('0'),
						   'nm_mc_bg' => __('bg-blue.png'));
						   
		$instance = wp_parse_args( (array) $instance, $default );
		
		$field_id_title = $this->get_field_id('nm_mc_title');
		$field_name_title = $this->get_field_name('nm_mc_title');
		
		$field_id_list = $this->get_field_id('nm_mc_list_id');
		$field_name_list = $this->get_field_name('nm_mc_list_id');
		
		$field_id_names = $this->get_field_id('nm_mc_show_names');
		$field_name_names = $this->get_field_name('nm_mc_show_names');
		
		$field_id_bg = $this->get_field_id('nm_mc_bg');
		$field_name_bg = $this->get_field_name('nm_mc_bg');
		
				
		$api_dir = dirname(__FILE__).'/api_mailchimp/mcapi_lists.php';
		include($api_dir);
		
		$file = dirname(__FILE__).'/control.php';
		include($file);
		
		
	}
  
 
  function nm_load_form($list_id, $show_names, $bgbox)
  {
	$file = dirname(__FILE__).'/form.php';
	include($file);
  }
  
  
}

/* register widget when loading the WP core */
add_action('widgets_init', 'just_register_widgets');

add_action('admin_menu', array('nmMailChimp', 'set_up_admin_page'));

function just_register_widgets(){
	// curl need to be installed
	register_widget('nmMailChimp');
}

?>