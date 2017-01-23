$.fn.preload = function() {
    this.each(function(){
        $('<img/>')[0].src = this;
    });
}
var images = Array(
                   "/userfiles/1/image/home-min.jpg",
                   "/userfiles/1/image/spas/wehavespas.jpg",
                   "/userfiles/1/image/outdoor-living/emily-rose-outdoor-set.png");

$([images[0],images[1],images[2]]).preload();


var currimg = 0;
var lastimg = 0;
$(document).ready(function(){
   
for (i = images.length -1; i >= 0; i--) {
   $('#slider').prepend( "<div id='img"+i+"' class='back' style='background-image: url(" + images[i] + ");display:none;'></div>");
}
  
   
   
    function loadimg(){
     

 		var oldimage = currimg; 
 		
 		$('#slider div.back').each(function(){
        		if( $(this).attr('id') == "img"+oldimage){
        			$(this).show();
        		}else{
        			$(this).hide(); 
        		}
   		});	
 		
   		
                currimg++;                
                if(currimg > images.length-1){                    
                    currimg=0;                    
                }             
         setTimeout(function() {
           $('#image').fadeOut('0',function() {
       
                var newimage = images[currimg];
           
                $('#image').css("background-image", "url("+newimage+")"); 
               
                $('#image').fadeIn("slow");

                setTimeout(loadimg,4000);
            });	       
  	},1000);

     }  
     $('#slider #img0').fadeIn("slow"); 
     setTimeout(loadimg,5000);
  
});