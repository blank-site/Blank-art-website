<?php


function savePortfolioRow($data,$files,$method){
		global $wpdb ;

	$id = $data['id'];
	
	$a['date'] = $data['date'];
	$a['name'] = $data['name'];
	$a['description'] = $data['description'];
	$a['projecttype'] = $data['projecttype'];
	$a['type'] = $data['type'];
	$a['projecttype2'] = $data['projecttype2'];
	$a['projecttype3'] = $data['projecttype3'];
	$a['projecttype4'] = $data['projecttype4'];
	$a['projecttype5'] = $data['projecttype5'];
	$a['projecttype6'] = $data['projecttype6'];
	
	
	
	if($data['new_company_type'] != ""){
		
		$a['companytype'] = $data['new_company_type'];
		
	}else{
		$a['companytype'] = $data['companytype'];
	}
	$a['manualmeta'] = $data['manualmeta'];
	$a['meta_keywords'] = $data['meta_keywords'];
	$a['meta_title'] = $data['meta_title'];
	$a['meta_description'] = $data['meta_description'];
	
	$a['link'] = $data['link'];
	
	if($files['picture']['name'] != ""){
		
			$filename = rand();
		  	$picture = $filename."-".$files['picture']['name']."";
			$thefile =  $files['picture']['tmp_name'];	
			$uploaded_file = ''.ABSPATH.'wp-content/plugins/dlg-portfolio/uploads/'.$picture; 
			
			$uploaded_file_thumb = ''.ABSPATH.'wp-content/plugins/dlg-portfolio/uploads/thumbs/'.$picture; 

			$rimg=new RESIZEIMAGE($thefile);
			$rimg->resize_limitwh("750","350",$uploaded_file);
			$rimg->resize_limitwh("300","300",$uploaded_file_thumb);
			$rimg->close();
			$a['picture'] = $picture;
		
	}
	
	if($files['banner']['name'] != ""){
			$thefile =  $files['banner']['tmp_name'];	
			$filename = rand();
		  	$banner = $filename."-".$files['pictures']['name']."";

			$uploaded_file = ''.ABSPATH.'wp-content/plugins/dlg-portfolio/uploads/'.$banner; 
			

			$rimg=new RESIZEIMAGE($thefile);
			$rimg->resize_limitwh("940","300",$uploaded_file);
			$rimg->close();	
			$a['banner'] = $banner;
		
	}	
	
	
	
	
	if($method == 'insert'){
	
    $wpdb->insert(  'wp_dlg_portfolio', $a );
		
	}else{
	$where['id'] = $id;
	$wpdb->update(  'wp_dlg_portfolio', $a , $where );
		
	}

	
	
	
	
	
	
	
	
}

function deletePortfolioRow($id){
	global $wpdb ;
	
	
	
	$wpdb->query("
	DELETE FROM wp_dlg_portfolio WHERE id = $id
	");
	header("Location: admin.php?page=dlg-portfolio");
	
}
function getPortfolioRows($type){
	
	
		global $wpdb ;
	
	
	$results = $wpdb->get_results("SELECT *  FROM wp_dlg_portfolio   where type =  $type", ARRAY_A);
	
	
	
	
	$table  .= '<table class="wp-list-table widefat fixed posts" cellspacing="0">
	<thead>
	<tr>
<th>Name</th>
<th>Type</th>
<th>Delete</th>
	</thead>';
	
	for($i=0; $i<count($results); $i++){
		
		$table .= '
		<tr>
		<td><a href="admin.php?page=dlg-portfolio&id='.$results[$i]['id'].'">'.$results[$i]['name'].'</a></td>
		<td>'.$results[$i]['projecttype'].'</td>
		<td><a href="admin.php?page=dlg-portfolio&id='.$results[$i]['id'].'&delete=1">Delete</a></td>
		</tr>
		';
	}
	$table .= '</table>';
	
	return $table;
	
}

function addPortfolioForm($id = NULL){
	
	global $wpdb ;
	$results = $wpdb->get_results("SELECT *  FROM wp_dlg_portfolio  GROUP BY companytype order by companytype;", ARRAY_A);
	
	
	if($id != NULL){
				
				
	
			$r = $wpdb->get_results("SELECT *  FROM wp_dlg_portfolio  WHERE id = ".$id."", ARRAY_A);
		
	}

				for($i=0; $i<count($results); $i++){
				
				
				if($results[$i]['companytype'] == $r[0]['companytype']){
				$companytype_selected = 'selected="selected"';
				}else{
				$companytype_selected = '';	
				}
			$companytype .=  '	<option '.$companytype_selected.'  value="'.$results[$i]['companytype'].'">'.$results[$i]['companytype'].'</option> ';
				
				
				}

	$form .=  wp_tiny_mce(false,array("editor_selector" => "description"));

	$form .= '
<form enctype="multipart/form-data" method="post" action="admin.php?page=dlg-portfolio" >
<input type="hidden" name="id" value= "'.$id.'">
<table width="100%" border="0" class="style2">



<tr>

<tr>

   <td>Name Of Project:</td>

    <td>

	 <input name="name" type="text" size="60"  value="'.$r[0]['name'].'" />



</td>

  </tr> 
  <tr>

   <td>Project Level:</td>

    <td>



	  <select name="type">
	<option value="'.$r[0]['type'].'">Choose a level</option>
        <option value="1" >Level 1</option>

        <option value="2">Level 2</option>

        <option value="3">Level 3</option>

        <option value="4">Level 4</option>

        <option value="5">Level 5</option>



</select>
</td></tr>
    <tr>

   <td>Feature Banner:</td>

    <td>';

if($r[0]['banner'] != ""){
	
$form .='Current image<br><img src="../wp-content/plugins/dlg-portfolio/uploads/'.$r[0]['banner'].'"><br>';	
}

	  $form .='<input type="file" name="banner" />
</td></tr>
  
 

<tr>

<tr>

   <td>Project Type:</td>

    <td>

	1.	<select name="projecttype">
<option value="'.$r[0]['projecttype'].'">'.$r[0]['projecttype'].'</option>
	<option value="Client Site Review">Client Site Review</option>

	<option value="Partner Site Review">Partner Site Review</option>

	<option value="Competitor Site Review">Competitor Site Review</option>

	<option value="Web Site Design">Web Site Design</option>

	<option value="Maintenance Programs">Maintenance Programs</option>

	<option value="Search Engine Optimization">Search Engine Optimization</option>

              <option value="Application Development">Application Development</option>

            <option value="Content Development ">Content Development </option>

              <option value="E-Commerce">E-Commerce</option>

                <option value="Pay Per Click Advertising">Pay Per Click Advertising</option>

                   <option value="Manufacturing">Manufacturing</option>

        <option value="Medical">Medical</option>      <option value="Viral Marketing and Advertising">Viral Marketing and Advertising</option>

                    <option value="Website Hosting">Website Hosting</option>

	</select><br />

	

	2.		<select name="projecttype2">
<option value="'.$r[0]['projecttype2'].'">'.$r[0]['projecttype2'].'</option>


	<option value="Client Site Review">Client Site Review</option>

	<option value="Partner Site Review">Partner Site Review</option>

	<option value="Competitor Site Review">Competitor Site Review</option>

	<option value="Web Site Design">Web Site Design</option>

	<option value="Maintenance Programs">Maintenance Programs</option>

	<option value="Search Engine Optimization">Search Engine Optimization</option>

              <option value="Application Development">Application Development</option>

            <option value="Content Development ">Content Development </option>

              <option value="E-Commerce">E-Commerce</option>

                <option value="Pay Per Click Advertising">Pay Per Click Advertising</option>

                  <option value="Viral Marketing and Advertising">Viral Marketing and Advertising</option>

                    <option value="Website Hosting">Website Hosting</option>       <option value="Manufacturing">Manufacturing</option>

        <option value="Medical">Medical</option>

	</select><br />

	

	3.			<select name="projecttype3">
<option value="'.$r[0]['projecttype3'].'">'.$r[0]['projecttype3'].'</option>
	

	<option value="Client Site Review">Client Site Review</option>

	<option value="Partner Site Review">Partner Site Review</option>

	<option value="Competitor Site Review">Competitor Site Review</option>

	<option value="Web Site Design">Web Site Design</option>

	<option value="Maintenance Programs">Maintenance Programs</option>

	<option value="Search Engine Optimization">Search Engine Optimization</option>

              <option value="Application Development">Application Development</option>

            <option value="Content Development ">Content Development </option>

              <option value="E-Commerce">E-Commerce</option>

                <option value="Pay Per Click Advertising">Pay Per Click Advertising</option>

                  <option value="Viral Marketing and Advertising">Viral Marketing and Advertising</option>

                    <option value="Website Hosting">Website Hosting</option>       <option value="Manufacturing">Manufacturing</option>

        <option value="Medical">Medical</option>

	</select><br />

	

	4.			<select name="projecttype4">
<option value="'.$r[0]['projecttype4'].'">'.$r[0]['projecttype4'].'</option>


	<option value="Client Site Review">Client Site Review</option>

	<option value="Partner Site Review">Partner Site Review</option>

	<option value="Competitor Site Review">Competitor Site Review</option>

	<option value="Web Site Design">Web Site Design</option>

	<option value="Maintenance Programs">Maintenance Programs</option>

	<option value="Search Engine Optimization">Search Engine Optimization</option>

              <option value="Application Development">Application Development</option>

            <option value="Content Development ">Content Development </option>

              <option value="E-Commerce">E-Commerce</option>

                <option value="Pay Per Click Advertising">Pay Per Click Advertising</option>

                  <option value="Viral Marketing and Advertising">Viral Marketing and Advertising</option>

                    <option value="Website Hosting">Website Hosting</option>       <option value="Manufacturing">Manufacturing</option>

        <option value="Medical">Medical</option>

	</select><br />

	

	5.			<select name="projecttype5">
<option value="'.$r[0]['projecttype5'].'">'.$r[0]['projecttype5'].'</option>


	<option value="Client Site Review">Client Site Review</option>

	<option value="Partner Site Review">Partner Site Review</option>

	<option value="Competitor Site Review">Competitor Site Review</option>

	<option value="Web Site Design">Web Site Design</option>

	<option value="Maintenance Programs">Maintenance Programs</option>

	<option value="Search Engine Optimization">Search Engine Optimization</option>

              <option value="Application Development">Application Development</option>

            <option value="Content Development ">Content Development </option>

              <option value="E-Commerce">E-Commerce</option>

                <option value="Pay Per Click Advertising">Pay Per Click Advertising</option>

                  <option value="Viral Marketing and Advertising">Viral Marketing and Advertising</option>

                    <option value="Website Hosting">Website Hosting</option>       <option value="Manufacturing">Manufacturing</option>

        <option value="Medical">Medical</option>

	</select><br />

	

	6.			<select name="projecttype6">
<option value="'.$r[0]['projecttype6'].'">'.$r[0]['projecttype6'].'</option>


	<option value="Client Site Review">Client Site Review</option>

	<option value="Partner Site Review">Partner Site Review</option>

	<option value="Competitor Site Review">Competitor Site Review</option>

	<option value="Web Site Design">Web Site Design</option>

	<option value="Maintenance Programs">Maintenance Programs</option>

	<option value="Search Engine Optimization">Search Engine Optimization</option>

              <option value="Application Development">Application Development</option>

            <option value="Content Development ">Content Development </option>

              <option value="E-Commerce">E-Commerce</option>

                <option value="Pay Per Click Advertising">Pay Per Click Advertising</option>

                  <option value="Viral Marketing and Advertising">Viral Marketing and Advertising</option>

                    <option value="Website Hosting">Website Hosting</option>       <option value="Manufacturing">Manufacturing</option>

        <option value="Medical">Medical</option>

	</select><br />

</td>

  </tr>

  <tr>

<td colspan="2">';
if ($r[0]['manualmeta'] == 1){
	
$formchecekd = 'checked="checked"';
}else{
$formchecekd = '';	
}


$form .='Manually enter meta tags? <input type="checkbox" name="manualmeta" value="1" '.$formchecekd.'  />



<div id="checked" style=" height:100px; background-color:#F5F5F5;padding:10px;margin:10px">

<table width="100%" border="0" cellspacing="0" cellpadding="0">

  <tr>

    <td>Title:</td>

    <td><input type="text" name="meta_title"  size="70" value="'.$r[0]['meta_title'].'" /></td>

  </tr>

  <tr>

    <td>Description: </td>

    <td><input type="text" name="meta_description"  size="70" value="'.$r[0]['meta_description'].'" /></td>

  </tr>

  <tr>

    <td>Keywords:</td>

    <td><input type="text" name="meta_keywords"  size="70" value="'.$r[0]['meta_keywords'].'" /></td>

  </tr>

</table>





</div>



</td>

</tr>



  <tr>

   <td>Company Type:</td>

    <td>

	<select name="companytype">


        

        '.$companytype .'

        

    

	</select> or new company type: <input type="text" name="new_company_type">

</td>

  </tr>

<tr>

   <td>Link:</td>

    <td>

	<input name="link" type="text" size="60" value="'.$r[0]['link'].'"  />

</td>

  </tr>

  <tr>

   <td>Upload Picture:</td>

    <td>';
	if($r[0]['picture'] != ""){
	
$form .='Current image<br><a href="../wp-content/plugins/dlg-portfolio/uploads/'.$r[0]['picture'].'" target="_blank"><img src="../wp-content/plugins/dlg-portfolio/uploads/'.$r[0]['picture'].'" width="100" border="0"></a><br>';	
}
	
	

	$form .= '<input name="picture" type="file" size="60"/>

</td>

  </tr>

  <tr>



    <td colspan="2">

	<p>Description</p>

 <textarea  name="description" id="preview" class="description" style="width:100%" rows="15">'.$r[0]['description'].'</textarea>

</td>

  </tr>

 

</table>

<p><input type="submit" name="save" value="Save"/></p>

</form>
';
	
	
	
	return $form;
}


?>