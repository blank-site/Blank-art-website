<script type="text/javascript">
function on_search_focus(target){
	if(target.value.indexOf('Search')>=0){
		target.value="";
	}
}
function on_search_blur(target){
	if(target.value.length==0){
		target.value="Search";
	}
}

</script>
<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
    <div>
        <input type="text" value="Search" name="s" id="s"  onFocus="on_search_focus(this)" onBlur="on_search_blur(this)"/>
        <input type="submit" id="searchsubmit" value="" />
        
    </div>
</form>