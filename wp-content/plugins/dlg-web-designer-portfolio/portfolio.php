<?php
/*
Plugin Name: DLG Portfolio
Plugin URI: http://dlgresults.com/#
Description: Another portfolio plugin
Author: Anthony Brown
Version: 1.0
Author URI: http://dlgresults.com
*/
global $dlg_portfolio_version;
$dlg_portfolio_version = "1.0";


add_action('admin_menu', 'my_plugin_menu');

include 'functions.php';
include 'thumbs.php';
include 'shortcode.php';
include 'widget.php';
//admin page 

function dlg_portfolio_install() {
   global $wpdb;
   global $dlg_portfolio_version;

   $table_name = $wpdb->prefix . "dlg_portfolio";
      
   $sql = "
   CREATE TABLE IF NOT EXISTS `".$table_name."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `name` text NOT NULL,
  `description` text NOT NULL,
  `projecttype` text NOT NULL,
  `projecttype2` text NOT NULL,
  `projecttype3` text NOT NULL,
  `projecttype4` text NOT NULL,
  `projecttype5` text NOT NULL,
  `projecttype6` text NOT NULL,
  `companytype` text NOT NULL,
  `link` text NOT NULL,
  `picture` text NOT NULL,
  `manualmeta` int(1) NOT NULL DEFAULT '0',
  `meta_title` text NOT NULL,
  `meta_description` text NOT NULL,
  `meta_keywords` text NOT NULL,
  `type` varchar(11) NOT NULL,
  `banner` varchar(255) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
)";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
 
   add_option("dlg_portfolio_version", $dlg_portfolio_version);
}


register_activation_hook(__FILE__,'dlg_portfolio_install');


function remove_item_by_value($array, $val = '', $preserve_keys = true) {
	if (empty($array) || !is_array($array)) return false;
	if (!in_array($val, $array)) return $array;

	foreach($array as $key => $value) {
		if ($value == $val) unset($array[$key]);
	}

	return ($preserve_keys === true) ? $array : array_values($array);
}



if($_GET['delete-image'] != ""){

$sliders = listImages($_GET['slide-name']);


$sliders = remove_item_by_value($sliders,$_GET['delete-image']);


update_option($_GET['slide-name'], serialize($sliders));
header("Location: options-general.php?page=dlg-upload");
}

if($_POST['save-slide'] != ""){
	

	
function saveImages(){

	
	
	if( $_FILES['slide-image']['name'] != ""){
	$sliders = listImages($_POST['slide-name']);
	
	$target_path = "../wp-content/plugins/js-banner-rotate/uploads/";
		$filename = ''.time().'_'. $_FILES['slide-image']['name'];
	$target_path = $target_path .$filename; 

	move_uploaded_file($_FILES['slide-image']['tmp_name'], $target_path);
	
	if(!is_array($sliders)){
		$sliders = array();
	}
	array_push($sliders, $filename);
	
	
add_option($_POST['slide-name'], serialize($sliders));
update_option($_POST['slide-name'], serialize($sliders));
	}
add_option(''.$_POST['slide-name'].'-text', $_POST['slide-text']);
update_option(''.$_POST['slide-name'].'-text', $_POST['slide-text']);





	header("Location: options-general.php?page=dlg-upload");
	
}

echo saveImages();
}



function listImages($slide){
	
	return unserialize(get_option($slide));

}
function getSlideText($slide){
	
return	get_option($slide);
}
function my_plugin_menu() {
	add_menu_page('DLG Portfolio', 'DLG Portfolio', 'manage_options', 'dlg-portfolio', 'my_plugin_options');
	add_submenu_page( 'dlg-portfolio', 'DLG Portfolio', 'Add Portfolio Item', 'manage_options', 'add-portfolio', 'my_plugin_options_add');
}
function my_plugin_options_add() {
	echo addPortfolioForm();
}
function my_plugin_options() {
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	


	




	
	//plugin page goes here
	
			
			
			
	if($_GET['id'] == ""){
	echo '<h1>DLG Portfolio</h1>
		<p>Welcome to DLG Portfolio, to insert a portfolio you have a few options. You will notice the 5 levels below. Each level can have a different template style. Below are the configuration options</p>
		

<p><b>Flash:</b> Will display a flash style rotating banner<br>
	<b>Icons:</b> Will display a list in icons format<br>
	<b>List: </b> Will display the portfolio items in a list format
	</p>

	
	<p>The type field should reflect the level below, for instance if you want to display all level items you would pick type 1</p>

</p>
<h2>Examples:</h2>
[dlg-portfolio template="flash" type="1"]<br>

[dlg-portfolio template="icons" type="1"]<br>

[dlg-portfolio template="list" type="1"]<br>

	
	';
	
	
	echo '<a href="admin.php?page=add-portfolio">Add Portfolio Item</a>';
	echo '<h2>Level 1</h2>';
	echo 	getPortfolioRows(1);
	echo '<h2>Level 2</h2>';
	echo 	getPortfolioRows(2);
	echo '<h2>Level 3</h2>';
	echo 	getPortfolioRows(3);
	echo '<h2>Level 4</h2>';
	echo 	getPortfolioRows(4);
	echo '<h2>Level 5</h2>';
	echo 	getPortfolioRows(5);
		
	}else{
		echo addPortfolioForm($_GET['id']);	
	}

	
	
}



if($_POST['save'] != ""){
	
	
	if($_POST['id'] != ""){

	savePortfolioRow($_POST,$_FILES,'update');	
	}else{
	
	savePortfolioRow($_POST,$_FILES,'insert');	
	}



	
}

if($_GET['delete'] == 1){
	
deletePortfolioRow($_GET['id']);	
}
//end admin page
?>