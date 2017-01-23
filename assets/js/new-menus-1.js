$( document ).ready(function() {
	
	
	var elem = $('li > a[href="'+window.location.pathname+'"]').parent();
	
	
	while(!$(elem).parent('ul').hasClass( "open" ) && $(elem).parent('ul').is('ul')){
		$(elem).parent('ul').addClass( "open" );
		elem = $(elem).parent().parent('li');		
	}

	
$(document).on( "click",'.downarrow',function(e) {
 e.stopPropagation(); 
	if(!$(this).parent().children('ul').hasClass( "open" ) && $("#hider-label").is(':visible')){
		$(this).parent().children('ul').addClass( "open" );		
	}else{$(this).parent().children('ul').removeClass("open");	}

	
});
	
	
	
	
	
	
	
	
	$("#exCollapsingNavbar2").css("height", "auto");
	$("#exCollapsingNavbar2").animate({ height: "toggle"}, 0);
	$(':checkbox').change(function(event) {
		if($(event.target).is("input#hider[type='checkbox']")){	
			
				$("#exCollapsingNavbar2").animate({ height: "toggle"}, "slow" );
			
		}
	});
});

var foo1 = (('ontouchstart' in window) || (window.DocumentTouch && document instanceof DocumentTouch)) ? 'touchstart' : 'click';

if(foo1 != 'click'){



$( document ).ready(function() {


$(document).on('click touchstart',function(event){ 
	
		
	if(!$("#hider-label").is(':visible')){
	
	    if ((!$(event.target).is('ul#nav a') && !$(event.target).is('ul#nav a img'))||$(event.target).is('a.navbar-brand')){
		
	 	$('ul#nav ul').hide();
	 	$('ul#nav li').removeClass( "hovering" );
		$('ul#nav a').removeClass( "hovered" );
		
	       
	    }else{
		
		   event.preventDefault();
		 $('ul#nav ul').show();  
		 $( event.target ).parent('li').siblings('li').removeClass( "hovering" );
		if( $( event.target ).parent('li').children('ul').length === 0 ){
			
			window.location.href = $(event.target).attr('href');  
				
		}else if( $( event.target ).parent('li').children('ul').length > 0 && !$(event.target).parent('li').hasClass( "hovering" )){
			
			
		   	 $( event.target ).parent('li').addClass( "hovering" );
		   	 
		}else if( $( event.target ).parent('li').children('ul').length > 0 && $(event.target).parent('li').hasClass( "hovering" )){
			window.location.href = $(event.target).attr('href');  
			
		
		}
	
	    
}
}



}); 



});
}




