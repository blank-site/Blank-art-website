<div class='sidebar-social widget-block'>
	<div class="widget-block-topline"></div>
    <div class="widget-wrap">
    <div class='widget-title'>Archives</div>
    <div class="social-spacing"></div>
    	<?php 
		$args = array(
		'type'            => 'monthly',
		'limit'           => '',
		'format'          => 'html', 
		'before'          => '',
		'after'           => '',
		'show_post_count' => true,
		'echo'            => 1);
		
		wp_get_archives($args); ?>
    </div>
</div>