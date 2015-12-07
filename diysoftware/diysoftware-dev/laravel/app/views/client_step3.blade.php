@extends('wizard')

@section('wizardtitle')
STEP 3: Personal Information
@stop
@section('step') data-step-number="step3" id="step3" @stop
@section('wizardcontent')

	<h4 class="info-text">The following information is needed in order to access your Federal Student Loans directly from the Department of Education!</h4>
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'step3f', 'class' => 'form-horizontal ajaxcheck')) }}
	@stop
  
		@include('errors')
  
	  <div class="form-group">
	    <label for="dob" class="col-sm-3 control-label">Your Date of Birth</label>
	    <div class="col-sm-9"><input type="text" name="dob" class="form-control" id="dob" placeholder="Enter your Date of Birth (mm/dd/yyyy)" value="{{{ $Name1DOB }}}" required></div>
	  </div> 
	  
	  @if (!$HasSSN)
	  <div class="form-group">
	    <label for="ssn" class="col-sm-3 control-label">Social Security number</label>
	    <div class="col-sm-9"><input type="text" name="ssn" class="form-control" id="ssn" placeholder="Enter your Social Security number" required></div>
	  </div> 
	  @else
	  <div class="form-group">
	    <label for="ssn" class="col-sm-3 control-label">Last 4 of your SSN</label>
	    <div class="col-sm-2"><input type="text" name="ssn" class="form-control" id="ssn2" placeholder="Enter the 4 digits of your ssn" required></div>
	  </div> 
	  @endif
<!--	  <div class="form-group">
	    <label for="address1" class="col-sm-3 control-label">Home Address</label>
	    <div class="col-sm-9"><input type="text" name="address1" class="form-control" id="address1" placeholder="Enter your Home Address" value="{{{ $HomeAddress }}}" required></div>
	  </div> 
	  
	   	  <div class="form-group">
	    <label for="city" class="col-sm-3 control-label">City</label>
	    <div class="col-sm-2"><input type="text" name="city" class="form-control" id="city" placeholder="City" value="{{{ $City }}}" required></div>
	    <label for="state" class="col-sm-1 control-label">State</label>
	    <div class="col-sm-2"><input type="text" name="state" class="form-control" id="state" placeholder="State" value="{{{ $State }}}" required></div>
	    <label for="zip" class="col-sm-1 control-label">Zip</label>
	    <div class="col-sm-2"><input type="text" name="zip" class="form-control" id="zip" placeholder="Zip Code" value="{{{ $Zipcode }}}" required></div>
	  </div> -->
	   
	  
	  <div class="form-group">
	  	<input type="checkbox" class="formsubmitter checkbox-inline" id="checkbox3" name="checkbox3"><label for="checkbox3" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to STEP 4</label>
	  </div>	  
 	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop

	 <script>

	 var prevElem = jQuery('*[data-step-number="step3"]').ScrollTo().prev(":first");
//	 jQuery('.wizard-content',prevElem).slideUp();

$("#dob").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
$("#ssn").mask("999-99-9999");
$("#ssn2").mask("9999", {placeholder:"*"});

	 
	 </script>

@stop