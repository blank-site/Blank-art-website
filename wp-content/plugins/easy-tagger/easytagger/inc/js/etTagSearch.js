
jQuery(function(){
    
    var adminurl = jQuery('#tagsSearchAdminUrl').val();
    var siteurl = jQuery('#tagsSearchSiteUrl').val();
    var redirectOption = jQuery('#tagsSearchRedirection').val();
    var customurl = jQuery('#tagsSearchRedirectionUrl').val();
    
    
    var onResponse = function(response){
        if( jQuery(response).find('result').text() == 'success' ){
            var data = [];
            
            jQuery(response).find('tag').each(function(){
                data.push(
                    {
                        text: jQuery(this).children('name').text(),
                        slug: jQuery(this).children('slug').text()
                    }
                );
            });
            
            jQuery('#tagsSearchInput')
                .autocomplete(
                    data,{
                    formatItem: function(item){
                        return item.text;
                    }
                })
                .result(function(event,item){
                    if(redirectOption==1){
                        location.href = siteurl + '?tag='+item.slug;
                    }
                    else if(redirectOption==2){
                        location.href = customurl;
                    }
                    else{
                        location.href = customurl + '?tag='+item.slug;
                    }
                })
                .bgiframe();
        }
    }  
    
    jQuery('#tagsSearchSubmit').click(function(){
        var tagn = jQuery('#tagsSearchInput').val();
        if(redirectOption==1){
            location.href = siteurl + '?tag=' + tagn;
        }
        else if(redirectOption==2){
             location.href = customurl;
        }
        else{
            location.href = customurl + '?tag=' + tagn;
        }
    });
    
    jQuery.post(
       adminurl,
       {
            action: 'fetch_tags'
       },
       onResponse
    );
        
});


