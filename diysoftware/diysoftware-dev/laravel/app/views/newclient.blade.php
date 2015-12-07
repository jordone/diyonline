@extends('wizard')

@section('wizardtitle')
STEP 1: Contact Information
@stop
@section('step') data-step-number="step1" @stop

@section('wizardcontent')
	<h4 class="info-text"> This information is what DIY software relays to the Department of Education (DOE) so they can correspond with you</h4>
	
	@section('wizardform_open')
	{{ Form::open(array('url' => 'newclientform', 'class' => 'form-horizontal ajaxcheck')) }}
	@stop
	
	<!-- errors -->
	@include('errors')
<!--			{{{ $tracking_field_value }}}-->
	<div class="form-inline form-group">

		    <label class="col-sm-2 control-label" for="inputFirstname">First Name</label>
		    <div class="col-sm-4"><input type="text" name="first_name" class="form-control" id="inputFirstname" placeholder="First Name" value="{{ $FirstName }}" required></div>

			<label class="col-sm-2 control-label" for="inputLastname">Last Name</label>
			<div class="col-sm-4"><input type="text" name="last_name" class="form-control" id="inputLastname" placeholder="Last Name"  value="{{ $LastName }}" required></div>
	  
	</div>
	
	  <div class="form-group">
	    <label class="col-sm-2 control-label" for="InputPhone">Mobile Number</label>
	    <div class="col-sm-8"><input type="text" name="phone" class="form-control" id="InputPhone" placeholder="Enter phone number"  value="{{ $HomeNumber }}" required></div>
	  </div> 
	  
	  	
	  <div class="form-group">
	    <label class="col-sm-2 control-label"  for="exampleInputEmail1">E-Mail</label>
	   <div class="col-sm-10">
	   		<input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"  value="{{ $EmailAddress }}" required>
	    	<font color="red">This e-mail address is where DIY Services will send you your username and password </font>
	    </div>
	  </div>
	  

	  
	  
	  <div class="form-group">
	  	<label  class="col-sm-5 control-label"  for="leadsource" style="text-align:left;width: 35%">How did you hear about our DIY service?</label>
	  	<div class="col-sm-7"><select name="leadsource" class="form-control">
	  		<option value="">Please select an option</option>
	  		<option value="Twitter" {{ $LeadSource == 'Twitter' ? 'selected="selected"' : '' }}>Twitter</option>
	  		<option value="Facebook" {{ $LeadSource == 'Facebook' ? 'selected="selected"' : '' }}>Facebook</option>
	  		<option value="Email" {{ $LeadSource == 'Email' ? 'selected="selected"' : '' }}>Email</option>
	  		<option value="Email" {{ $LeadSource == 'Google Search' ? 'selected="selected"' : '' }}>Google Search</option>
	  	</select>
	  	</div>
	  </div>

	  @if ( !Session::get('fileNumber') )
	  @if (Request::Ajax())
<!--<script src="https://www.google.com/recaptcha/api.js?onload=showRecaptcha&render=explicit" async defer></script>-->
<?php
$randomcapacha = 'amihuman_'.uniqid().'';
?>	  
	 <div class="row">
	  	<label  class="col-sm-5 control-label"  for="leadsource" style="text-align:left;width: 35%">Prove Human</label>		
		<div class="col-sm-2 " id="captchadiv"><div class="g-recaptcha" id="<?php echo $randomcapacha; ?>" data-sitekey="6LdI9wATAAAAANT_6rPnIaMpVXhAGO8AhOu7H_eV"></div></div>

<script type="text/javascript">

function showRecaptcha() {

	grecaptcha.render(document.getElementById('<?php echo $randomcapacha; ?>'), {
	'sitekey' : '6LdI9wATAAAAANT_6rPnIaMpVXhAGO8AhOu7H_eV'
	});
//	
//	Recaptcha.create("6LdI9wATAAAAANT_6rPnIaMpVXhAGO8AhOu7H_eV", 'captchadiv', {
//		theme: 'white',
//		callback: Recaptcha.focus_response_field
//	});
}

showRecaptcha();
 </script>
 		
	  </div>  

	  @else
	 <div class="row">
	  	<label  class="col-sm-5 control-label"  for="leadsource" style="text-align:left;width: 35%">Prove Human</label>		
		<div class="col-sm-2"><div class="g-recaptcha" id="amihuman2" data-sitekey="6LdI9wATAAAAANT_6rPnIaMpVXhAGO8AhOu7H_eV"></div></div>

	  </div>  	  
	  
	  @endif

	  @endif
	  
	  <div class="form-group">
		
	  	<input type="checkbox" class="formsubmitter checkbox-inline" id="checkbox1" name="checkbox1"><label for="checkbox1" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to STEP 2</label>
	  </div>
	  
	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop
	 
	 <script>

	 $("#InputPhone").mask("(999) 999-9999")
	 </script>
	 
	 
@stop

