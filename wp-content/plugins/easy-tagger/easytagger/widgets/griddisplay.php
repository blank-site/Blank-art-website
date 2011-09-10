<?php


class DisplayFeaturedGrid extends WP_Widget {
	function __construct(){
            parent::__construct('taggrid','ET Featured Images Grid');
            wp_register_script('displaygrid',  ET_URL . '/inc/js/etImageGrid.js', array('jquery','wp-ajax-response'));
            wp_enqueue_script('displaygrid');
            wp_enqueue_script('jquery.lazyload',ET_URL . 'inc/js/jquery.lazyload.js');
            wp_enqueue_style('gridstyle', ET_URL . 'css/grid-style.css');
 
            add_action('wp_ajax_fetch_thumbs','fetch_featured_thumbnails');
            add_action('wp_ajax_nopriv_fetch_thumbs','fetch_featured_thumbnails');

        }

	function form($instance) {
            
        
	}
 
	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function widget($args=array(), $instance=null) {
            if($args)
                extract($args,EXTR_SKIP);
            echo $before_widget;
            
            
        
            
            ?>
            <div id="gridWrapper">
                 <input id="gridfiltertag" type="hidden" value="<?php echo $_REQUEST['tag']; ?>"></input>
                <input id="gridimagewidth" type="hidden" value="<?php echo get_option('et_grid_image_width',150); ?>"></input>
                <input id="gridimageheight" type="hidden" value="<?php echo get_option('et_grid_image_height',150); ?>"></input>
                 <input id="gridrows" type="hidden" value="<?php echo get_option('et_grid_rows',4); ?>"></input>
                 <input id="gridcolumns" type="hidden" value="<?php echo get_option('et_grid_columns',4); ?>"></input>
                <input id="gridadminurl" type="hidden" value="<?php echo admin_url('admin-ajax.php'); ?>" ></input>
                <input id="rightarrow" type="hidden" value="<?php echo ET_URL . 'resources/rightarrow.png' ?>" ></input>
                <input id="leftarrow" type="hidden" value="<?php echo ET_URL .'resources/leftarrow.png'; ?>" ></input>
                <div id="leftControl"></div>
                <div id="gridDisplay">
                    <div id="loadingWrapper">
                         <div id="loadingOverlay"></div>
                         <div id="loadingContent">
                             <img src="<?php echo ET_URL . 'resources/ajax-loader.gif' ?>" />
                            
                         </div>
                    </div>
                </div>
                <div id="rightControl"></div>
                <div id="infoArea">
                    <p>Hello World</p>
                </div>
            </div>
            <?php
            echo $after_widget;
	}   
        
}

add_action(
        'widgets_init',
        create_function('','register_widget("DisplayFeaturedGrid");')
);

$GLOBALS['et_grid'] = new DisplayFeaturedGrid();

/* featured images grid shortcode */
function grid_shortcode($atts){
    global $et_grid;
    
    $atts = shortcode_atts( array(
		'default' =>'1'
	), $atts );
    
    return $et_grid->widget($atts);
}
add_shortcode('etgrid', 'grid_shortcode');

/* featured images grid template tag */
function et_images_grid($args = array()){
    global $et_grid;
    
    echo $et_grid->widget($args);
}



?>