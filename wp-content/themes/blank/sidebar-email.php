<div class='sidebar-social widget-block'>
	<div class="widget-block-topline"></div>
    <div class="widget-wrap">
    <div class='widget-title'>Additional Infomation</div>
    <div class="social-spacing"></div>
    	<?php if (function_exists('contact_us')) {
			echo  'Direct email<a class="purple" href="mailto:'.contact_us('email').'"> - '.contact_us('email').'</a><br/>Direct call<a  class="purple" href="tell:'.contact_us('mobile').'"> - '.contact_us('mobile').'</a>' ;} 
		
		?>
       
    </div>
</div>