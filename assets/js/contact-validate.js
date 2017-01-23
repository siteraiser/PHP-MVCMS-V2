$( document ).ready(function() {
	
	$('button[name="action"]').prop("disabled",true);
	
	function validateEmail(email){
		return /^[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}$/.test(email) && /^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/.test(email);
	}	
	function success(event){
		$(event.target).parent().removeClass( "has-warning" ).addClass( "has-success" );	
		$(event.target).removeClass( "form-control-warning" ).addClass( "form-control-success" );	
	}	
	function warning(event){
		$(event.target).parent().removeClass( "has-success" ).addClass( "has-warning" );	
		$(event.target).removeClass( "form-control-success" ).addClass( "form-control-warning" );		
	}

	function validate(event){
		if($(event.target).val() !=''){
			if($(event.target).attr("id") == "email"){
				if(validateEmail($(event.target).val())){
					success(event);	
				}else{
					warning(event);
				}	
			}else{
				success(event);				
			}
		}else{
			warning(event);
		}	
		
		var errors = [];
		$('#name,#email,#message').each(function () {
			if((validateEmail($(this).val()) && $(this).attr("id") == "email") || ($(this).val() !='' && $(this).attr("id") != "email")){					
			}else{
				errors.push('error');
			}
				
		});

		if(errors.length == 0){
			$('button[name="action"]').prop("disabled",false);
			return true;
		}else{
			$('button[name="action"]').prop("disabled",true);
			return false;
		}		
	}
	$('body').on('keyup change blur','#name,#email,#message',function(event){	
		var valid = validate(event);	
		if(event.keyCode == 13 && !valid){
		   event.preventDefault();
		   return false;
		}
	});
});

