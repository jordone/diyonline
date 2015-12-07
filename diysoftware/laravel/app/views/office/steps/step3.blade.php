@extends('wizard', array('stepnav' => true, 'class' => 'navbar-info text-info'));

@section('wizardtitle')
{{ STEP3_TITLE }}
@stop
@section('step') data-step-number="step3" id="step3" @stop
@section('wizardcontent')
	<h4 class="text-center">{{ STEP3_DISPLAY_TEXT }}</h4>
	<h3 class="text-center">The following information is needed in order to access your Federal Student Loans directly from the Department of Education!</h3>
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'step3f', 'class' => 'form-horizontal ajaxcheck')) }}
	@stop
  
		@include('errors')
  
	  <div class="form-group">
	    <label for="dob" class="col-sm-3 control-label">Your Date of Birth</label>
	    <div class="col-sm-9"><input type="text" name="dob" class="form-control" id="dob" placeholder="Enter your Date of Birth (mm/dd/yyyy)" value="{{{ $client->TProperties->Name1DOB }}}" required></div>
	  </div>

	<div class="form-group">
		<label for="ssn" class="col-sm-3 control-label">Last 4 of your SSN</label>
		<div class="col-sm-2"><input type="text" name="ssn" class="form-control" id="ssn2" placeholder="Enter the 4 digits of your ssn" value="{{ $client->TProperties->Name1SSN }}" required></div>
	</div>


	  <div class="form-group">
	    <label for="address1" class="col-sm-3 control-label">Mailing Address</label>
	    <div class="col-sm-9"><input type="text" name="address1" class="form-control" id="address1" placeholder="Enter your Home Address" value="{{{ $client->TProperties->AddressLine1 }}}" required></div>
	  </div> 
	  
	   <div class="form-group">
	    <label for="city" class="col-sm-3 control-label">City</label>
	    <div class="col-sm-2"><input type="text" name="city" class="form-control" id="city" placeholder="City" value="{{{ $client->TProperties->City }}}" required></div>
	    <label for="state" class="col-sm-1 control-label">State</label>
	    <div class="col-sm-2"><input type="text" name="state" class="form-control" id="state" placeholder="State" value="{{{ $client->TProperties->State }}}" required></div>
	    <label for="zip" class="col-sm-1 control-label">Zip</label>
	    <div class="col-sm-2"><input type="text" name="zip" class="form-control" id="zip" placeholder="Zip Code" value="{{{ $client->TProperties->ZipCode }}}" required></div>
	  </div> 
	  	  
	  <div class="form-group">
          <div class="col-sm-offset-3 col-sm-9">
             <p class="text-danger"> Please enter your permanent address (number, street, apartment number, or rural route number and box number, then city, state, and zip code). MUST BE A PHYSICAL ADDRESS (P.O BOXES WILL NOT BE ACCEPTED) </p>
          </div>
        </div>
	  <div class="form-group hide">
	  	<input type="checkbox" class="formsubmitter checkbox-inline" id="checkbox3" name="checkbox3"><label for="checkbox3" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to STEP 4</label>
	  </div>
 	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop

	 <script>
		 if ( !(CurrentStepsLoaded & step3) )
			 CurrentStepsLoaded = CurrentStepsLoaded | step3;

	 var prevElem = jQuery('*[data-step-number="step3"]').ScrollTo().prev(":first");
//	 jQuery('.wizard-content',prevElem).slideUp();

$("#dob").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
$("#ssn").mask("999-99-9999");
$("#ssn2").mask("9999", {placeholder:"*"});

	 
	 </script>

@stop