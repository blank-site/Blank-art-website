<?php
/**
 * The default template for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
			<?php if ( is_sticky() ) : ?>
				<hgroup>
					<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'blank' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<h3 class="entry-format"><?php _e( 'Featured', 'blank' ); ?></h3>
				</hgroup>
			<?php else : ?>
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'blank' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			<?php endif; ?>

			

			<?php if ( comments_open() && ! post_password_required() ) : ?>
			<div class="comments-link">
				<?php // comments_popup_link( '<span class="leave-reply">' . __( 'Reply', 'blank' ) . '</span>', _x( '1', 'comments number', 'blank' ), _x( '%', 'comments number', 'blank' ) ); ?>
			</div>
			<?php endif; ?>
		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'blank' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'blank' ) . '</span>', 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<footer class="entry-meta">
       		 <ul>
			<?php $show_sep = false; ?>
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
             <li class="autor">
            <?php blank_author(); ?>
            	
            </li>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'blank' ) );
				if ( $categories_list && false):
			?>
            
            
           
			<li class="cat-links">
				<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'blank' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
				$show_sep = true; 
				?>
			</li>
			<?php endif; // End if categories ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ' / ', 'blank' ) );
				if ( $tags_list ):
				if ( $show_sep ) : ?>
			
				<?php endif; // End if $show_sep ?>
			<li class="tag-links">
				<?php printf( __( '<span class="%1$s">Tags</span> %2$s', 'blank' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
				$show_sep = true; ?>
			</li>
            </ul>
			<?php endif; // End if $tags_list ?>
			<?php endif; // End if 'post' == get_post_type() ?>

			<?php if ( comments_open() && false) : ?>
			<?php if ( $show_sep ) : ?>
			<span class="sep"> | </span>
			<?php endif; // End if $show_sep ?>
			<span class="comments-link"><?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'blank' ) . '</span>', __( '<b>1</b> Reply', 'blank' ), __( '<b>%</b> Replies', 'blank' ) ); ?></span>
			<?php endif; // End if comments_open() ?>

			<?php edit_post_link( __( 'Edit', 'blank' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- #entry-meta -->
	</article><!-- #post-<?php the_ID(); ?> -->



<p>[wpgmappity id="1"]</p>
<?php

if(function_exists('pronamic_google_maps')) {
    pronamic_google_maps(array(
        'width' => 500 ,
        'height' => 500
    ));
}

?>