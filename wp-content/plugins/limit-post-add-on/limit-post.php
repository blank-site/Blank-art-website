<?php
/*
Plugin Name: Limit Post Add-On
Plugin URI: http://www.doc4design.com/plugins/limit-post/
Description: Limits the displayed text length with both the_content_limit and get_the_content_limit
Version: 1.0
Author: Doc4, Alfonso Sanchez-Paus Diaz, Julian Simon de Castro
Author URI: http://www.doc4design.com
*/

function the_content_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);

   if (strlen($_GET['p']) > 0) {
      echo $content;
   }
   else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
        $content = substr($content, 0, $espacio);
        $content = $content;
        echo $content;
        //echo "<a href='";
        //the_permalink();
        echo "...";
        echo "<br>";
        echo "<div class=";
		echo "'read-more'>";
		echo "<a href='";
        the_permalink();
        echo "'>".$more_link_text."</a></div></p>";
   }
   else {
      echo $content;
   }
}

function get_the_content_limit($max_char, $more_link_text = '(more...)', $stripteaser = 0, $more_file = '') {
    $content = get_the_content($more_link_text, $stripteaser, $more_file);
    $content = apply_filters('get_the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
	$content = strip_tags($content);

   if (strlen($_GET['p']) > 0) {
      echo $content;
   }
   else if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))) {
        $content = substr($content, 0, $espacio);
        $content = $content;
        echo $content;
        //echo "<a href='";
        //the_permalink();
        echo "...";
        echo "<br>";
        echo "<div class=";
		echo "'read-more'>";
		echo "<a href='";
        the_permalink();
        echo "'>".$more_link_text."</a></div></p>";
   }
   else {
      echo $content;
   }
}

?>
