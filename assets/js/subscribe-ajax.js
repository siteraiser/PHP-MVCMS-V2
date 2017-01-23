function validateEmail(email){
	return /^[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}$/.test(email) && /^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/.test(email);
}
$( "#subscribe" ).submit(function( event ) {
  event.preventDefault();
 
  var $form = $( this ),
    term = $form.find( "input[name='email']" ).val(), 
	first = $form.find( "input[name='first']" ).val(), last = $form.find( "input[name='last']" ).val(),
    url = $form.attr( "action" );
 if(validateEmail(term)){
  
  var posting = $.post( url, { email: term, 'first':first, 'last':last } );
 
  posting.done(function( data ) {
    var content = $( data ).filter( "#content" );
	 ga('send', {
            'hitType' : 'pageview',
            'page' : '/subscribed' 
        });
		
    $( "#result" ).empty().append(content);
  });
 }else{
	return false;	 
 }
});

