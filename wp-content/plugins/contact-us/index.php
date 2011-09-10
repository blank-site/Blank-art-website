<?php
/*
Plugin Name: Contact Us
Plugin URI: http://wordpress.org/extend/plugins/contact-us/
Description: Adds the ability to enter business contact information, business hours, business location, etc and output the details in your posts, pages or templates.
Version: 1.6
Author: EON Media Group
Author URI: http://eonmediagroup.com
*/

if ( ! class_exists( 'ContactUs' ) )
{
	class ContactUs
	{
		var $name = 'Contact Us';
		var $tag = 'contact_us';
		var $options = array();
		var $messages = array();
		
		function ContactUs()
		{
			if ( $options = get_option( $this->tag ) ) {
				$this->options = $options;
			}
			if ( is_admin() ) {
				register_activation_hook( __FILE__, array( &$this, 'activate' ) );
				add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
				add_action( 'admin_init', array( &$this, 'admin_init' ) );
				add_filter( 'plugin_row_meta', array( &$this, 'plugin_row_meta' ), 10, 2 );
			} else {
				add_shortcode( 'contact_us', array( &$this, 'shortcode' ) );
				add_filter( 'contact_us', array( &$this, 'build'), 1 );
				add_filter( 'contact_form', array( &$this, 'build_form'), 1 );
			}
		}
		
		function activate()
		{
			if ( ! $this->options ) {
				update_option( $this->tag, array(
					'email' => get_option( 'admin_email' )
				) );
			}
		}
		
		function admin_menu()
		{
			add_options_page($this->name,$this->name,'publish_pages',$this->tag,array( &$this, 'settings' ));
		}
		
		function admin_init()
		{
			register_setting( $this->tag.'_options', $this->tag );
		}
		
		function settings()
		{
			include_once( 'settings.php' );
		}
		
		function plugin_row_meta( $links, $file )
		{
			$plugin = plugin_basename( __FILE__ );
			if ( $file == $plugin ) {
				return array_merge(
					$links,
					array( sprintf(
						'<a href="options-general.php?page=%s">%s</a>',
						$this->tag, __( 'Edit Contact Information' )
					) )
				);
			}
			return $links;
		}
		
		function build( $args )
		{
			extract( shortcode_atts( array(
				'type' => false,
				'heading' => '',
				'heading_open_tag' => '<h4>',
				'heading_close_tag' => '</h4>',
				'nl2br' => false,
				'before' => '',
				'after' => ''
			), $args ) );
			$value = $this->value( $type );
			if ( strlen( $value ) == 0 ) {
				return;
			}
			$header = '';
			if( !empty($heading) ){
			  $header = "{$heading_open_tag}{$heading}{$heading_close_tag}";
			}
			if( $nl2br === true || $nl2br === 'true' ){
			  $is_xhtml = true;
			  $value = nl2br($value, $is_xhtml);
			}
			$info = "{$header}{$before}{$value}{$after}";
			if ( $echo ) {
				echo $info;
			} else {
				return $info;
			}
		}
		
		function value( $type = false )
		{
			if ( ( false != $type )  && array_key_exists( $type, $this->options ) ) {
				return ( 'address' == $type ? nl2br( $this->options[$type] ) : $this->options[$type] );
			}
			return null;
		}
		
		// added build_form by licensetoil
		function build_form( $args )
		{
			extract( shortcode_atts( array(
				'include' => false,
				'before' => '',
				'after' => '',
				'echo' => true
			), $args ) );
			$form = $this->form( $include );
			if ( strlen( $form ) == 0 ) {
				return;
			}
			$contact_form = $before.$form.$after;
			if ( $echo ) {
				echo $contact_form;
			} else {
				return $contact_form;
			}
		}
		
		function shortcode( $atts )
		{
			extract( shortcode_atts( array(
				'type' => false,
				'include' => false,
				'heading' => '',
				'heading_open_tag' => '<h4>',
				'heading_close_tag' => '</h4>',
				'nl2br' => false,
				'before' => '',
				'after' => ''
			), $atts ) );
			if ( 'form' == $type ) {
				return $this->form( $include );
			}
			return contact_us( $type, $heading, $heading_open_tag, $heading_close_tag, $nl2br, $before, $after );
		} 
		
		function form( $include = false )
		{
			ob_start();
			if ( ! isset( $this->options['email'] ) || ! is_email( $this->options['email'] ) ) {
				return __( 'You must define an email address on the options page in order to display the contact form.' );
			}			
			if ( isset( $_POST['contact'] ) ) {
				$this->messages['error'] = array();
				if ( ! wp_verify_nonce( $_POST[$this->tag.'_nonce'], $this->tag ) ) {
   					$this->messages['error'][] = __( 'Sorry, the nonce field provided was invalid.' );
				}
				$contact = $_POST['contact'];
				foreach ( $contact AS $key => $value ) {
					switch ( $key ) {
						case 'name':
							$value = apply_filters( 'pre_comment_author_name', $value );
							if ( strlen( $value ) < 1 ) {
								$this->messages['error'][] = __( 'Please enter your name.' );
							}
						break;
						case 'email':
							$value = apply_filters( 'pre_comment_author_email', sanitize_email( $value ) );
							if ( ! is_email( $value ) ) {
								$this->messages['error'][] = __( 'Please enter a valid email address.' );
							}
						break;
						case 'message':
							$value = trim( wp_kses( stripslashes( $value ), array() ) );
							if ( strlen( $value ) < 1 ) {
								$this->messages['error'][] = __( 'Please enter a message.' );
							}
						break;
						default:
							$value = trim( wp_kses( stripslashes( $value ), array() ) );
					}
					$contact[$key] = $value;
				}
				if($this->options['recaptcha_enable'] === 'true'){
				  require_once(WP_PLUGIN_DIR.'/contact-us/recaptchalib-1.11.php');
          $resp = recaptcha_check_answer($this->options['recaptcha_private_key'],$_SERVER["REMOTE_ADDR"],$_POST["recaptcha_challenge_field"],$_POST["recaptcha_response_field"]);
				  if (!$resp->is_valid){
				    $this->messages['error'][] = __( 'reCAPTCHA error: ' ) . $resp->error;
				  }
				}
				if ( count( $this->messages['error'] ) == 0 ) {
					if ( $this->is_blacklisted( $contact ) ) {
						$this->messages['error'][] = __(
							'Sorry, your comment failed the blacklist check and could not be sent.'
						);
					} else if ( $this->is_spam( $contact ) ) {
						$this->messages['error'][] = __(
							'Sorry, your comment failed the spam check and could not be sent.'
						);
					} else {
						if ( $this->send_mail( $contact ) ) {
							$this->messages['ok'] = __(
								'Your message has been sent.'
							);
							unset( $contact );
						} else {
							$this->messages['error'][] = __(
								'Sorry, we were unable to send your message.'
							);
						}
					}
				}
			}
			if ( ( false !== $include ) && file_exists( TEMPLATEPATH.'/'.basename( $include ) ) ) {
				include( TEMPLATEPATH.'/'.basename( $include ) );
			} else {
				include( 'form.php' );
			}
			$form = ob_get_contents(); ob_end_clean();
			return $form;
		}
		
		function is_blacklisted( $contact ) 
		{
			return wp_blacklist_check(
				$contact['name'],
				$contact['email'],
				( isset( $contact['website'] ) ? $contact['email'] : false ),
				$contact['message'],
				preg_replace( '/[^0-9a-fA-F:., ]/', '', $_SERVER['REMOTE_ADDR'] ),
				substr( $_SERVER['HTTP_USER_AGENT'], 0, 254 )
			);
		}
		
		function is_spam( $contact )
		{
			if ( function_exists( 'akismet_http_post' ) ) {
				global $akismet_api_host, $akismet_api_port;
				$comment = array(
					'comment_author' => $contact['name'],
					'comment_author_email' => $contact['email'],
					'comment_author_url' => $contact['email'],
					'contact_form_subject' => '',
					'comment_content' => $contact['message'],
					'user_ip' => preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] ),
					'user_agent' => $_SERVER['HTTP_USER_AGENT'],
					'referrer' => $_SERVER['HTTP_REFERER'],
					'blog' => get_option( 'home' ),
				);
				foreach ( $_SERVER as $key => $value ) {
					if ( ( $key != 'HTTP_COOKIE' ) && is_string( $value )) {
						$comment[$key] = $value;
					}
				}
				$query = '';
				foreach ( $comment as $key => $value ) {
					$query .= $key . '=' . urlencode( $value ) . '&';
				}
				$response = akismet_http_post(
					$query,
					$akismet_api_host,
					'/1.1/comment-check',
					$akismet_api_port
				);
				if ( 'true' == trim($response[1]) ) {
					return true;
				}
			}
			return false;
		}
		
		function send_mail( $contact )
		{
			$headers = array(
				'From: ' . get_bloginfo( 'name' ) . ' <' . $this->options['email'] . '>',
				'Reply-To: ' . $contact['name'] . ' <' . $contact['email'] . '>',
				'Content-Type: text/plain; charset="' . get_option( 'blog_charset' ) . '"'
			);
			$content = '';
			foreach ( $contact AS $key => $value ) {
				if ( ! in_array( $key, array( 'name', 'email', 'submit' ) ) && !empty( $value ) ) {
					if ( 'message' == $key ) {
						$content .= $contact['name'] . ' ' . __('wrote') . ": \r\n\r\n" . $value;
					} else {
						$content .= __( ucwords( $key ) ) . ': ' . $value . "\r\n\r\n";
					}
				}
			}
			return wp_mail(
				$this->options['email'],
				'[' . get_bloginfo('name') . '] ' . __('Contact form'),
				$content,
				implode( "\r\n", $headers )
			);
		}
		
	}
	$contactUs = new ContactUs();
	if ( isset( $contactUs ) ) {
		function contact_us( $t = false, $h = '', $ho = '<h4>', $hc = '</h4>', $nl2br = false, $b = '', $a = '', $e = true ){
			return apply_filters( 'contact_us', array(
				'type' => $t,
				'heading' => $h,
				'heading_open_tag' => $ho,
				'heading_close_tag' => $hc,
				'nl2br' => $nl2br,
				'before' => $b,
				'after' => $a,
				'echo' => $e
			) );
		}
		function contact_form( $i = false, $b = '', $a = '', $e = true ){
			return apply_filters( 'contact_form', array(
				'include' => $i,
				'before' => $b,
				'after' => $a,
				'echo' => $e
			) );
		}
	}
}
