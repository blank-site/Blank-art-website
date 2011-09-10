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

?>