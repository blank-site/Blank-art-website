
String.prototype.format = function() {
    var formatted = this;
    for(arg in arguments) {
        formatted = formatted.replace("{" + arg + "}", arguments[arg]);
    }
    return formatted;
};



/*
 * Grid Image controller singleton
 * uses ajax to display, roll and update
 * featured images mosaic
 * 
*/
var ImageGrid = (function(){
    
    /* private methods and variables */
    var defaults = {
        columns : 2,
        rows : 2,
        imageResolution : 'thumbnail',
        imageWidth : 150,
        imageHeight: 150,
        arrowWidth: 15,
        arrowHeight: 30
    };
    
    var options = defaults;
    
    var imageData = [];
    var imageFrame = [];
    var totalFrames;
    var currentFrame = 0;
    var adminurl;
        
        
    var constructDOM = function(images){
        var $domContainer = jQuery('#gridDisplay');
               
           var cols = options.columns < images.length ? options.columns : images.length;
           var rows = options.rows < Math.floor(images.length/options.columns) ? options.rows : Math.floor(images.length/options.columns);
               
           var totalW = cols*options.imageWidth + options.arrowWidth*2;
           var totalH = rows*options.imageHeight + 50;
           
           jQuery('#gridWrapper')
                .css('width',totalW)
                .css('height',totalH);
                
           var marginImg = rows*options.imageHeight/2 - options.arrowHeight/2;
        
           jQuery('#leftControl')
                .append(jQuery('<img>')
                    .attr('src',jQuery('#leftarrow').val())
                    .css('width',options.arrowWidth)
                    .css('height',options.arrowHeight)
                    .css('margin-top',marginImg)
                    .hover(
                        function(){
                            jQuery(this).stop(true,true).fadeTo('fast','1.0');
                        },
                        function(){
                             jQuery(this).stop(true,true).fadeTo('fast','0.7');
                        })
                    .click(function(){
                        var fsize = options.rows * options.columns;
                       currentFrame = (currentFrame==0) ? totalFrames-1 : currentFrame-1;
                       imageFrame = imageData.slice(currentFrame*fsize,(currentFrame+1)*fsize);
                       updateImages();
                    }));
                                
            jQuery('#rightControl')  
                .append(jQuery('<img>')
                    .attr('src',jQuery('#rightarrow').val())
                    .css('width',options.arrowWidth)
                    .css('height',options.arrowHeight)
                    .css('margin-top',marginImg)
                    .hover(
                        function(){
                            jQuery(this).stop(true,true).fadeTo('fast','1.0');
                        },
                        function(){
                             jQuery(this).stop(true,true).fadeTo('fast','0.7');
                        })
                    .click(function(){
                        var fsize = options.rows * options.columns;
                        currentFrame = (currentFrame+1) % totalFrames;
                        imageFrame = imageData.slice(currentFrame*fsize,(currentFrame+1)*fsize);
                        updateImages();
                    }));                  
        
        jQuery.each(images,function(i,val){
           var icont = jQuery('<a>')
                    .attr('id','imageContainer')
                    .attr('href',document.URL+'?p='+val.id)
                    .append(jQuery('<img>')
                        .lazyload()  
                        .css('width',options.imageWidth)
                        .css('height',options.imageHeight)
                        .attr('src',val.thumb_url))
                    .append(jQuery('<div>')
                        .attr('id','imageTextLayer')
                        .append(jQuery('<p>')
                            .attr('id','imageText')
                            .text(val.post_title)
                         )
                         .hide());
                    
                      
            if( i % options.columns == 0 ){
                icont.css('clear','left');
            } 
            icont.hover(
                        function(){
                            jQuery(this).find('#imageTextLayer').show();
                        },
                        function(){
                            jQuery(this).find('#imageTextLayer').hide();
                        }
                     );
         
            
            
             $domContainer.append(icont);
        });
        
    }
    
    var fetchImageData = function(firstTime){
        
         if(firstTime){
            var filtertag = jQuery('#gridfiltertag').val();
            var data = {
                    action: 'fetch_thumbs',
                    count: options.rows * options.columns,
                    isize: options.imageResolution
                 };
             if(filtertag)
                 data.tag_slug = filtertag;
             
             jQuery.post(
                 adminurl,
                 data,
                 function(response){
                     imageData = [];
                     jQuery(response).find('entity').each(function(){
                         var $e = jQuery(this);
                         imageData.push(
                           {
                             id : $e.children('id').text(),
                             post_name : $e.children('post_name').text(),
                             post_title : $e.children('post_title').text(),
                             thumb_url : $e.children('thumb_url').text(),
                             thumb_width : $e.children('thumb_width').text(),
                             thumb_height : $e.children('thumb_height').text()
                           }
                        );               
                     });
                    var fsize = options.rows * options.columns;
                    totalFrames = Math.floor(imageData.length / fsize)+1;
                    imageFrame = imageData.slice(currentFrame*fsize,(currentFrame+1)*fsize);
                    
                    constructDOM(imageFrame);  
                    updateImages();
                }
             );
         } 
    }
    var updateImages = function(){
        var a = jQuery('#gridDisplay a');
        var img = jQuery('#gridDisplay a img');
        var lbl = jQuery('#gridDisplay a #imageTextLayer p');
        var fsize = options.rows * options.columns;
        
        
        jQuery.each(a,function(i,val){
            jQuery(a[i]).css('visibility','visible');
        });
        
        jQuery.each(imageFrame,function(i,val){
            jQuery(img[i])
                .attr('src',val.thumb_url);
             jQuery(a[i])
                .attr('href',document.URL+'?p='+val.id);
             jQuery(lbl[i])
                .text(val.post_title);
            
        });
        
        
        if(imageData.length < fsize*(currentFrame+1) && a.length == fsize ){
            var count = fsize*(currentFrame+1) - imageData.length;
            var it = a.length-1;
            while(count>0){
                jQuery(a[it--]).css('visibility','hidden');
                count--;
            }
        }
        
        var lower = fsize*currentFrame + 1;
        var higher = (fsize*(currentFrame+1)) > imageData.length ? imageData.length : (fsize*(currentFrame+1));
        
        jQuery('#infoArea p').text(
            '{0}-{1} of {2}'.format(lower,higher,imageData.length)
        );
    }
   
   
    
    /* public methods */
    return {
        set : function(args){
            options = jQuery.extend({}, options, args);
        },
        toString : function(){
            var str = 'Options:\n';
            jQuery.each(options,function(i,val){
                str += i + ':' + val + ', ';
            });
            str += '\nImages:\n';
            jQuery.each(imageData,function(i,val){
                 jQuery.each(val,function(i,e){
                      str += i + ':' + e + ', ';
                 });
                 str += '\n';
            });
            return str.substr(0,str.length-2);
        },
        printHTML: function(){
           fetchImageData(true);
        },
        
        init: function(args){
             adminurl = jQuery('#gridadminurl').val();
             ImageGrid.set(args);
        }
    }
    
})();


jQuery(function(){
    
    var r,c,w,h;
    w = jQuery('#gridimagewidth').val();
    h = jQuery('#gridimageheight').val();
    r = jQuery('#gridrows').val();
    c = jQuery('#gridcolumns').val();
    
    ImageGrid.init(
        {
            imageResolution : 'thumbnail',
            rows : r,
            columns: c,
            imageWidth: w,
            imageHeight: h
        });
    
    ImageGrid.printHTML();
    
   
});