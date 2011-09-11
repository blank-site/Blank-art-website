<?php

/**
 *
 */

class NS_MC_Plugin {
	private $options;
	private $donate_link = 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JSL4JTA4KMZLG';
	private static $instance;
	private static $mcapi;
	private static $name = 'NS_MC_Plugin';
	private static $prefix = 'ns_mc';
	private static $public_option = 'no';
	private static $textdomain = 'mailchimp-widget';
	private function __construct () {
		self::load_text_domain();
		register_activation_hook(__FILE__, array(&$this, 'set_up_options'));
		 // Set up the settings.
		add_action('admin_init', array(&$this, 'register_settings'));
		 // Set up the administration page.
		add_action('admin_menu', array(&$this, 'set_up_admin_page'));
		 // Fetch the options, and, if they haven't been set up yet, display a notice to the user.
		$this->get_options();
		if ('' == $this->options) {
			add_action('admin_notices', array(&$this, 'admin_notices'));
		}
		 // Add our widget when widgets get intialized.
		add_action('widgets_init', create_function('', 'return register_widget("NS_Widget_MailChimp");'));
		add_filter('plugin_row_meta', array(&$this, 'add_plugin_meta_links'), 10, 2);
	}

	public static function get_instance () {
		if (empty(self::$instance)) {
			self::$instance = new self::$name;
		}
		return self::$instance;
	}
	
	public function add_plugin_meta_links ($links, $file) {
		if (plugin_basename(realpath(dirname(__FILE__) . '/../mailchimp-widget.php')) == $file) {
			$links[] = '<a href="' . $this->donate_link . '">' . __('Donate', 'mailchimp-widget') . '</a>';
		}
		return $links;
	}
	
	public function admin_notices () {
		echo '<div class="error fade">' . $this->get_admin_notices() . '</div>';
	}

	public function admin_page () {
		global $blog_id;	
		$api_key = (is_array($this->options)) ? $this->options['api-key'] : '';
		if (isset($_POST[self::$prefix . '_nonce'])) {
			$nonce = $_POST[self::$prefix . '_nonce'];
			$nonce_key = self::$prefix . '_update_options';
			if (! wp_verify_nonce($nonce, $nonce_key)) {
				?>
				<div class="wrap">
					<div id="icon-options-general" class="icon32">
						<br />
					</div>
					<h2>MailChimp Widget Settings</h2>
					<p><?php  echo __('What you\'re trying to do looks a little shady.', 'mailchimp-widget'); ?></p>
				</div>
				<?php
				return false;
			} else {
				$new_api_key = $_POST[self::$prefix . '-api-key'];
				$new_options['api-key'] = $new_api_key;
				$this->update_options($new_options);
				$api_key = $this->options['api-key'];
			}
		}
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32">
				<br />
			</div>
			<h2><?php echo __('MailChimp Widget Settings', 'mailchimp-widget') ; ?></h2>
		<?php
		if (function_exists('curl_init')) {
		?>
			<p><?php echo __('Enter a valid MailChimp API key here to get started. Once you\'ve done that, you can use the MailChimp Widget from the Widgets menu. You will need to have at least MailChimp list set up before the using the widget.', 'mailchimp-widget') ?> 				
			</p>
			<form action="options.php" method="post">
				<?php settings_fields(self::$prefix . '_options'); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<label for="' . self::$prefix . '-api-key">MailChimp Api Key</label>
						</th>
						<td>
							<input class="regular-text" id="<?php echo self::$prefix; ?>-api-key" name="<?php echo self::$prefix; ?>_options[api-key]" type="password" value="<?php echo $api_key ?>" />
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="<?php echo  __('Save Changes', 'mailchimp-widget'); ?>" />
				</p>
			</form>
		<?php	
		} else {
		?>	
			<p><?php echo __('You need to have the PHP Client URL library enabled for this plugin to work. You can find more information about installing it <a href="http://php.net/manual/en/book.curl.php">here</a>.');?></p><?php
		}
		?>
		</div>
		<?php
	}
	
	public function get_admin_notices () {
		global $blog_id;
		$notice = '<p>';
		$notice .= __('You\'ll need to set up the MailChimp signup widget plugin options before using it. ', 'mailchimp-widget') . __('You can make your changes', 'mailchimp-widget') . ' <a href="' . get_admin_url($blog_id) . 'options-general.php?page=mailchimp-widget/lib/ns_mc_plugin.class.php">' . __('here', 'mailchimp-widget') . '.</a>';
		$notice .= '</p>';
		return $notice;
	}
	
	public function get_mcapi () {
		$api_key = $this->get_api_key();
		if (false == $api_key) {
			return false;
		} else {
			if (empty(self::$mcapi)) {
				self::$mcapi = new MCAPI($api_key);
			}
			return self::$mcapi;
		}
	}
	
	public function get_options () {
		$this->options = get_option(self::$prefix . '_options');
		return $this->options;
	}
	
	public function load_text_domain () {
		load_plugin_textdomain(self::$textdomain, null, str_replace('lib', 'languages', dirname(plugin_basename(__FILE__))));
	}
	
	public function register_settings () {
		register_setting( self::$prefix . '_options', self::$prefix . '_options', array($this, 'validate_api_key'));
	}
	
	public function remove_options () {
		delete_option(self::$prefix . '_options');
	}
	
	public function set_up_admin_page () {
		add_submenu_page('options-general.php', 'MailChimp Widget Options', 'MailChimp Widget', 'activate_plugins', __FILE__, array(&$this, 'admin_page'));
	}

	public function set_up_options () {
		add_option(self::$prefix . '_options', '', '', self::$public_option);
	}
	
	public function validate_api_key ($api_key) {
		//#TODO: Add API validation logic.
		return $api_key;
	}
	
	private function get_api_key () {
		if (is_array($this->options) && ! empty($this->options['api-key'])) {
			return $this->options['api-key'];
		} else {
			return false;
		}
	}
	
	private function update_options ($options_values) {
		$old_options_values = get_option(self::$prefix . '_options');
		$new_options_values = wp_parse_args($options_values, $old_options_values);
		update_option(self::$prefix .'_options', $new_options_values);
		$this->get_options();
	}
}
?>
