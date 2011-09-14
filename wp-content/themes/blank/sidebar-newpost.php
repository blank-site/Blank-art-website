<div  class='sidebar-social widget-block'>
	<div class="widget-block-topline"></div>
    <div class="widget-wrap">
    <div class='widget-title'>Latest blog post</div>
    <div class="social-spacing"></div>
   
  
	    <ul>
    <?php query_posts('showposts=1'); ?>

    <?php while (have_posts()) : the_post(); ?>
    <li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></li>

    <li><?php // the_content_limit(2); ?></li>
    <?php endwhile;?>
    </ul>
    
 
     </div>
</div>