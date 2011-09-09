=== Custom Post Type List Shortcode ===
Contributors: Blackbird Interactive
Donate link: http://blog.blackbirdi.com/donate/
Tags: custom post-type list, custom post-type, post list, shortcode, cpt, custom field,
Requires at least: 3.1
Tested up to: 3.2.1
Stable tag: 1.3.6

A shortcode with which you can easily list all of the posts within a post-type and sort by regular or custom fields.

== Description ==

When used with Custom Post Type UI plug-in (http://wordpress.org/extend/plugins/custom-post-type-ui/) and Advanced Custom Fields plug-in (http://wordpress.org/extend/plugins/advanced-custom-fields/), you can easily list all of the posts within a post-type and sort by regular or custom fields.

The shortcode with all attributes set to default values is as follows:
`[cpt_list post_type="post_type_name" posts_per_page="100" category="" use_single_blocks="false" title_links="false" link_to_file="false" attachment_to_use="0" show_thumbs="true" show_post_content="true" read_more_link="false" list_title="false" file_extension="true" thumb_link="false" thumb_height="false" thumb_width="false" order_posts_by="false" which_order="ASC" wrap_with="false"]`

As you can tell, there is a slew of options for this shortcode, the reason for this is for maximum extensibility; we hope that you can create any style of post listing that you desire, just from this shortcode.
All options explained below:
`post_type="post_type_name"`
This is the post type name, if no posts from this type are returned, an error will display.

`posts_per_page="100"`
This is how many posts to display in a single listing, pagination is planned and will be implemented in a future revision.

`category=""`
This is a category filter, use your category slug to only return posts of the post type that are within this category.

`use_single_blocks="false"`
This option is to switch the post type list from its standard definition list with definition titles and definition definitions to an unordered list with list items.

`title_links="false"`
This option causes the title to be a link.
Possible values are:
- `false` causes the title to not be a link.
- `true` causes the link's href to be the posts permalink unless a link_to_file value is provided.
- Any other value uses this value as the href of the title link, this is for linking the posts to some custom url.

`link_to_file="false"`
This option is to set the post type link's href to one of its attachment's urls. Possible values are:
- `false` causes the post to not link to any file.
- `true` causes the post to link to an attached file
- Any other value cuases the post to link to the given value.

`attachment_to_use="0"`
This option is to define which attachment to use for `link_to_file`. It defaults to the first file attachment.

`show_thumbs="true"`
This option allows you to either display the thumbnails (if they exist), or hide them.

`show_post_content="true"`
This option allows you to change what content is displayed, possible values are:
- `true` causes the content area to be the post's content.
- `false` causes the content area to be empty.
- Any other value uses this value as the post's content display.

`read_more_link="false"`
This option allows you to show a read more link, possible values are:
- `false` - Will not display any read more link.
- `true` - Will cause a generic "Read More…" link to appear after your content and will link to the permalink (or title link value or link to file value).
- Any other value will cause a custom text link to appear after your content and will link to the permalink (or title link value or link to file value).

`list_title="false"`
This option allows you to display a heading with the list's name. Possible values are:
- `false` causes no title to be displayed.
- `true` causes the Custom Post Type's name to be displayed.
- Any other value uses this value as the list's title.

`file_extension="true"`
This option is only used if `link_to_file` is set, this option is used to give the link a class representing the file type that it is referencing for easy css styling. Possible values are:
- `true` attempts to use the file extension from the file link referenced.
- `false` does not add a class to the link.
- Any other value adds a class of this value (Note: these values are printed as-is into the class attribute of the anchor tag, please insure no special characters or spaces are used).

`thumb_link="false"`
This option is to allow the thumbnail image to be linked somewhere, possible values are:
- `false` thumbnails will not be linked anywhere.
- `true` the image will be linked to the same location that the title is linked to if specified, otherwise it will link to the post's permalink
- `post_index` the image will be linked to `#n` where n is a 0 indexed count, (useful for custom jQuery image galleries).
- `id` the image will be linked to the image's attachment ID
- `src` the image will be linked to it's source.
- Any other value the thumbnail will be linked to this value (Note: this value is printed as-is into the href attribute of the anchor tag, please insure no special characters or spaces are used without being properly escaped).

`thumb_height="false"`
This attribute, if not `false`, will be used as the height attribute of the thumbnail image if being displayed. Must either be `false` or a positive integer.

`thumb_width="false"`
This attribute, if not `false`, will be used as the width attribute of the thumbnail image if being displayed. Must either be `false` or a positive integer.

`order_posts_by="false"`
This option is used as the orderby argument in the WP query. If `false`, it will order by post `ID` `DESC` by default.

`which_order="ASC"`
This option is used as the order argument in the WP query.  Only used if `order_posts_by`is set.

`wrap_with="false"`
This option is used to wrap each list item with an html tag. This option is only used if using the use_single_blocks option is not `false`. (Note: this value is printed as-is into an html tag, please only use a valid, block-level XHTML tag as a value). Example: `wrap_with="div class='wrapper_class'"`.

== Installation ==

1. Upload `cpt_shortcode` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Place shortcode `[cpt_list]` (see description for options) in any content area/page that allows shortcode.

== Frequently Asked Questions ==

= Do I have to use Custom Post-types? =

You do not have to use custom post-types, Custom Post Type List Shortcode pulls `posts` by default, but the real power of this plugin lies in the use of custom post-types. 

= Is there paging? =

Paging is not supported in this version, though it is planned for a future release.

= What can I do with this plugin =

You can easily create a list of any specified custom post-type in any content area that allows shortcode to be executed. Some examples of use are:
1. Custom image gallery managed via `gallery` custom post-type.
2. Resturant menu page, menu items managed via `menu` custom post-type
3. Show schedule listing, where shows are managed via `shows` custom post-type.
4. Employee directory with names/photos/info, managed via `employees` custom post-type.

== Screenshots ==

1. Use of the shortcode with some options in a page content area
2. Custom post-type driven gallery after some jQuery and CSS.

== Changelog ==

= 1.3.5 =
* Added menu in the admin panel contianing documentation and information.
* Added new Arguments: show_date, images_only, imgaes_only_num, excerpt_only
* Added Donation button
* Fixed category bug

= 1.3.4 =
* Fixed boolean checks that were bugs when users input "false" for an option
* Major bug-fixes

= 1.3.3 =
* Added a `div` with class of `cpt_item_entry_content` around the entry content of each cpt item

= 1.3.2 =
* Added new Argument: `wrap_with`
* Minor bug-fixes

= 1.3.1 =
* Added new Arguments: `thumb_link`, `thumb_height`, `thumb_width`
* Minor bug-fixes

= 1.3 =
* Added new Arguments: `category`, `use_single_blocks`, `title_links`, `link_to_file`, `attachment`, `show_thumbs`, `show_post_content`, `read_more_link`, `list_title`, `file_extension`
* Changed Loop Structure
* Added New Classes

= 1.0 =
* Initial build

== Upgrade Notice ==

= 1.3.4 =
Fixed Boolean checks. Highly recomended upgrade, especially if your having problems with some of the shortcode options.

= 1.3.3 =
Unreleased

= 1.3.2 =
Added subtle options and bugfixes. Not necessary, but recomended upgrade.

= 1.3.1 =
More thumbnail controls and bugfixes. Recomended upgrade.

= 1.3 =
Significant structure changes and feature additions. Recomended upgrade.

= 1.0 =
Initial Build. Upgrade to latest.