<?php




function displayDLGPortoflioItem($atts){
	global $wpdb ;
	$type = $atts['type'];
	
	$id = $_GET['pid'];
	if($type == 0){
		
		$i = 0;
		$r = $wpdb->get_results("SELECT *  FROM wp_dlg_portfolio   where id = $id ", ARRAY_A);
		
		$nhtml ='
		
		
		<a href="/clients/">Click here to go back to all clients</a>
		<table width="100%" border="0" cellspacing="5" cellpadding="5" style="text-align:left">
  <tr>
    <td rowspan="2" width="440" style="text-align:center" valign="top">  <a href="'.$r[$i]['link'].'" target="_blank"><img src="/wp-content/plugins/dlg-portfolio/thumbnails.php?src=/wp-content/plugins/dlg-portfolio/uploads/'.$r[$i]['picture'].'&w=430&h=430" /><br /><br />
    
  Click here to view website</a></td>
    <td></td>

  </tr>
  <tr>
    <td valign="top"><h1>'.stripslashes($r[$i]['name']).'</h1>
    <p><strong>Company Type:</strong> '.stripslashes($r[$i]['companytype']).'</p>
   '.stripslashes($r[$i]['description']).'

</p>
      <table width="100%" border="0" cellspacing="5" cellpadding="5">
     
        <tr>
          <td><strong>What we did: </strong></td>
          <td> 
          <ul>';
		  
		  
		  if($r[$i]['projecttype'] != ""){         $nhtml .= '<li>'.$r[$i]['projecttype'].'</li>';		  }
		  if($r[$i]['projecttype2'] != ""){         $nhtml .= '<li>'.$r[$i]['projecttype2'].'</li>';		  }
		  if($r[$i]['projecttype3'] != ""){         $nhtml .= '<li>'.$r[$i]['projecttype3'].'</li>';		  }
		  if($r[$i]['projecttype4'] != ""){         $nhtml .= '<li>'.$r[$i]['projecttype4'].'</li>';		  }
		  if($r[$i]['projecttype5'] != ""){         $nhtml .= '<li>'.$r[$i]['projecttype5'].'</li>';		  }
		  if($r[$i]['projecttype6'] != ""){         $nhtml .= '<li>'.$r[$i]['projecttype6'].'</li>';		  }
		
          
         $nhtml .='</ul> 
          </td>

  </tr>

      </table>
    <p>&nbsp;</p></td>
  </tr>

</table>









';
	 return $nhtml;
	}
	
}

function displayDlgPortfolio($atts){
	
		global $wpdb ;

	$template = $atts['template'];
	$type = $atts['type'];
	
		$r = $wpdb->get_results("SELECT *  FROM wp_dlg_portfolio   where type =  $type", ARRAY_A);
	
	

		
		
		
		
	switch($template){
		
		
	case "flash":
	$html .= '
	
	<script type="text/javascript" src="/wp-content/plugins/dlg-portfolio/js/swfobject.js"></script>
		<script type="text/javascript">
			var flashvars = {};
			flashvars.settingsXML = "/wp-content/plugins/dlg-portfolio/banner/assets/xml/settings1.xml";
			var params = {};
			params.menu = "false";
			params.quality = "best";
			params.scale = "noscale";
			params.salign = "tl";
			params.wmode = "transparent";
			params.allowfullscreen = "true";
			var attributes = {};
			attributes.align = "middle";
			swfobject.embedSWF("/wp-content/plugins/dlg-portfolio/banner/deploy.swf", "componentDiv1", "950", "400", "9.0.0", "expressInstall.swf", flashvars, params, attributes);
		</script>
        
        <h2>Featured Clients</h2>
        
        	<div id="componentDiv1"></div>';
	break;
	case "icons":
	$html .= '<h2>Portfolio</h2>';
		for($i=0; $i<count($r); $i++){
	$html .= '<div style="float:left;width:32%; text-align:center;padding:5px;margin-bottom:5p;margin-top:10px;">
<h3><a href="?pid='.$r[$i]['id'].'">'.$r[$i]['name'].'</a></h3>
<a href="?pid='.$r[$i]['id'].'">
<img src="'.get_bloginfo('wpurl').'/wp-content/plugins/dlg-portfolio/thumbnails.php?src='.get_bloginfo('wpurl').'/wp-content/plugins/dlg-portfolio/uploads/'.$r[$i]['picture'].'&w=230&h=150" border="0">
</a>
</div>';
		}
		
		
	break;
	case "list":
	$html .= '<h2>Other Clients</h2>';
		for($i=0; $i<count($r); $i++){
	$html .= '<div style="float:left;width:23%; padding:5px;margin-bottom:5px;font-size:12px; text-align:left">
'.$r[$i]['name'].'

</div>';
	}
	
	break;
	}
	
	
	
	return '<div>'.$html.' <div style="clear:both"></div></div>';
	
	
}
function override_post_title_with_first_content_heading($title, $_post = null){
	global $wpdb ;
	$id = $_GET['pid'];
	$r = $wpdb->get_results("SELECT *  FROM wp_dlg_portfolio   where id = $id ", ARRAY_A);
	
	$title = $r[0]['meta_title'];

	return $title;
	
}
function get_project_extra_head(){
		global $wpdb ;
	$id = $_GET['pid'];
	$r = $wpdb->get_results("SELECT *  FROM wp_dlg_portfolio   where id = $id ", ARRAY_A);
	
	echo '<meta name="description" content="'.stripslashes($r[0]['meta_description']).'" />
<meta name="keywords" content="'.stripslashes($r[0]['meta_keywords']).'" />';
	
	
}
function dlg_check_seo(){
	
			global $wpdb ;
	$id = $_GET['pid'];
	$r = $wpdb->get_results("SELECT *  FROM wp_dlg_portfolio   where id = $id ", ARRAY_A);
	if($r[0]['manualmeta'] ==1){
		return true;
		
	}else{
	return false;	
	}
}

	if($_GET['pid'] != ""){
	add_shortcode( 'dlg-portfolio', 'displayDLGPortoflioItem' );
	if(dlg_check_seo() == true){
	 add_filter('wp_title','override_post_title_with_first_content_heading', 10, 3);
	 add_action('wp_head', 'get_project_extra_head');
	}
	}else{

	add_shortcode( 'dlg-portfolio', 'displayDlgPortfolio' );	
	}

?>