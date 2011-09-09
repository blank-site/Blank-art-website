<?php
//LATEST UPDATE V. 1.3.6 - 8/24/11 - JUSTIN GREER
//FIXED:: BUG ISSUE WITH TAGS AND STYLESHEET FIX - REPORTED BY BRYCE TAYLOR

//LATEST UPDATE v. 1.3.5 - 8/21/11 - JUSTIN GREER
//ADDED:: Arguments: show_date, only_images

//LATEST UPDATE v. 1.3.4 - 7/19/11 - BRYCE TAYLOR
//ADDED:: Fixes: boolean checks fixed

//LATEST UPDATE v. 1.3.3 - 7/18/11 - BRYCE TAYLOR
//ADDED:: Structure: div with class cpt_item_entry_content around entry content

//LATEST UPDATE: v. 1.3.2 - 6/30/11 - BRYCE TAYLOR
//ADDED:: Arguments: wrap_with;

//LATEST UPDATE: v. 1.3.1 - 6/27/11 - BRYCE TAYLOR
//ADDED:: Arguments: thumb_link, thumb_height, thumb_width;

//LATEST UPDATE: v. 1.3 - 6/24/11 - BRYCE TAYLOR
//ADDED:: Arguments: category, use_single_blocks, title_links, link_to_file, attachment, show_thumbs, show_post_content, read_more_link, list_title, file_extension; New Loop Structure; New Classes;


// SAMPLE FOR MULTIPLE QUERIES
//query_posts(array('post_type' => array('post', 'movies')));



// Main plugin class - be mindful of namespace
class cpt_shortcode {
	
	private $errors = array();
	
	public function initialize() {
		if ( !is_admin() ) {
			add_shortcode( 'cpt_list',  array('cpt_shortcode', 'handle_cpt_list') );
		}
	}

	// RECORD ERROR
	private function error($message) {
		$errors[] = __($message);
	}
	
	// HANDLE LIST SHORTCODE -DEFAULTS
	function handle_cpt_list( $atts ) {
		extract( shortcode_atts( array(
			'post_type' => 'posts',
			'posts_per_page' => 10,
			'category' => '',
			//'taxonomy'=> 'false',
			//'taxonomy_name'=> '',
			'use_single_blocks' => 'false',
			'title_links'=> 'false',
			'link_to_file'=> 'false',
			'attachment_to_use'=>0,
			'show_thumbs' => 'false',
			'show_post_content' => 'true',
			'read_more_link' => 'false',
			'list_title' => 'true',
			'file_extension' => 'true',
			'thumb_link' => 'false',
			'thumb_height' => 'false',
			'thumb_width' => 'false',
			'order_posts_by' => 'false',
			'which_order' => 'ASC',
			'wrap_with'=> 'false',
			'show_date'=> 'false',
			'images_only'=>'false',
			'images_only_num'=>1,
			'excerpt_only'=>'false' //CONTRIBUTION FROM http://craigwaterman.com
		), $atts ) );
		
		if ( post_type_exists( $post_type ) ) {
			$post_type_object = get_post_type_object( $post_type );
			if ( $post_type_object->publicly_queryable ) {
				if($category == ''){
					if($order_posts_by!='false'){
						$cpt_query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => $posts_per_page, 'orderby' => $order_posts_by, 'order' => $which_order ) );
					}else{
						$cpt_query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => $posts_per_page ) );
					}
				}else{
					if($order_posts_by!='false'){
						$cpt_query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => $posts_per_page, 'category_name' =>$category, 'orderby' => $order_posts_by, 'order' => $which_order )  );
					}else{
						$cpt_query = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => $posts_per_page, 'category_name' =>$category ) );
					}
				}
				
				if ( $cpt_query->posts ) {
					$list_title_to_print = ''; //create cpt list title
					if($list_title == 'true'){
						$list_title_to_print = '<h2 class="cpt-list-title">' . ucfirst($post_type_object->name) .'</h2>';
					}else{
						if($list_title != 'false'){
							$list_title_to_print = ucfirst($list_title);
						}
					}
					$pre_list = '<div class="cpt-list-wrapper cpt-list-wrapper-' . $post_type_object->name . '">' . $list_title_to_print . ''; //list wrapper before
					$aft_list = '</div>'; //list wrapper after
					$pre_item = ''; //list item wrapper before
					$btw_item = ''; //list item between title and content
					$aft_item = ''; //list item wrapper after
					if($use_single_blocks == 'false'){
						$pre_list .= '<dl class="cpt-list cpt-list-' . $post_type_object->name . ' cpt-list-cat-' . $category . '">';
						$aft_list = '</dl>' . $aft_list;
					}else{
						$pre_list .= '<ul class="cpt-list cpt-list-' . $post_type_object->name . '">';
						$aft_list = '</ul>' . $aft_list;
					}
					
					$output = $pre_list;
					$first = true;
					$total = count($cpt_query->posts);
					$count = 0;
					
					foreach ( $cpt_query->posts as $post ) :
						$count++; //keep track of current cpt list item
						
						$ext_class = 'cpt_item '; //extend class, used for each cpt list item, starting with a generic cpt list item identifier
						
						if($first){
							$ext_class .= 'first '; //if it's the first post add class of first
							$first = false;	
						}
						if($count == $total){
							$ext_class .= 'last '; //if it's the last post add class of last	
						}						
						$ext_title_class = 'cpt_item_title '; //extend list item title class, starting with a generic cpt list item title identifier
						$pre_title = '<h3 class="' . $ext_title_class . '">'; //cpt list item title wrap before
						$aft_title = '</h3>'; //cpt list item title wrap after
						
						$more_links_to = ''; //cpt list item read more link
						
						if($title_links != 'false'){
							$extension = '';
							$link_title_to = $title_links;
							if($title_links == 'true'){
								if($link_to_file != 'false'){
									if($link_to_file == 'true'){
										$args = array(
											'post_type' => 'attachment',
											'numberposts' => null,
											'post_status' => null,
											'post_parent' => $post->ID
										);
										$attachments = get_posts($args);
										if ($attachments) {
											if($attachments[$attachment_to_use]){
												$attachment = $attachments[$attachment_to_use];
											}else{
												$attachment = $attachments[0];
											}
											$link_title_to = wp_get_attachment_url($attachment->ID, false);
											if($file_extension != 'false'){
												if($file_extension == 'true'){
													$extension = explode('.',$link_title_to);
													$extension = $extension[count($extension)-1];
												}else{
													$extension = str_replace("'","\\'",str_replace(' ','-',$file_extension));	
												}
											}
										}else{
											$link_title_to = '#';
										}
									}else{
										$link_title_to = $link_to_file;
									}
								}else{
									$link_title_to = get_permalink($post->ID);
								}
							}
							$pre_title .= '<a href="' . $link_title_to . '" class="cpt_item_title_link ' . $extension . '">';
							$aft_title = '</a>' . $aft_title;
							$more_links_to = $link_title_to;
						}else{
								$more_links_to = get_permalink($post->ID);
						}
						$thumb_html = ''; //cpt list item thumbnail
						if($show_thumbs == 'true'){
							if ( function_exists('has_post_thumbnail') && has_post_thumbnail($post->ID) ) {
							 	$thumbnails = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );
								if (!$thumbnails[0]){
									$thumb_html = '';
								}else{
									$alt = '' . attribute_escape($post->post_title) .'';
									$title = '' . attribute_escape($post->post_title) . '';							
									if(get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true)){
										$alt = get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true);
										$title = get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_alt', true);
									}
									if(get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_title', true)){
										$title = get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_title', true);
										if($alt==''){
											$alt = get_post_meta(get_post_thumbnail_id($post->ID), '_wp_attachment_image_title', true);	
										}
									}
									
									$thumb_size_html = '';
									if($thumb_height != 'false'){
										$thumb_size_html .= 'height="' . $thumb_height . '" ';
									}
									if($thumb_width != 'false'){
										$thumb_size_html .= 'width="' . $thumb_width . '" ';
									}
									$thumb_html = '<img src="' . $thumbnails[0] . '" border="0" class="attachment-post-thumbnail wp-post-image" title="' . $title . '" alt="' . $alt . '" ' . $thumb_size_html . ' />';
									if($thumb_link != 'false'){
										$link_thumb_to = '';
										switch($thumb_link){
											case 'true':
											if(!$link_title_to){
												$link_title_to = get_permalink($post->ID);
											}
											$link_thumb_to = $link_title_to;
											break;
											case 'post_index':
											$link_thumb_to = '#' . intval($count-1);
											break;
											case 'id':
											$link_thumb_to = get_post_thumbnail_id($post->ID);
											break;
											case 'src':
											$link_thumb_to = $thumbnails[0];
											break;
											default:
											$link_thumb_to = $thumb_link;
											break;
										}
										$thumb_html = '<a href="' . $link_thumb_to . '" class="cpt_item_thumb_link">' . $thumb_html . '</a>';
									}
								}
							}
						}
						$content_html = '';
						if($show_post_content == 'true'){
							$content_html = $post->post_content;
							$content_html = ( $excerpt_only == 'true' ) ? $post->post_excerpt : $post->post_content; //http://craigwaterman.com
						}else if($show_post_content == 'false'){
							
						}else{
							$content_html = $show_post_content;	
						}
						
							
						// ADDED BY JUSTIN GREER 8/21/2011 - ABILITY TO SHOW ONLY IMAGES FROM THE POST
								if ($images_only == "true"){
									
									  	$num = $images_only_num;
										global $more;
										$more = 1;
										$link = get_permalink();
										$content = $post->post_content;
										$count = substr_count($content, '<img');
										$start = 0;
										for($i=1;$i<=$count;$i++) {
										$imgBeg = strpos($content, '<img', $start);
										$post = substr($content, $imgBeg);
										$imgEnd = strpos($post, '>');
										$postOutput = substr($post, 0, $imgEnd+1);
										$postOutput = preg_replace('/width="([0-9]*)" height="([0-9]*)"/', '',$postOutput);;
										$image[$i] = $postOutput;
										$start=$imgEnd+1;
									}
								$content_html = $postOutput;									
									}else if($images_only == 'false'){
							
						}else{
							$content_html = $images_only;	
						}
							
							// ADDED BY JUSTIN GREER - 08/21/2011
									$show_date1= '';
										if($show_date == 'true'){
											$show_date1 = $post->post_date;
										}else if($show_date == 'false'){
											
										}else{
									$show_date1 = $show_date;	
								}
									

						if($use_single_blocks == 'false'){
							$pre_item = '<dt class="' . $ext_class . '">';
							$btw_item = '</dt><dd class="cpt_item_content">';
							$aft_item = '</dd>';
						}else{
							$open_wrap_html = '';
							$close_wrap_html = '';
							
							if($wrap_with!='false'){
								$open_wrap_html = '<'.$wrap_with.'>';
								
								$wrap_close_tag = explode(' ',$wrap_with);
								
								$close_wrap_html = '</'.$wrap_close_tag[0].'>';	
							}
							
							$pre_item = '<li class="' . $ext_class . '">'.$open_wrap_html;
							$btw_item = '<div class="cpt_item_content">';
							
							$aft_item = '</div>'.$close_wrap_html.'</li>';
						}
												
						$more_link = '';
						if($read_more_link != 'false'){
							if($read_more_link == 'true'){
								$more_link = '<a href="' . $more_links_to . '">Read More...</a>';	
							}else{
								$more_link = '<a href="' . $more_links_to . '">' . $read_more_link . '</a>';
							}
							$aft_item = $more_link . $aft_item;
						}
						
							$output .= $pre_item;
							$output .= $pre_title . $post->post_title . $aft_title;
							$output .= $pre_title . $show_date1 . $after_title; // ADDED BY JUSTIN GREER 8/20/2011 - DATE OUTPUT
							$output .= $btw_item;
							$output .= $thumb_html. $content_html;
							$output .= $aft_item;
						
						// debugging code
						//$output = '<pre>' . print_r($post,true) . '</pre>';
					endforeach;
					$output .= $aft_list;
				} else {
					$output .= '<p>' . $post_type_object->labels->not_found . '</p>';
				}
			} else {
				$output = '<p>Post Type <em>{$post_type}</em> is not publicly queriable.</p>';
			}
			// debugging code
			// $output = '<pre>' . print_r($post_type_object,true) . '</pre>';
		} else {
			$output = '<p>Post Type <em>{$post_type}</em> does not exist.</p>';
		}
		return $output;
	}
	

	// SHOW MESSAGES
	private function showMessages() {
		if(!$errors) return;
		?>
		<div class="updated wp-cpt-shortcode-updated error"><p><strong><?php e('The following errors were reported:') ?></strong></p>
			<?php foreach($this->errors as $err) {
			print '<p>' . __($err) . '</p>';
			} ?>
		</div>
	<?php }

	
}

?>