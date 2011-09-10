

jQuery(function(){
   
   var adminurl = jQuery('#tagadderadminurl').val();
   var userid = jQuery('#tagadderuserid').val();
   var postid = jQuery('#tagadderpostid').val();
   
   var submitTags = function(tags){
       jQuery.post(
         adminurl,
         {
             action: 'add_tags',
             uid: userid,
             pid: postid,
             new_tags: tags
         },
         onResponse
       );
   }
   
   var onResponse = function(response){
       jQuery('#tagAdderError').hide();
       
        var res = jQuery(response).find('result').text() == 'success' ? true : false;
        if(res){
            var tags='';
            jQuery(response).find('accepted').each(function(i,val){
               tags += jQuery(val).text() + ', ';
            });
            
            tags = tags.substring(0,tags.length-2);
            
            var text = l10nobject.tags + ': ' + tags + ' ' + l10nobject.succadd;
            jQuery('#tagAdderAdded')
                .text(text)
                .show();
        }
        else{
           jQuery('#tagAdderError')
              .text( jQuery(response).find('reason').text() )
              .show(); 
        }
   }
   
   var validateInput = function(input){
      input = input.trim();
      if(!input)
          return false;
      
      var words = input.split(',');
      
      jQuery.each(words,function(i,val){
          words[i] = words[i].toLowerCase();
          words[i] = words[i].trim();
      });
     
      var ret = words;
      jQuery.each(words,function(i,val){
         if( words[i].match(/\w+\s+\w+/) )
             ret = false;
      });
  
      return ret;
   }
   
   jQuery('#addTagsSubmit').click(function(){
       var tags = validateInput( jQuery('#addTagsInput').val() );
       if(tags === false){
           jQuery('#tagAdderAdded').hide();
           
           jQuery('#tagAdderError')
              .text(l10nobject.err_validation)
              .show();
       }    
       else{
            submitTags(tags);
       }
   });
   
});


