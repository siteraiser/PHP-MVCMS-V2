function validateEmail(email){
	return /^[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}$/.test(email) && /^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/.test(email);
}	
// Attach a submit handler to the form
$( "#subscribe" ).submit(function( event ) {
 
  // Stop form from submitting normally
  event.preventDefault();
 
  // Get some values from elements on the page:
  var $form = $( this ),
    term = $form.find( "input[name='email']" ).val(), 
	first = $form.find( "input[name='first']" ).val(), last = $form.find( "input[name='last']" ).val(),
    url = $form.attr( "action" );
 if(validateEmail(term)){
  // Send the data using post
  var posting = $.post( url, { email: term, 'first':first, 'last':last } );
 
  // Put the results in a div
  posting.done(function( data ) {
    var content = $( data ).filter( "#content" );
	 ga('send', {
            'hitType' : 'pageview',
            'page' : '/subscribed' 
        });
	//Virtual page (aka, does not actually exist) that you can now track in GA Goals as a destination page.		
		
    $( "#result" ).empty().append(content);
  });
 }else{
	return false;	 
 }
});

