=== Loop Post Navigation Links ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: posts, navigation, links, next, previous, portfolio, previous_post_link, next_post_link, coffee2code
Requires at least: 2.6
Tested up to: 3.2.1
Stable tag: 1.6.1
Version: 1.6.1

Template tags (for use in single.php) to create post navigation loop (previous to first post is last post; next/after last post is first post).


== Description ==

Template tags (for use in single.php) to create post navigation loop (previous to first post is last post; next/after last post is first post).

The function `next_or_loop_post_link()` is identical to WordPress's `next_post_link()` in every way except when called on the last post in the navigation sequence, in which case it links back to the first post in the navigation sequence.

The function `previous_or_loop_post_link()` is identical to WordPress's `previous_post_link()` in every way except when called on the first post in the navigation sequence, in which case it links back to the last post in the navigation sequence.

Useful for providing a looping link of posts, such as for a portfolio, or to continually present pertinent posts for visitors to continue reading.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/loop-post-navigation-links/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Unzip `loop-post-navigation-links.zip` inside the `/wp-content/plugins/` directory (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Use `next_or_loop_post_link()` template tag instead of `next_post_link()`, and/or `previous_or_loop_post_link()` template tag instead of `previous_post_link()`, in your single-post template (single.php).


== Template Tags ==

The plugin provides two template tags for use in your single-post theme templates.

= Functions =

* `function next_or_loop_post_link( $format='%link &raquo;', $link='%title', $in_same_cat = false, $excluded_categories = '' )`
Like WordPress's `next_post_link()`, this function displays a link to the next chronological post (among all published posts, those in the same category, or those not in certain categories).  Unlink `next_post_link()`, when on the last post in the sequence this function will link back to the first post in the sequence, creating a circular loop.

* `function previous_or_loop_post_link( $format='&laquo; %link', $link='%title', $in_same_cat = false, $excluded_categories = '' )`
Like WordPress's `previous_post_link()`, this function displays a link to the previous chronological post (among all published posts, those in the same category, or those not in certain categories).  Unlink `previous_post_link()`, when on the first post in the sequence this function will link to the last post in the sequence, creating a circular loop.

= Arguments =

* `$format`
(optional) A percent-substitution string indicating the format of the entire output string.  Use <code>%link</code> to represent the next/previous post being linked, or <code>%title</code> to represent the title of the next/previous post.

* `$link`
(optional) A percent-substitution string indicating the format of the link itself that gets created for the next/previous post.  Use <code>%link</code> to represent the next/previous post being linked, or <code>%title</code> to represent the title of the next/previous post.

* `$in_same_cat`
(optional) A boolean value (either true or false) indicating if the next/previous post should be in the current post's same category.

* `$excluded_categories`
(optional) A string of category IDs to which posts cannot belong.  Due to goofy a WP convention, the category IDs need to be joined by " and ", i.e. "14 and 15 and 31".


== Examples ==

`<div class="navigation">
	<div class="alignleft"><?php previous_or_loop_post_link(); ?></div>
	<div class="alignright"><?php next_or_loop_post_link(); ?></div>
</div>`


== Changelog ==

= 1.6.1 =
* Note compatibility through WP 3.2+
* Update copyright date (2011)
* Minor code formatting (spacing)
* Add plugin homepage and author links to description in readme.txt

= 1.6 =
* Add rel= attribute to links
* Wrap all functions in if(!function_exists()) check
* Check that GLOBALS['post'] is an object before treating it as such
* Minor code tweaks to mirror more recent changes to adjacent_post_link()
* Note compatibility with WP 3.0+
* Minor code reformatting (spacing)
* Add Upgrade Notice section to readme
* Remove docs from top of plugin file (all that and more are in readme.txt)
* Remove trailing whitespace in header docs

= 1.5.1 =
* Add PHPDoc documentation
* Note compatibility with WP 2.9+
* Update copyright date

= 1.5 =
* Added adjacent_or_loop_post_link() and have next_or_loop_post_link() and previous_or_post_link() simply deferring to it for core operation
* Added support for %date in format string (as per WP)
* Added support for 'previous_post_link' and 'next_post_link' filters (as per WP)
* Added support for 'previous_or_loop_post_link' and 'next_or_loop_post_link' filters
* Removed two previously used global variable flags and replaced with one
* Changed description
* Noted compatibility with WP 2.8+
* Dropped support for pre-WP2.6
* Updated copyright date

= 1.0 =
* Initial release


== Upgrade Notice ==

= 1.6.1 =
Trivial update: noted compatibility through WP 3.2+ and updated copyright date

= 1.6 =
Minor update. Highlights: adds 'rel=' attribute to links; minor tweaks; verified WP 3.0 compatibility.