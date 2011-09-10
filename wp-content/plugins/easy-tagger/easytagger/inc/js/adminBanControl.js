

jQuery(function(){
    
    var selected_user = null;
    var selected_villain = null;
    var adminurl = jQuery('#adminadminurl').val();
    
    
    var showError = function(where,msg){
        jQuery(where)
            .text(msg)
            .show();
        window.setTimeout(function(){
          jQuery(where)
            .text(msg)
            .hide();
        }, 3000);
    }
    
    var showSuccess = function(where,msg){
        jQuery(where)
            .text(msg)
            .css('background','#E2E2E2')
            .show();
        window.setTimeout(function(){
          jQuery(where)
            .text(msg)
            .css('background','#fecac2')
            .hide();
        }, 3000);
    }
    
   /* select from Users list */
   jQuery('#et-users tr').click(
        function(){
            selected_user = jQuery(this);
            jQuery('#et-container tr').each(function(i,val){
               jQuery(val).css('background','#ffffff'); 
            });
            selected_user.css('background','#dddddd');
        }
    );
   
   /*select from Banned list */
   jQuery('#et-villains tr').click(
        function(){
            selected_villain = jQuery(this);
            jQuery('#et-villains tr').each(function(i,val){
                 jQuery(val).css('background','#ffffff'); 
            });
            selected_villain.css('background','#dddddd');
        }
    );
        
    /* normal list submit button */
    jQuery('#ban_submit').click(
        function(){
            if(selected_user){             
                var expireStr = jQuery('#ban_expire').val();
                var reasonStr = jQuery('#ban_reason').val();
                expireStr = expireStr.trim();
                
                if(!expireStr)
                   showError('#ban_warning',l10nobject.noexp_msg);
                else if(!reasonStr)
                    showError('#ban_warning',l10nobject.noreason_msg);
                else if(!expireStr.match(/^\d\d\d\d-\d\d-\d\d \d\d:\d\d:\d\d$/) )
                    showError('#ban_warning',l10nobject.wrongformat_msg);
                else{
                      var data = {};
                      data.expire = expireStr;
                      data.reason = reasonStr;
                      data.id = selected_user.children('#ID').text();
                      data.action = 'add_ban';
                      
                      jQuery.post(
                        adminurl,
                        data,
                        function(result){
                            showSuccess('#ban_warning',l10nobject.success_msg);
                        }
                      );
                }
            }
        }
    );
        
    /* ban list submit button */
    jQuery('#unban_submit').click(
        function(){
            if(selected_villain){
               var data = {};
               data.action = 'remove_ban';
               data.id = selected_villain.children('#ID2').text();
               
               jQuery.post(
                    adminurl,
                    data,
                    function(result){
                        showSuccess('#unban_warning',l10nobject.success_msg);
                    }
                );
            }
        }
    );
       
    /* enable / disable custom url input */
    jQuery('#et_redirection').change(
        function(){
            if( jQuery('#et_redirection option:selected').val() != 1){
                jQuery('#et_redirect_url')
                    .removeAttr('disabled')
                    .css('background','#ffffff');
                
            }
            else{
                jQuery('#et_redirect_url')
                    .attr('disabled','')
                    .val('')
                    .css('background','#bbbbbb');
            }
        }
    );
        
   
});

