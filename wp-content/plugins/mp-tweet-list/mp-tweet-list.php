<?php
/*
Plugin Name: MP Tweet List
Plugin URI: http://MikesPickzWS.com/wordpress-plugins/mp-tweet-list
Description: The MP Tweet List allows you to easily display your most recent tweets, with support for multiple Twitter accounts - with no Javascript or Flash needed. This plugin will add a widget that can be used in any widgetized area in your theme. It is fully  customizable, allowing you to choose the widget title, how many tweets to display and which user's tweets to display. It also allows you to choose whether or not to convert @mentions, #hashtags and links independent of eachother. This plugin supports up to two Twitter users at the same time.
Version: 1.0
Author: MikesPickz Web Solutions, Inc.
Author URI: http://MikesPickzWS.com
License: GPL2
*/

/*  Copyright 2011  MikesPickz Web Solutions, Inc.  (email : Mike@MikesPickzWS.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Add Widgets Link on Plugins Page
function mp_tweet_center_add_widget_link($links, $file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);
 
	if ($file == $this_plugin){
		$settings_link = '<a href="widgets.php">'.__("Jump to Widgets").'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}
add_filter('plugin_action_links', 'mp_tweet_center_add_widget_link', 10, 2);

//Start with the Widget Class
class MPTweetList extends WP_Widget {
	//Construct the Widget
    function MPTweetList() {
        parent::WP_Widget(false, $name = 'MP Tweet List');
    }
	
	//Widget Options Form
    function form($instance) {
        $title = esc_attr($instance['title']);
		$user1 = esc_attr($instance['user1']);
		$user2 = esc_attr($instance['user2']); 
		$tweet_count = esc_attr($instance['tweet_count']);
		$convert_links = esc_attr($instance['convert_links']);
		$convert_users = esc_attr($instance['convert_users']);
		$convert_hashs = esc_attr($instance['convert_hashs']); ?>
        <p>
        Brought to you by <br /><a href="http://MikesPickzWS.com/" target="_blank">MikesPickz Web Solutions, Inc.</a>
        </p>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('user1'); ?>"><?php _e('Twitter User Name (no @ sign):'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('user1'); ?>" name="<?php echo $this->get_field_name('user1'); ?>" type="text" value="<?php echo $user1; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('user2'); ?>"><?php _e('Second Twitter User (no @ sign):'); ?></label><br />
            <span style="font-size:8px;">A second Twitter User is Optional</span><br />
			<input class="widefat" id="<?php echo $this->get_field_id('user2'); ?>" name="<?php echo $this->get_field_name('user2'); ?>" type="text" value="<?php echo $user2; ?>" />
		</p>
		<p>
			<?php
            $_1_status = 'unselected'; $_2_status = 'unselected'; $_3_status = 'unselected'; $_4_status = 'unselected';
            $_5_status = 'unselected'; $_6_status = 'unselected'; $_7_status = 'unselected'; $_8_status = 'unselected';
            $_9_status = 'unselected'; $_10_status = 'unselected'; $_11_status = 'unselected'; $_12_status = 'unselected';
            $_13_status = 'unselected'; $_14_status = 'unselected';
            
            $number = $tweet_count;
            
            if ($number == '1') {$_1_status = 'selected';} elseif ($number == '2') {$_2_status = 'selected';}
            elseif ($number == '3') {$_3_status = 'selected';} elseif ($number == '4') {$_4_status = 'selected';}
            elseif ($number == '5') {$_5_status = 'selected';} elseif ($number == '6') {$_6_status = 'selected';}
            elseif ($number == '7') {$_7_status = 'selected';} elseif ($number == '8') {$_8_status = 'selected';}
			elseif ($number == '9') {$_9_status = 'selected';} elseif ($number == '10') {$_10_status = 'selected';}
            elseif ($number == '11') {$_11_status = 'selected';} elseif ($number == '12') {$_12_status = 'selected';}
            elseif ($number == '13') {$_13_status = 'selected';} elseif ($number == '14') {$_14_status = 'selected';}
            ?>
			<label for="<?php echo $this->get_field_id('tweet_count'); ?>"><?php _e('How Many Tweets?:'); ?></label> 
			<select class="widefat" id="<?php echo $this->get_field_id('tweet_count'); ?>" name="<?php echo $this->get_field_name('tweet_count'); ?>">
            	<option <?php echo $_1_status; ?> value="1">1</option><option <?php echo $_2_status; ?> value="2">2</option>
            	<option <?php echo $_3_status; ?> value="3">3</option><option <?php echo $_4_status; ?> value="4">4</option>
            	<option <?php echo $_5_status; ?> value="5">5</option><option <?php echo $_6_status; ?> value="6">6</option>
            	<option <?php echo $_7_status; ?> value="7">7</option><option <?php echo $_8_status; ?> value="8">8</option>
                <option <?php echo $_9_status; ?> value="9">9</option><option <?php echo $_10_status; ?> value="10">10</option>
            	<option <?php echo $_11_status; ?> value="11">11</option><option <?php echo $_12_status; ?> value="12">12</option>
            	<option <?php echo $_13_status; ?> value="13">13</option><option <?php echo $_14_status; ?> value="14">14</option>
            </select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('convert_links'); ?>"><?php _e('Convert Links:'); ?></label>
            <?php if ($convert_links == 'yes') {$y1_status = 'checked=checked';} else {} ?>
			<?php if ($convert_links == 'no') {$n1_status = 'checked=checked';} else {} ?>
			<input name="<?php echo $this->get_field_name('convert_links'); ?>" type="radio" value="yes" <?php echo $y1_status; ?>>Yes
			<input name="<?php echo $this->get_field_name('convert_links'); ?>" type="radio" value="no" <?php echo $n1_status; ?>>No
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('convert_users'); ?>"><?php _e('Convert Usernames:'); ?></label>
            <?php if ($convert_users == 'yes') {$y2_status = 'checked=checked';} else {} ?>
			<?php if ($convert_users == 'no') {$n2_status = 'checked=checked';} else {} ?>
			<input name="<?php echo $this->get_field_name('convert_users'); ?>" type="radio" value="yes" <?php echo $y2_status; ?>>Yes
			<input name="<?php echo $this->get_field_name('convert_users'); ?>" type="radio" value="no" <?php echo $n2_status; ?>>No
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('convert_hashs'); ?>"><?php _e('Convert Hashtags:'); ?></label>
            <?php if ($convert_hashs == 'yes') {$y3_status = 'checked=checked';} else {} ?>
			<?php if ($convert_hashs == 'no') {$n3_status = 'checked=checked';} else {} ?>
			<input name="<?php echo $this->get_field_name('convert_hashs'); ?>" type="radio" value="yes" <?php echo $y3_status; ?>>Yes
			<input name="<?php echo $this->get_field_name('convert_hashs'); ?>" type="radio" value="no" <?php echo $n3_status; ?>>No
		</p><?php 
    }
	
	//Update Widget Options
	function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['title'] = strip_tags(trim($new_instance['title']));
	$instance['user1'] = strip_tags(trim($new_instance['user1']));
	$instance['user2'] = strip_tags(trim($new_instance['user2']));
	$instance['tweet_count'] = strip_tags(trim($new_instance['tweet_count']));
	$instance['convert_links'] = strip_tags(trim($new_instance['convert_links']));
	$instance['convert_users'] = strip_tags(trim($new_instance['convert_users']));
	$instance['convert_hashs'] = strip_tags(trim($new_instance['convert_hashs']));
        return $instance;
    }
	
	//Display the Widget
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$user1 = $instance['user1'];
		$user2 = $instance['user2'];
		$tweet_count = $instance['tweet_count'];
		$max = $tweet_count;
		$m = 0;
		$p = 0;
		if ($user1 != '' && $user2 != '') {
			$max = $max/2;
		}
		$convert_links = $instance['convert_links'];
		$convert_users = $instance['convert_users'];
		$convert_hashs = $instance['convert_hashs'];
		//Gather tweets data
		if ($user1 != '') {
			$user1_url = "http://api.twitter.com/1/statuses/user_timeline.xml?screen_name=".$user1;
			$user1_data = file_get_contents($user1_url);
			$tweet_pattern = "/<text>(.+)<\/text>/";
			preg_match_all ($tweet_pattern, $user1_data, $user1_recent);
			$user1_tweets = '<strong><a href="http://twitter.com/'.$user1.'" target="_blank">@'.$user1.'</a></strong>';
			$user1_tweets .= '<ul class="user1_tweets">';
			while ($m < $max) {
				if ($convert_links == 'yes') {
				$user1_full_tweet = preg_replace("/(https?:\/\/\w+\.\w+\/\w+)/","<a href='$1' target='_blank'>$1</a> ",$user1_recent[0][$m]);
				}
				if ($convert_users == 'yes') {
				if ($convert_links == 'no') { $user1_full_tweet = $user1_recent[0][$m]; }
				$user1_full_tweet = preg_replace("/@(\w+)/","<a href='http://twitter.com/$1' target='_blank'>@$1</a> ",$user1_full_tweet);
				}
				if ($convert_hashs == 'yes') {
				if ($convert_links == 'no') { $user1_full_tweet = $user1_recent[0][$m]; }
				$user1_full_tweet = preg_replace("/#(\w+)/","<a href='http://search.twitter.com/search?q=%23$1' target='_blank'>#$1</a> ",$user1_full_tweet);
				}
				if ($convert_links != 'yes' && $convert_users != 'yes' && $convert_hashs != 'yes') {
					$user1_tweets .= '<li>'.$user1_recent[0][$m].'</li>';
				} else {
				$user1_tweets .= '<li>'.$user1_full_tweet.'</li>';
				}
				$m++;
			}
			$user1_tweets .= '</ul>';
		}
		if ($user2 != '') {
			$user2_url = "http://api.twitter.com/1/statuses/user_timeline.xml?screen_name=".$user2;
			$user2_data = file_get_contents($user2_url);
			$tweet_pattern = "/<text>(.+)<\/text>/";
			preg_match_all ($tweet_pattern, $user2_data, $user2_recent);
			$user2_tweets = '<br /><strong><a href="http://twitter.com/'.$user2.'" target="_blank">@'.$user2.'</a></strong>';
			$user2_tweets .= '<ul class="user2_tweets">';
			while ($p < $max) {
				if ($convert_links == 'yes') {
				$user2_full_tweet = preg_replace("/(https?:\/\/\w+\.\w+\/\w+)/","<a href='$1' target='_blank'>$1</a> ",$user2_recent[0][$p]);
				}
				if ($convert_users == 'yes') {
				if ($convert_links == 'no') { $user2_full_tweet = $user2_recent[0][$p]; }
				$user2_full_tweet = preg_replace("/@(\w+)/","<a href='http://twitter.com/$1' target='_blank'>@$1</a> ",$user2_full_tweet);
				}
				if ($convert_hashs == 'yes') {
				if ($convert_links == 'no') { $user2_full_tweet = $user2_recent[0][$p]; }
				$user2_full_tweet = preg_replace("/#(\w+)/","<a href='http://search.twitter.com/search?q=%23$1' target='_blank'>#$1</a> ",$user2_full_tweet);
				}
				if ($convert_links != 'yes' && $convert_users != 'yes' && $convert_hashs != 'yes') {
					$user2_tweets .= '<li>'.$user2_recent[0][$p].'</li>';
				} else {
				$user2_tweets .= '<li>'.$user2_full_tweet.'</li>';
				}
				$p++;
			}
			$user2_tweets .= '</ul>';
		}
		//Results
		echo $before_widget;
		if ($title) { echo $before_title.$title.$after_title; }
		echo $user1_tweets;
		echo $user2_tweets;
        echo $after_widget;
	}

} // class MPTweetList

//Add the Widget to Widget Menu
add_action('widgets_init', create_function('', 'return register_widget("MPTweetList");'));
?>