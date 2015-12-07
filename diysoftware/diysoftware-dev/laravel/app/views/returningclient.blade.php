@extends('wizard')
@section('wizardtitle')
Returning Client: Enter your information
@stop
@section('step') data-step-number="returningclient" @stop


@section('wizardcontent')
	@section('wizardform_open')
		{{ Form::open(array('url' => 'returningclientf', 'class' => 'form-horizontal ajaxcheck')) }}
	@stop
      <br/>  
      <!-- Display an error message if the client cant be found -->
      @if (Session::get('SearchError'))
      <font color="Red"><b>Error</b> Sorry there was no account with the information you have provided.</font>
      @endif
      
<script src="https://www.google.com/recaptcha/api.js?onload=returningcustomers&render=explicit" async defer></script>
<script type="text/javascript">

recaptcha = false;

var returningcustomers = function() {

	grecaptcha.render(document.getElementById('amihuman1'), {
	'sitekey' : '6LdI9wATAAAAANT_6rPnIaMpVXhAGO8AhOu7H_eV'
	});

	if (jQuery('#amihuman2').length)
	{
		grecaptcha.render(document.getElementById('amihuman2'), {
		'sitekey' : '6LdI9wATAAAAANT_6rPnIaMpVXhAGO8AhOu7H_eV'
		});
	}
	recaptcha = grecaptcha;

};
</script>
</script>

	  <div class="form-group">
	   <label for="lastname" class="col-sm-3 control-label">Last Name</label>
	   <div class="col-sm-8"><input type="text" name="lastname" class="form-control" id="lastname" placeholder="Enter your Last Name"></div>
	  </div>
	  <div class="form-group">
	   <label for="emailreturning" class="col-sm-3 control-label">Email Address</label>
	   <div class="col-sm-8"><input type="text" name="email" class="form-control" id="emailreturning" placeholder="Enter your Email Address"></div>
	  </div>
	  
	  
		<div class="row">
		<div class="col-sm-3">Prove Human</div>
		<div class="col-sm-2"><div class="g-recaptcha" id="amihuman1" data-sitekey="6LdI9wATAAAAANT_6rPnIaMpVXhAGO8AhOu7H_eV"></div></div>
		
		</div>  
	  <div class="form-group">
	  	<div class="col-sm-3">&nbsp;</div>
	  	<div class="col-sm-2"><button type="submit" class="btn btn-danger" name="action_login">Continue</button></div>

	  </div>
	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop
	 
@stop


