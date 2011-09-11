<?php 
/*
function colorCloud($text) { 
$text = preg_replace_callback('|<a (.+?)>|i', 'colorCloudCallback', $text); 
return $text; 
} 
function colorCloudCallback($matches) { 
$text = $matches[1]; 
$color = dechex(rand(0x666666,0x666666));//通过这里修改颜色范围 


$pattern = '/style=(\'|\")(.*)(\'|\")/i'; 
$text = preg_replace($pattern, "style=\"color:#{$color};$2; \"", $text); 
return "<a $text>"; 
} 
add_filter('wp_tag_cloud', 'colorCloud', 1);

*/
//init widgets-----------------------------------
function blank_widgets_init() {

	

	register_sidebar( array(
		'name' => __( 'Homepage', 'blank' ),
		'id' => 'sidebar-1',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'About', 'blank' ),
		'id' => 'sidebar-2',
		'description' => __( 'The sidebar for the optional Showcase Template', 'blank' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Work', 'blank' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your work page page', 'blank' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Work-single-post', 'blank' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your work-single-post page', 'blank' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Blog', 'blank' ),
		'id' => 'sidebar-5',
		'description' => __( 'An optional widget area for your Blog', 'blank' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	register_sidebar( array(
		'name' => __( 'Blog-single-post', 'blank' ),
		'id' => 'sidebar-6',
		'description' => __( 'An optional widget area for your blog-single-post page', 'blank' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Contact', 'blank' ),
		'id' => 'sidebar-7',
		'description' => __( 'An optional widget area for your contact page', 'blank' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'blank_widgets_init' );
//init widgets end-----------------------------------

if ( ! function_exists( 'blank_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own twentyeleven_posted_on to override in a child theme
 *
 * @since Twenty Eleven 1.0
 */
function blank_posted_on() {
	printf( __( '<span class="sep">Posted on </span><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="by-author"> <span class="sep"> by </span> <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'blank' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		sprintf( esc_attr__( 'View all posts by %s', 'blank' ), get_the_author() ),
		esc_html( get_the_author() )
	);
}
endif;

/*------------------------------------------*/
if ( ! function_exists( 'blank_author' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 * Create your own twentyeleven_posted_on to override in a child theme
 *
 * @since Twenty Eleven 1.0
 */
 
function blank_author() {
	printf( __( 'By <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span>', 'blank' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		sprintf( esc_attr__( 'View all posts by %s', 'blank' ), get_the_author() ),
		esc_html( get_the_author() )
	);
}
endif;


//register all widgets code below:
/*********************************************/
/**
 * Blank search bar Class
 */
class mb_blank_search_bar extends WP_Widget {
    /** 构造函数 */
    function mb_blank_search_bar() {
        parent::WP_Widget(false, $name = 'Blank search bar');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
         include(TEMPLATEPATH . '/sidebar-search.php');
        
    }

} // class end

add_action('widgets_init', create_function('', 'return register_widget("mb_blank_search_bar");'));
/***********************************************/


/*********************************************/
/**
 * Class start
 */
class mb_blank_social_bar extends WP_Widget {
    /** 构造函数 */
    function mb_blank_social_bar() {
        parent::WP_Widget(false, $name = 'Blank social bar');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
         include(TEMPLATEPATH . '/sidebar-social.php');
        
    }

} // class end

add_action('widgets_init', create_function('', 'return register_widget("mb_blank_social_bar");'));
/***********************************************/


/*********************************************/
/**
 * Class start
 */
class mb_blank_tags_cloud extends WP_Widget {
    /** 构造函数 */
    function mb_blank_tags_cloud() {
        parent::WP_Widget(false, $name = 'Blank tags cloud');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
         include(TEMPLATEPATH . '/sidebar-tags.php');
        
    }

} // class end

add_action('widgets_init', create_function('', 'return register_widget("mb_blank_tags_cloud");'));
/***********************************************/

/*********************************************/
/**
 * Class start
 */
class mb_blank_google_title extends WP_Widget {
    /** 构造函数 */
    function mb_blank_google_title() {
        parent::WP_Widget(false, $name = 'Blank google title');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
         include(TEMPLATEPATH . '/sidebar-google-title.php');
        
    }

} // class end

add_action('widgets_init', create_function('', 'return register_widget("mb_blank_google_title");'));
/***********************************************/

/*********************************************/
/**
 * Class start
 */
class mb_blank_email_bar extends WP_Widget {
    /** 构造函数 */
    function mb_blank_email_bar() {
        parent::WP_Widget(false, $name = 'Blank email bar');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
         include(TEMPLATEPATH . '/sidebar-email.php');
        
    }

} // class end

add_action('widgets_init', create_function('', 'return register_widget("mb_blank_email_bar");'));
/***********************************************/



/*********************************************/
/**
 * Class start
 */
class mb_blank_archives_bar extends WP_Widget {
    /** 构造函数 */
    function mb_blank_archives_bar() {
        parent::WP_Widget(false, $name = 'Blank archives bar');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
         include(TEMPLATEPATH . '/sidebar-archives.php');
        
    }

} // class end

add_action('widgets_init', create_function('', 'return register_widget("mb_blank_archives_bar");'));
/***********************************************/


/*********************************************/
/**
 * Class start
 */
class mb_blank_address_bar extends WP_Widget {
    /** 构造函数 */
    function mb_blank_address_bar() {
        parent::WP_Widget(false, $name = 'Blank address bar');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
         include(TEMPLATEPATH . '/sidebar-address.php');
        
    }

} // class end

add_action('widgets_init', create_function('', 'return register_widget("mb_blank_address_bar");'));
/***********************************************/


/*********************************************/
/**
 * Class start
 */
class mb_blank_sidebar_spacing extends WP_Widget {
    /** 构造函数 */
    function mb_blank_sidebar_spacing() {
        parent::WP_Widget(false, $name = 'Blank spacing bar');	
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {		
         include(TEMPLATEPATH . '/sidebar-spacing.php');
        
    }

} // class end

add_action('widgets_init', create_function('', 'return register_widget("mb_blank_sidebar_spacing");'));
/***********************************************/
//register all widgets code end:

?>