<?php
/**
 * Template Name: postlist Template
 * Description: A Page Template that showcases Sticky Posts, Asides, and Blog Posts
 *
 * The showcase template in Twenty Eleven consists of a featured posts section using sticky posts,
 * another recent posts area (with the latest post shown in full and the rest as a list)
 * and a left sidebar holding aside posts.
 *
 * We are creating two queries to fetch the proper posts and a custom widget for the sidebar.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */

// Enqueue showcase script for the slider

get_header(); ?>
<?php get_template_part('top','location')?>
<div class='left-sidebar '>
<?php get_sidebar('social'); ?>
<div class="side-spacing"></div>
<?php get_sidebar('tags'); ?>
<div class="side-spacing"></div>
<?php get_sidebar('archives'); ?>
</div>

<div id="primary" class="showcase">
<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
	<?php require('content.php'); ?>
<?php endwhile;endif ;?>
    
</div><!-- #primary -->
<div class='clear'></div>
<?php get_footer(); ?>
