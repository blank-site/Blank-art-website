<?php
/**
 * @package Loop_Post_Navigation_Links
 * @author Scott Reilly
 * @version 1.6.1
 */
/*
Plugin Name: Loop Post Navigation Links
Version: 1.6.1
Plugin URI: http://coffee2code.com/wp-plugins/loop-post-navigation-links/
Author: Scott Reilly
Author URI: http://coffee2code.com
Description: Template tags (for use in single.php) to create post navigation loop (previous to first post is last post; next/after last post is first post).

Compatible with WordPress 2.6+, 2.7+, 2.8+, 2.9+, 3.0+, 3.1+, 3.2+.

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/loop-post-navigation-links/

*/

/*
Copyright (c) 2008-2011 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/
$c2c_loop_navigation_find = false;

if ( ! function_exists( 'next_or_loop_post_link' ) ) :
/**
 * Display next post link that is adjacent to the current post, or if none, then the first post in the series.
 *
 * @param string $format (optional) Link anchor format. Default is '%link &raquo;'.
 * @param string $link (optional) Link permalink format. Default is '%title'.
 * @param bool $in_same_cat (optional) Whether link should be in same category. Default is false.
 * @param string $excluded_categories (optional) Excluded categories IDs. Default is ''.
 * @return void
 */
function next_or_loop_post_link( $format='%link &raquo;', $link='%title', $in_same_cat = false, $excluded_categories = '' ) {
	adjacent_or_loop_post_link( $format, $link, $in_same_cat, $excluded_categories, false );
}
endif;

if ( ! function_exists( 'previous_or_loop_post_link' ) ) :
/**
 * Display previous post link that is adjacent to the current post, or if none, then the last post in the series.
 *
 * @param string $format (optional) Link anchor format. Default is '&laquo; %link'.
 * @param string $link (optional) Link permalink format. Default is '%title'.
 * @param bool $in_same_cat (optional) Whether link should be in same category. Default is false.
 * @param string $excluded_categories (optional) Excluded categories IDs. Default is ''.
 * @return void
 */
function previous_or_loop_post_link( $format='&laquo; %link', $link='%title', $in_same_cat = false, $excluded_categories = '' ) {
	adjacent_or_loop_post_link( $format, $link, $in_same_cat, $excluded_categories, true );
}
endif;

if ( ! function_exists( 'adjacent_or_loop_post_link' ) ) :
/**
 * Display adjacent post link or the post link for the post at the opposite end of the series.
 *
 * Can be either next post link or previous.
 *
 * @param string $format Link anchor format.
 * @param string $link Link permalink format.
 * @param bool $in_same_cat (optional) Whether link should be in same category. Default is false.
 * @param string $excluded_categories (optional) Excluded categories IDs. Default is ''.
 * @param bool $previous (optional) Whether to display link to previous post. Default is true.
 * @return void
 */
function adjacent_or_loop_post_link( $format, $link, $in_same_cat = false, $excluded_categories = '', $previous = true ) {
	if ( $previous && is_attachment() && is_object( $GLOBALS['post'] ) )
		$post = & get_post($GLOBALS['post']->post_parent);
	else
		$post = get_adjacent_post($in_same_cat, $excluded_categories, $previous);

	// START The only modification of adjacent_post_link() -- get the last/first post if there isn't a legitimate previous/next post
	if ( ! $post ) {
		global $c2c_loop_navigation_find;
		$c2c_loop_navigation_find = true;
		$post = get_adjacent_post( $in_same_cat, $excluded_categories, $previous );
		$c2c_loop_navigation_find = false;
	}
	// END modification

	if ( !$post )
		return;

	$title = $post->post_title;

	if ( empty($post->post_title) )
		$title = $previous ? __('Previous Post') : __('Next Post');

	$title = apply_filters('the_title', $title, $post->ID);
	$date = mysql2date(get_option('date_format'), $post->post_date);
	$rel = $previous ? 'prev' : 'next';

	$string = '<a href="'.get_permalink($post).'" rel="'.$rel.'">';
	$link = str_replace('%title', $title, $link);
	$link = str_replace('%date', $date, $link);
	$link = $string . $link . '</a>';

	$format = str_replace('%link', $link, $format);

	$adjacent = $previous ? 'previous' : 'next';
	echo apply_filters( "{$adjacent}_or_loop_post_link", apply_filters( "{$adjacent}_post_link", $format, $link ), $format, $link );
}
endif;

if ( ! function_exists( 'c2c_modify_nextprevious_post_where' ) ) :
/**
 * Modifies the SQL WHERE clause used by WordPress when getting a previous/next post to accommodate looping navigation.
 *
 * Can be either next post link or previous.
 *
 * @param string $where SQL WHERE clause generated by WordPress
 * @param string $link Link permalink format.
 * @param bool $in_same_cat (optional) Whether link should be in same category. Default is false.
 * @param string $excluded_categories (optional) Excluded categories IDs. Default is ''.
 * @param bool $previous (optional) Whether to display link to previous post. Default is true.
 * @return void
 */
function c2c_modify_nextprevious_post_where( $where ) {
	// The incoming WHERE statement generated by WordPress is a condition for the date, relative to the current
	//	post's date.  To find the post we want, we just need to get rid of that condition (which is the first) and retain the others.
	if ( $GLOBALS['c2c_loop_navigation_find'] )
		$where = preg_replace( '/WHERE (.+) AND/imsU', 'WHERE', $where );
	return $where;
}
endif;

/*
 * Register actions to filter WHERE clause when previous or next post query is being processed.
 */
add_filter( 'get_next_post_where',     'c2c_modify_nextprevious_post_where' );
add_filter( 'get_previous_post_where', 'c2c_modify_nextprevious_post_where' );

?>