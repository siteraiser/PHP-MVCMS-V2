  var rowcount = 0;
  var rowids = '';
$(document).ready(function(){
		function restartSortable(){
	 //   rowids = '';
	//	rowcount = $('#sortable').children().size();	

	//	for ( var i = 0, l = rowcount; i < l; i++ ) {
		//	rowids = rowids + "#sortable-row-" + i + ",";
		//}
		//rowids = rowids.substring(0, rowids.length - 1);

		$( '.sortable-row' ).sortable({
		 update: function( event, ui ) {
			},
		  connectWith: ".connectedSortable"
		});//.disableSelection();
		
		//alert('restart');
		$( ".bootup" ).sortable({
		 update: function( event, ui ) {
			},
		  connectWith: ".bootup"
		});
	}
	addButtons();
	restartSortable();
   
   
   
   $(document).on( "click", '.add',function() {
   	 var scrns = $('#scrnsize').text();
	 if(this.parentNode.dataset["col" + scrns] < 12){++this.parentNode.dataset["col" + scrns]}
 // alert( this.parentNode.dataset["col" + scrns]);
	$(this).parent().removeClass( "col-" + scrns + "-0 col-" + scrns + "-1 col-" + scrns + "-2 col-" + scrns + "-3 col-" + scrns + "-4 col-" + scrns + "-5 col-" + scrns + "-6 col-" + scrns + "-7 col-" + scrns + "-8 col-" + scrns + "-9 col-" + scrns + "-10 col-" + scrns + "-11  col-" + scrns + "-12" ).addClass( "col-" + scrns + "-" + this.parentNode.dataset["col" + scrns ] );

  });
  
 $(document).on( "click",'.remove',function() {
 	 var scrns = $('#scrnsize').text();
   	 if(this.parentNode.dataset["col" + scrns] > 1){--this.parentNode.dataset["col" + scrns]}
	$(this).parent().removeClass( "col-" + scrns + "-0 col-" + scrns + "-1 col-" + scrns + "-2 col-" + scrns + "-3 col-" + scrns + "-4 col-" + scrns + "-5 col-" + scrns + "-6 col-" + scrns + "-7 col-" + scrns + "-8 col-" + scrns + "-9 col-" + scrns + "-10 col-" + scrns + "-11  col-" + scrns + "-12" ).addClass( "col-" + scrns + "-" + this.parentNode.dataset["col" + scrns ] );


  });
  
 function getPrevClass(elem){ 
var classList = $(elem).parent().attr('class').split(/\s+/);
var i=0;
var retClass=false;


 $.each(classList, function(index, item) {
	//alert(item.split('-')[1]);
	var classes = item.split('-');
//console.log(classes.join(', '));
    if ( classes[2] === 'pull' || classes[2] === 'push' && scrns == classes[1]) {
        //do something
		var ppClasses = bps[bps.indexOf(classes[1])];
		//alert(bps.indexOf(ppClasses)+'<-'+ classes[2]);
		i = bps.indexOf(ppClasses);
		
		//console.log('i-'+i-);
		for (; 0 < i; ) { //console.log(classes[2]+'-'+classes[1]);
			if(bps[i] ==  classes[1]){
				retClass = classes[2];console.log(classes[2]+'-'+classes[1]);
				break;
			}
			i--;
		}
		
		return false;
		
		
    }
	
});

return retClass;//bps[i];


}
 var bps = ["xs","sm","md","lg","xl"];
     
   $(document).on( "click", '.right',function() {
   	 var scrns = $('#scrnsize').text();
	 if(this.parentNode.dataset["shift" + scrns] < 12){
		 	$(this).parent().css("position", "");
			$(this).parent().css("top", "");
			$(this).parent().css("left", "");
			if(this.parentNode.dataset["shift" + scrns] !=''){
				var first = false;
				++this.parentNode.dataset["shift" + scrns];
				
			}else{
				this.parentNode.dataset["shift" + scrns] = 0;
				var first = true;
				}
		  if( this.parentNode.dataset["shift" + scrns] >= 0 || this.parentNode.dataset["shift" + scrns] == "" ){
			var shiftmode = "push";
						
			if(this.parentNode.dataset["shift" + scrns] == 0 || this.parentNode.dataset["shift" + scrns] == "" ){	
				//shiftmode = "pull";

				$(this).parent().removeClass( "col-" + scrns + "-pull-1" );
				
				shiftmode = "push"; 
				var behindClass = getPrevClass(this);//$(this).parent().hasClass(bps[bps.indexOf(scrns)-1]).toString();
				if(behindClass){	
					
					var shiftmode =behindClass;
					if( first == true){$(this).parent().addClass( "col-" + scrns + "-"+shiftmode+"-0");}
				}else{
					
					$(this).parent().removeClass( "col-" + scrns + "-push-0 col-" + scrns + "-pull-0" );
				}
		  }else{		  $(this).parent().removeClass( "col-" + scrns + "-push-0" );}
			
		  }else{
			  var shiftmode = "pull";
			  
		  }
	}
	//if(shiftmode==right){}
 // alert( this.parentNode.dataset["col" + scrns]);

	$(this).parent().removeClass( "col-" + scrns + "-"+shiftmode+"-1 col-" + scrns + "-"+shiftmode+"-2 col-" + scrns + "-"+shiftmode+"-3 col-" + scrns + "-"+shiftmode+"-4 col-" + scrns + "-"+shiftmode+"-5 col-" + scrns + "-"+shiftmode+"-6 col-" + scrns + "-"+shiftmode+"-7 col-" + scrns + "-"+shiftmode+"-8 col-" + scrns + "-"+shiftmode+"-9 col-" + scrns + "-"+shiftmode+"-10 col-" + scrns + "-"+shiftmode+"-11  col-" + scrns + "-"+shiftmode+"-12" ).addClass( "col-" + scrns + "-"+shiftmode+"-" + Math.abs(this.parentNode.dataset["shift" + scrns ]) );

  });
  
  
     $(document).on( "click", '.left',function() {
		var scrns = $('#scrnsize').text();
		if(this.parentNode.dataset["shift" + scrns] > -12){ 

		 	$(this).parent().css("position", "");
			$(this).parent().css("top", "");
			$(this).parent().css("left", "");
			if(this.parentNode.dataset["shift" + scrns] !=''){
				--this.parentNode.dataset["shift" + scrns];
				var first = false;
			}else{
				var first = true;
				this.parentNode.dataset["shift" + scrns] = 0;
			}
		  if( this.parentNode.dataset["shift" + scrns] <= 0 || this.parentNode.dataset["shift" + scrns] == "" ){
			var shiftmode = "pull";
			
			if(this.parentNode.dataset["shift" + scrns] == 0 || this.parentNode.dataset["shift" + scrns] == "" ){	
				//shiftmode = "pull";
				$(this).parent().removeClass( "col-" + scrns + "-push-1" );
				shiftmode = "pull";
			
			var behindClass = getPrevClass(this);//$(this).parent().hasClass(bps[bps.indexOf(scrns)-1]).toString();
				if(behindClass){		
				
					var shiftmode =behindClass;//alert(behindClass);	
					if(first == true){$(this).parent().addClass( "col-" + scrns + "-"+shiftmode+"-0");}
				}else{
					
					$(this).parent().removeClass("col-" + scrns + "-push-0 col-" + scrns + "-pull-0"  );
				}
			}else{		  $(this).parent().removeClass( "col-" + scrns + "-pull-0" );}		  
			
		  }else{
			  var shiftmode = "push";
			  
		  }
	}
	//if(shiftmode==right){}
 // alert( this.parentNode.dataset["col" + scrns]);

	$(this).parent().removeClass( "col-" + scrns + "-"+shiftmode+"-1 col-" + scrns + "-"+shiftmode+"-2 col-" + scrns + "-"+shiftmode+"-3 col-" + scrns + "-"+shiftmode+"-4 col-" + scrns + "-"+shiftmode+"-5 col-" + scrns + "-"+shiftmode+"-6 col-" + scrns + "-"+shiftmode+"-7 col-" + scrns + "-"+shiftmode+"-8 col-" + scrns + "-"+shiftmode+"-9 col-" + scrns + "-"+shiftmode+"-10 col-" + scrns + "-"+shiftmode+"-11  col-" + scrns + "-"+shiftmode+"-12" ).addClass( "col-" + scrns + "-"+shiftmode+"-" + Math.abs(this.parentNode.dataset["shift" + scrns ]) );

  });
  
  
  
  
  
  
 function innerSortStart(elem){
  restartSortable();

 	$(elem).html(editmode);
	$( ".bootup" ).sortable('destroy');$(  '.sortable-row'  ).sortable('destroy'); 
    $(elem).parent().children('.content').addClass("editmode");
	$(elem).parent().children('.content').attr("id","droppable");
    $( ".draggable" ).draggable({
        connectToSortable: "#droppable",
		helper: "clone",
		revert: "invalid"
    });
	$( ".draggableb" ).sortable('destroy');
	
	$( ".draggableb" ).draggable({
        connectToSortable: "#droppable",
		revert: "invalid"
    });
		
	$( "#droppable > .draggableb" ).draggable('destroy');
	
    $( "#droppable" ).droppable({
      drop: function( event, ui ) {
	  if($( ui.draggable ).hasClass("draggable") && !$( ui.draggable ).hasClass("draggableb")&& !$( ui.draggable ).hasClass("ui-sortable-helper")){
	//  alert($( ui.draggable ).attr('class')+"\n"+$( ui.draggable ).attr('id')); ///
		$( elem ).append( '<div class="draggableb content">' + ui.draggable.html()+"</div>" );   //<div id="div-'+ divids++ +'" class="draggableb content">' + ui.draggable.html()+"</div>
		restartSortableDiv();
	}else{
		$( ui.draggable ).removeClass("draggable ui-draggable-handle ui-draggable").addClass("draggableb content");
		$( ui.draggable ).removeAttr('style');
	//	$( ui.draggable ).attr("id",'div-' + divids++);
				//$( ui.draggable ).append('<span class="editcontent2">Edit</span>');
	}
//ui.draggable.remove();

      }
    });
 } 
  

function editStart(elem){	

$(elem).html(editmode);
	
	//restartSortable();
//$( "#sortable" ).sortable('destroy');
//$( rowids ).sortable('destroy'); 	
restartSortableDiv();
$( ".content" ).sortable('destroy');
$( ".draggable" ).draggable('destroy');
$( "#droppable" ).droppable('destroy');
	$(elem).parent().children('.content').attr("id","");	
$( ".draggableb" ).sortable('destroy');
$( ".draggableb" ).draggable('destroy');

	
	
//  $('#sortable').html($('#load').val());

} 

function sortStart(elem){	

		$(elem).html(editmode);
	restartSortable();
	$(elem).parent().children('.content').removeClass("editmode");	
//	$(elem).parent().children('.content').attr("id",""); 
	$( ".draggable" ).draggable('destroy');
	$( "#droppable" ).droppable('destroy');

	restartSortableDiv();
} 
  

var divids=0;
var editmode = 'sorting';
$(document).on( "click",'.editcontent',function() {

/*
 var highest=[];
  $('.draggableb.content').each(function() {
  var divid = $(this).attr("id").split('-');
  if( divid[1] > -1){
	highest[highest.length] = divid[1];   
	}
  });
  var maximum=0;
highest.forEach(function(value){
  if(value > maximum) {
    maximum = value;
  }
});
  divids = maximum;
*/
  

   if(editmode == 'sorting'){ 	
	restartSortableDiv();  
	editmode = 'innersort';
	innerSortStart(this);
 }else if(editmode == 'innersort'){			
 
		editmode = 'editing';
		editStart(this);

   }else if(editmode == 'editing'){		
		editmode = 'sorting';
		sortStart(this);
   }
   
	

//alert('clicked');
 /* editable = $(this).parent().children('.content').attr('contenteditable');
  $(this).parent().children('.content').attr('contenteditable', !(editable == 'true'));
 CKEDITOR.inline($(this).parent().children('.content').attr('id'));
 */

});


//var rowdivcount = 0;var rowdivids='';
	
	
	function restartSortableDiv(){

		$(".content" ).sortable({
		 update: function( event, ui ) {
			},
		  connectWith: ".content"
		});//.disableSelection();
		
		
		
	}

function setSize(){
	if($( document ).width() > 1200){
			$('#scrnsize').html('xl');
		}else if($( document ).width() > 992){
			$('#scrnsize').html('lg');
		}else if($( document ).width() > 768){
			$('#scrnsize').html('md');
		}else if($( document ).width() > 575){
			$('#scrnsize').html('sm');
		}else{		
			$('#scrnsize').html('xs');
		}
}


$('.tooltoggle').click(function() {
 $(".left,.right,.add,.remove,.delete,.editcontent,.editcontent2,.row,.row > div,.draggableb, .editablel2 > a.edit-it,.editablel2 > .delete-n").toggleClass( "hidden" );
  if($(this).text() == 'On'){
	$(".tooltoggle").each(function(){
		$(this).text("Off");
	});
 }else{ 
	$(".tooltoggle").each(function(){
	  $(this).text("On");
	});
}
});

$('.copyhtml').click(function() {
	
	if(editmode=='sorting'){
		
		var id = $(this).attr("id").substring(7);
	
		$( "div.bottom-tools").each(function() { $(this).remove(); });	
		$("div.draggableb").removeAttr( "style");
		$('#sortableInputID' + id).clone().appendTo('#outbox' + id);
		
	
	/*
	$('#outbox').find( "a.edit-it,div.editablel2 .delete-n").each(function() {
		$(this).remove();
	});
	*/
$('#outbox' + id).find( "span.editcontent,span.left,span.right,span.add,span.remove,span.delete").each(function() {
		$(this).remove();
	});


	$('#outbox' + id).find( "div.bootup").each(function() {
		$(this).removeClass( "ui-sortable ui-sortable-handle ui-draggable-handle");
	});

		$('#loadInputID' + id).val($('#outbox' + id + ' > div.bootup').html());
		$('#outbox' + id).empty();
	}
	
	

//
});
$('.addhtml').click(function() {
	var InputID = $(this).attr("id");
	//$(  '.content'  ).sortable('destroy'); 
$( ".bootup" ).sortable('destroy');
//$( ".content" ).sortable('destroy');
$(  '.sortable-row'  ).sortable('destroy'); 
//$( ".draggableb" ).draggable('destroy');
$( "#droppable" ).droppable('destroy');
  $('#sortable' + InputID).html($('#load' +  InputID).val());
  addButtons();
  restartSortable();
  	restartSortableDiv();
});




    $(window).resize(function() {
	setSize();

    });
	
setSize();	





$(document).on( "click",'.editcontent2',function() {


	if($(this).parent().children('.content').attr("id")!='editing'){
		$(this).parent().children('.content').attr("id","editing"); 
		$(this).text('editing');
		CKEDITOR.inline('editing',{ filebrowserBrowseUrl :'/filemanager_in_ckeditor/js/ckeditor/filemanager/browser/default/browser.html?Connector=/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
                    filebrowserImageBrowseUrl : '/filemanager_in_ckeditor/js/ckeditor/filemanager/browser/default/browser.html?Type=Image&Connector=/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
                    filebrowserFlashBrowseUrl :'/filemanager_in_ckeditor/js/ckeditor/filemanager/browser/default/browser.html?Type=Flash&Connector=/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/connector.php',
					filebrowserUploadUrl  :'/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/upload.php?Type=File',
					filebrowserImageUploadUrl : '/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/upload.php?Type=Image',
					filebrowserFlashUploadUrl : '/filemanager_in_ckeditor/js/ckeditor/filemanager/connectors/php/upload.php?Type=Flash'
					
				,toolbarGroups: [
					{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
{ name: 'about' },
	
			],
			//htmlEncodeOutput: true,
			
			extraAllowedContent: 'a(documentation);abbr[title];code',
					//removePlugins: 'stylescombo',
					extraPlugins: 'sourcedialog',
					startupFocus: true,
			extraDialogTabs:'image:advanced;link:advanced'});
	//	alert('edit-'+ $(this).parent().children('.content').text());
	}else{
		CKEDITOR.instances.editing.destroy();
		$(this).parent().children('.content').attr("id","");
		$(this).text('edit');
		//alert('done editing-'+ $(this).parent().children('.content').text());
	}
    editable = $(this).parent().children('.content').attr('contenteditable');
   $(this).parent().children('.content').attr('contenteditable', !(editable == 'true'));
 //$(this).parent().children('.draggableb.content').attr('id')
});



$(document).on( "click",'.delete,.delete-n',function() {
$(this).parent().remove();

});


$(document).on( "click",'span.clone',function() {
$(this).parent().parent(".draggableb").clone().insertAfter($(this).parent().parent(".draggableb")).find( "div.bottom-tools:last" ).remove();


});

$(document).on( "click",'span.delete-section',function() {
$(this).parent().parent(".draggableb").remove()
});
CKEDITOR.on( 'dialogDefinition', function( ev ) {
    // Take the dialog name and its definition from the event data.
    var dialogName = ev.data.name;
    var dialogDefinition = ev.data.definition;

    // Check if the definition is from the dialog window you are interested in (the "Link" dialog window).
    if ( dialogName == 'link' ) {
        // Get a reference to the "Link Info" tab.
        var infoTab = dialogDefinition.getContents( 'info' );

        // Set the default value for the URL field.
        var urlField = infoTab.get( 'url' );
        urlField[ 'default' ] = 'www.siteraiser.com';
    }
});



//Saving / Updating
$(document).on( "click",'.save',function() {
if(editmode=='sorting'){
	var id = this.dataset["id"]
	var artid = this.dataset["article"];
	 if(1){
	$( "div.bottom-tools").each(function() { $(this).remove(); });	

		$("div.draggableb").removeAttr( "style");

	$('#sortableInputID' + id).clone().appendTo('#outbox' + id);
		
	$('#outbox' + id).find( "div.editablel2").each(function() {
		$(this).replaceWith( "[article[" + $(this).data("id") + "]]");
	});
	/*
	$('#outbox').find( "a.edit-it,div.editablel2 .delete-n").each(function() {
		$(this).remove();
	});
	*/
$('#outbox' + id).find( "span.editcontent,span.left,span.right,span.add,span.remove,span.delete").each(function() {
		$(this).remove();
	});


	$('#outbox' + id).find( "div.bootup").each(function() {
		$(this).removeClass( "ui-sortable ui-sortable-handle ui-draggable-handle");
	});
	var html =  $('#outbox' + id + ' > div.bootup').html();
	var tex = html.replace(/[\u00A0-\u2666]/g, function(c) {
	   return '&#'+c.charCodeAt(0)+';';
	});
	  // Send the data using post
	  var posting = $.post( '/siteadmin/ajaxupdate', { 'id': artid, 'content':tex});
	 
	  // Put the results in a div
	  posting.done(function( data ) {
		var message = $( data ).filter( "#content" );
	$('#outbox' + id).empty();
		$( "#result" ).empty().append(message);
	  });
	 }else{
		return false;	 
	 }
 
}else{alert('still editing, not saved');}
});





$(document).on( "mouseenter",'.draggableb', function() {
    $( this ).append( $( "<div class='bottom-tools'><span class='clone'>Clone</span>|<span class='delete-section'>Delete</span></div>" ) );
  }
);
 $(document).on( "mouseleave",'.draggableb', function() {
    $( this ).find( "div.bottom-tools:last" ).remove();
  }
);




function addButtons(){
	$('<span class="editcontent">sorting</span><span class="left">&lt;</span><span class="right">&gt;</span><span class="add">+</span><span class="remove">-</span><span class="delete">X</span>').insertAfter('.connectedSortable > div > div.content');
}






});
