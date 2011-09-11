<?php

echo '<link rel="stylesheet" type="text/css" href="'.plugins_url('nm_mailchimp_style.css', __FILE__).'"/>';
wp_enqueue_script('jquery');

$bgURL = plugins_url('/images/'.$bgbox, __FILE__);
?>
<script type="text/javascript">
 if (typeof jQuery == 'undefined') { 
   var head = document.getElementsByTagName("head")[0];
   script = document.createElement('script');
   script.id = 'jQuery';
   script.type = 'text/javascript';
   script.src = '<?php echo plugins_url('/js/jquery-1.4.4.min.js', __FILE__)?>';
   head.appendChild(script);
}
</script>
<!--<script type="application/javascript" src="<?php echo plugins_url('/js/jquery-1.4.4.min.js', __FILE__)?>"></script>-->



<div  class='sidebar-social widget-block'>
	<div class="widget-block-topline"></div>
    <div class="widget-wrap">
    <div id='newsletter_title' class='widget-title'>Newsletter</div>
    <div class="social-spacing"></div>
    
  
	   <?php if($show_names):?>
    	<input type="text" id="fname" class="field_names" onClick="nm_clickclear(this, 'First Name')" onBlur="nm_clickrecall(this,'First Name')" value="First Name" />
        <input type="text" id="lname" class="field_names" onClick="nm_clickclear(this, 'Last Name')" onBlur="nm_clickrecall(this,'Last Name')" value="Last Name" />
    <?php endif?>
        
       	<input type="text" id="subsc_email" class="field_email" onClick="nm_clickclear(this, 'Enter email')" onBlur="nm_clickrecall(this,'Enter email')" value="Enter email" />
        <input type="hidden" value="<?php echo get_option('nm_mc_apikey');?>" id="nm_mailchimp_api_key" />
        <input type="hidden" value="<?php echo $list_id;?>" id="nm_mailchimp_list_id" />
        <input id='btn_email' type="button" class="btn_email" onClick="postToMailChimp()" />
     </div>
</div>


<script type="text/javascript">
//jQuery(document).ready(function($) {
	
  $('#subsc_email').css('width',($('#newsletter_title').width()-30-15)+'px');
 // });
 
//jQuery.('#subsc_email').css('width',jQuery.('#newsletter_title').width()+'px');
function postToMailChimp()
{
	var e 		= $('#subsc_email').val();
	var apikey 	= $('#nm_mailchimp_api_key').val();
	var listid	= $('#nm_mailchimp_list_id').val();
	var f		= $('#fname').val();
	var l		= $('#lname').val();	
	
	//alert(e);
	$('#subsc_email').val('Subscribing...');
	
	var url_api_mailchimp = "<?php echo plugins_url('api_mailchimp/store-address.php', __FILE__)?>";
	//alert(url_api_mailchimp);
	
	$.post(url_api_mailchimp, {email: e,
								api_key: apikey,
								list_id: listid,
								fname: f,
								lname: l}, function(data){
			alert(data);
			$('#subsc_email').val('Enter email');
	});
}


function nm_clickclear(thisfield, defaulttext, color) {
	if (thisfield.value == defaulttext) {
		thisfield.value = "";
		if (!color) {
			color = "666666";
		}
		thisfield.style.color = "#" + color;
	}
}
function nm_clickrecall(thisfield, defaulttext, color) {
	if (thisfield.value == "") {
		thisfield.value = defaulttext;
		if (!color) {
			color = "666666";
		}
		thisfield.style.color = "#" + color;
	}
}
</script>


	