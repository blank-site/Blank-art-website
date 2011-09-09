<?php
/*
	Plugin Name: Custom Post Type Shortcode
	Plugin URI: http://blog.blackbirdi.com/
	Description: List custom post type posts using a shortcode on any page.
	Version: 1.3.6
	Author: Blackbird Interactive
	Author URI: http://blackbirdi.com
	License: GPL2



    Copyright 2010 Blackbird Interactive (email : cpt_shortcode@blackbirdi.com)

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

// CALL FOR STYLE SHEET ONLY IN THE ADMIN
function cpt_list_style(){
?>
<link href="<?php print bloginfo('wpurl'). "/wp-content/plugins/custom-post-type-list-shortcode/assets/css/styles.css";?>" rel="stylesheet" type="text/css">
<?php 
}
add_action('admin_head', 'cpt_list_style');



# Compatibilty checks
# -----------------------------------------------------

// get wordpress version number and fill it up to 9 digits
$int_wp_version = preg_replace('#[^0-9]#', '', get_bloginfo('version'));
while(strlen($int_wp_version) < 9){
	
	$int_wp_version .= '0'; 
}

// get php version number and fill it up to 9 digits
$int_php_version = preg_replace('#[^0-9]#', '', phpversion());
while(strlen($int_php_version) < 9){
	
	$int_php_version .= '0'; 
}

// Check overall plugin compatibility
if(	$int_wp_version >= 300000000 && 		// Wordpress version > 2.7
	$int_php_version >= 520000000 && 		// PHP version > 5.2
	defined('ABSPATH') && 					// Plugin is not loaded directly
	defined('WPINC')) {						// Plugin is not loaded directly
		
	// Load plugin class file
	require_once(dirname(__FILE__).'/assets/lib/class.main.php');
	
	// Intialize the plugin class by calling initialize on our class
	add_action('init',array('cpt_shortcode','initialize'));
	
}

// Plugin is not compatible with current configuration
else {
	
	// Display incompatibility information
	add_action('admin_notices', 'cpt_shortcode_incompatibility_notification');
}

// Display incompatibility notification
function cpt_shortcode_incompatibility_notification(){
	
	echo '<div id="message" class="error">
	
	<p><b>The &quot;Custom Post Type List&quot; Plugin does not work on this WordPress installation!</b></p>
	<p>Please check your WordPress installation for following minimum requirements:</p>
	
	<p>
	- WordPress version 3.0 or higer<br />
	- PHP version 5.2 or higher<br />
	</p>
	
	<p>Do you need help? Contact <a href="mailto:justin@blackbirdi.com">Blackbird Interactive</a></p>
	
	</div>';
}

// ADD PLUGIN TO THE ADMIN PANEL
function add_cp_menu(){
//create custom post type menu
	add_menu_page('CP List', 'CP LIST', 'administrator', 'cp_list_main_menu', 'cp_list_settings');

	//create submenu items
	add_submenu_page('cp_list_main_menu', 'Documentaion','Documentation', 'administrator', 'cp_list_sub_doc', 'cp_list_doc');
}

add_action('admin_menu','add_cp_menu');

// HOME/WELCOME PAGE
function cp_list_settings(){?>
	   

<div id="cpl-wrapper">
<div id="cpl-logo"><img src="http://www.blackbirdi.com/images/bb-sig-bird.gif" /> WordPress Custom Post Type List Shortcode</div>
<div id="cpl-content">
<?php
// GET FEED FROM BLACKBIRDI.COM/CPLISTFEED.HTML
print_r (file_get_contents('http://www.blackbirdi.com/cplistfeed.html'));
 ?> 
</div>
</div><!-- Wrapper -->
   <?php }
	
// Documentation page		
function cp_list_doc(){?>
<div id="cpl-wrapper">
   <div id="cpl-header">
   <p>Custom Post Type List Documentation</p>
   </div>
<div id="cpl-menu">
   <ul>
     <li><a href="#plugin_description">Plugin Description</a></li>
     <li><a href="#tag_description">Tag Description</a></li>
     <li><a href="#faq">FAQ's</a></li>
   </ul>
</div>
<p>&nbsp;</p>
   <p>&nbsp;</p>
   <div id="cpl-description">
   <h2><a name="plugin_description" id="plugin_description"></a> Description</h2>
<em><strong>A shortcode with which you can easily list all of the posts within a post-type and sort by regular or custom fields.   </strong></em>
<p>When used with Custom Post Type UI plug-in (http://wordpress.org/extend/plugins/custom-post-type-ui/) and Advanced Custom Fields plug-in (http://wordpress.org/extend/plugins/advanced-custom-fields/), you can easily list all of the posts within a post-type and sort by regular or custom fields.</p>

   <p><em><strong>The shortcode with all attributes set to default values is as follows:</strong></em></p>
   <p>&nbsp;</p>
   <p>Copy and Paste this code into your editor:<br />
     <textarea name="shortcode" id="cpl-shortcode-text" cols="150" rows="5">[cpt_list post_type="post_type_name" posts_per_page="100" category="" use_single_blocks="false" title_links="false" link_to_file="false" attachment_to_use="0" show_thumbs="true" show_post_content="true" read_more_link="false" list_title="false" file_extension="true" thumb_link="false" thumb_height="false" thumb_width="false" order_posts_by="false" which_order="ASC" wrap_with="false" show_date="false" images_only="false" images_only_num="1" excerpt_only="false"]</textarea>
   </p>
   <p>As you can tell, there is a slew of options for this shortcode, the reason for this is for maximum extensibility; we hope that you can create any style of post listing that you desire, just from this shortcode.<br />
     All options explained below:</p>
     <table id="cpl-table" width="1247" border="10" cellpadding="10" cellspacing="10">
     <tr>
       <th width="208"><a name="tag_description" id="tag_description"></a>Tag</th>
       <th width="1027">Description of Tag</th>
     </tr>
     <tr>
       <td><strong>post_type</strong></td>
       <td>This is the post type name, if no posts from this type are returned, an error will display.</td>
     </tr>
     <tr>
       <td><strong>`posts_per_page=&quot;100&quot;`</strong></td>
       <td>This is how many posts to display in a single listing, pagination is planned and will be implemented in a future revision.</td>
     </tr>
     <tr>
       <td height="21"><strong>`category=&quot;&quot;`</strong></td>
       <td>This is a category filter, use your category slug to only return posts of the post type that are within this category.</td>
     </tr>
     <tr>
       <td><strong>`use_single_blocks=&quot;false&quot;`</strong></td>
       <td>This option is to switch the post type list from its standard definition list with definition titles and definition definitions to an unordered list with list items.</td>
     </tr>
     <tr>
       <td><strong>`title_links=&quot;false&quot;`</strong></td>
       <td>This option causes the title to be a link.<br />
Possible values are:<br />
- `false` causes the title to not be a link.<br />
- `true` causes the link's href to be the posts permalink unless a link_to_file value is provided.<br />
- Any other value uses this value as the href of the title link, this is for linking the posts to some custom url.</td>
     </tr>
     <tr>
       <td><strong>`link_to_file=&quot;false&quot;`</strong></td>
       <td>This option is to set the post type link's href to one of its attachment's urls. Possible values are:<br />
- `false` causes the post to not link to any file.<br />
- `true` causes the post to link to an attached file<br />
- Any other value cuases the post to link to the given value.</td>
     </tr>
     <tr>
       <td><strong>`attachment_to_use=&quot;0&quot;`</strong></td>
       <td>This option is to define which attachment to use for `link_to_file`. It defaults to the first file attachment.</td>
     </tr>
     <tr>
       <td><strong>`show_thumbs=&quot;true&quot;`</strong></td>
       <td>This option allows you to either display the thumbnails (if they exist), or hide them.</td>
     </tr>
     <tr>
       <td><strong>`show_post_content=&quot;true&quot;`</strong></td>
       <td>This option allows you to change what content is displayed, possible values are:<br />
- `true` causes the content area to be the post's content.<br />
- `false` causes the content area to be empty.<br />
- Any other value uses this value as the post's content display.</td>
     </tr>
     <tr>
       <td><strong>`read_more_link=&quot;false&quot;`</strong></td>
       <td>This option allows you to show a read more link, possible values are:<br />
- `false` - Will not display any read more link.<br />
- `true` - Will cause a generic &quot;Read More…&quot; link to appear after your content and will link to the permalink (or title link value or link to file value).<br />
- Any other value will cause a custom text link to appear after your content and will link to the permalink (or title link value or link to file value).</td>
     </tr>
     <tr>
       <td><strong>`list_title=&quot;false&quot;`</strong></td>
       <td>This option allows you to display a heading with the list's name. Possible values are:<br />
- `false` causes no title to be displayed.<br />
- `true` causes the Custom Post Type's name to be displayed.<br />
- Any other value uses this value as the list's title.</td>
     </tr>
     <tr>
       <td><strong>`file_extension=&quot;true&quot;`</strong></td>
       <td>This option is only used if `link_to_file` is set, this option is used to give the link a class representing the file type that it is referencing for easy css styling. Possible values are:<br />
- `true` attempts to use the file extension from the file link referenced.<br />
- `false` does not add a class to the link.<br />
- Any other value adds a class of this value (Note: these values are printed as-is into the class attribute of the anchor tag, please insure no special characters or spaces are used).</td>
     </tr>
     <tr>
       <td><p><strong>`thumb_link=&quot;false&quot;`</strong></p></td>
       <td>This option is to allow the thumbnail image to be linked somewhere, possible values are:<br />
- `false` thumbnails will not be linked anywhere.<br />
- `true` the image will be linked to the same location that the title is linked to if specified, otherwise it will link to the post's permalink<br />
- `post_index` the image will be linked to `#n` where n is a 0 indexed count, (useful for custom jQuery image galleries).<br />
- `id` the image will be linked to the image's attachment ID<br />
- `src` the image will be linked to it's source.<br />
- Any other value the thumbnail will be linked to this value (Note: this value is printed as-is into the href attribute of the anchor tag, please insure no special characters or spaces are used without being properly escaped).</td>
     </tr>
     <tr>
       <td><p><strong>`thumb_height=&quot;false&quot;`</strong></p></td>
       <td><p>       This attribute, if not `false`, will be used as the height attribute of the thumbnail image if being displayed. Must either be `false` or a positive integer.</p></td>
     </tr>
     <tr>
       <td><strong>`thumb_width=&quot;false&quot;`</strong></td>
       <td>This attribute, if not `false`, will be used as the width attribute of the thumbnail image if being displayed. Must either be `false` or a positive integer.</td>
     </tr>
     <tr>
       <td><strong>`order_posts_by=&quot;false&quot;`</strong></td>
       <td><p>This option is used as the orderby argument in the WP query. If `false`, it will order by post `ID` `DESC` by default.</p></td>
     </tr>
     <tr>
       <td><strong>`which_order=&quot;ASC&quot;`</strong></td>
       <td>This option is used as the order argument in the WP query.  Only used if `order_posts_by`is set.</td>
     </tr>
     <tr>
       <td><strong>`wrap_with=&quot;false&quot;`</strong></td>
       <td>This option is used to wrap each list item with an html tag. This option is only used if using the use_single_blocks option is not `false`. (Note: this value is printed as-is into an html tag, please only use a valid, block-level XHTML tag as a value). Example: `wrap_with=&quot;div class='wrapper_class'&quot;`.</td>
     </tr>
     <tr>
       <td><strong>'show_date=&quot;false&quot;'</strong></td>
       <td>This option is used to display the date in each listing</td>
     </tr>
     <tr>
       <td><strong>'images_only=&quot;false&quot;'</strong></td>
       <td>**This option is in testing** This option if set to &quot;true&quot; will overwrite all content and show a set amount of images from each post.</td>
     </tr>
     <tr>
       <td><p><strong>'images_only_num=1'</strong></p></td>
       <td>This option is used hand in hand and tells the 'images_only' tag how many images per post should be shown.</td>
     </tr>
     <tr>
       <td><strong>'excerpt_only=&quot;false&quot;'</strong></td>
       <td>This option allows you to show only the excerpt of the post.</td>
     </tr>
   </table>
  </div>
   <p>&nbsp;</p>
   
   <p>&nbsp;</p>
   <p>&nbsp;</p>
<h2><a name="faq" id="faq"></a>Frequently Asked Questions </h2>
   <p><strong>Do I have to use Custom Post-types?</strong></p>
   <p>You do not have to use custom post-types, Custom Post Type List Shortcode pulls `posts` by default, but the real power of this plugin lies in the use of custom post-types. </p>
   <p><strong>Is there paging?</strong></p>
   <p>Paging is not supported in this version, though it is planned for a future release.</p>
   <p><strong>What can I do with this plugin?</strong></p>
   <p>You can easily create a list of any specified custom post-type in any content area that allows shortcode to be executed. Some examples of use are<br />
   Custom image gallery managed via `gallery` custom post-type.</p>
   <ol>
     <li> Restaurant menu page, menu items managed via `menu` custom post-type</li>
     <li> Show schedule listing, where shows are managed via `shows` custom post-type.</li>
     <li> Employee directory with names/photos/info, managed via `employees` custom post-type.</li><br />
       <br />
       <br />
</ol>
</div>
<?php }

		
		
		
 
		
/*
function bot_install()
{
    global $wpdb;
    $table = $wpdb->prefix."bot_counter";
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
        bot_name VARCHAR(80) NOT NULL,
        bot_mark VARCHAR(20) NOT NULL,
        bot_visits INT(9) DEFAULT 0,
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structure);
 
    // Populate table
    $wpdb->query("INSERT INTO $table(bot_name, bot_mark)
        VALUES('Google Bot', 'googlebot')");
    $wpdb->query("INSERT INTO $table(bot_name, bot_mark)
        VALUES('Yahoo Slurp', 'yahoo')");
}
*/
?>