@extends('wizard', array('stepnav' => false, 'class' => 'navbar-info text-info'))

@section('wizardtitle')
{{ STEP3_TITLE }}
@stop
@section('step') data-step-number="step3" id="step3" @stop
@section('wizardcontent')

    @include('snipplets.steps_header_text', ['step' => 'STEP3'])

	<h3> The following information is needed in order to access your Federal Student Loans directly from the Department of Education.</h3>
	

@section('wizardform_open')
    @if (isset($update_enabled) && $update_enabled)
        {{ Form::open(array('url' => 'step3f', 'class' => 'form-horizontal ajaxcheck')) }}
    @else
        <div class="form form-horizontal">
    @endif
@endsection


		@include('errors')
  
	  <div class="form-group">
	    <label for="dob" class="col-sm-3 control-label">Your Date of Birth</label>
	    <div class="col-sm-5"><input  pattern="[^\!\@\#\$\%\^\&\*\<;\>]*"  type="text" name="dob" class="form-control" id="dob" placeholder="Enter your Date of Birth (mm/dd/yyyy)" value="{{ $client->TProperties->Name1DOB }}" required></div>
	  </div>

	<div class="form-group">
		<label for="ssn" class="col-sm-3 control-label">Last 4 of your SSN</label>
		<div class="col-sm-5"><input  pattern="[^\!\@\#\$\%\^\&\*\<;\>]*"  type="text" name="ssn" class="form-control" id="ssn2" placeholder="Enter the 4 digits of your ssn" value="{{ $client->TProperties->Name1SSN }}" required></div>
	</div>


	  <div class="form-group">
	    <label for="address1" class="col-sm-3 control-label">Mailing Address</label>
	    <div class="col-sm-9"><input  pattern="[^\!\@\#\$\%\^\&\*\<;\>]*"  type="text" name="address1" class="form-control" id="address1" placeholder="Enter your Home Address" value="{{ $client->TProperties->AddressLine1 }}" required></div>
	  </div>

    <div class="form-group">
        <label for="city" class="col-sm-3 control-label">City</label>
        <div class="col-sm-2"><input type="text" name="city" class="form-control" id="city" placeholder="City" value="{{{ $client->TProperties->City }}}" required></div>
        <label for="state" class="col-sm-1 control-label">State</label>
        <div class="col-sm-2">
            {{ Form::stateSelect('state', $client->TProperties->State, ["class" => "form-control required"]) }}
        </div>

        <label for="zip" class="col-sm-1 control-label">Zip</label>
        <div class="col-sm-2"><input type="text" name="zip" class="form-control" id="zip" placeholder="Zip Code" value="{{{ $client->TProperties->ZipCode }}}" required></div>
    </div>

    <div class="form-group">
          <div class="col-sm-offset-3 col-sm-9">
             <p class="text-danger"> Please enter your permanent address (number, street, apartment number, or rural route number and box number, then city, state, and zip code). MUST BE A PHYSICAL ADDRESS (P.O BOXES WILL NOT BE ACCEPTED) </p>
          </div>
        </div>

@section('wizardform_close')
    @if (isset($update_enabled) && $update_enabled)
        @include('steps.partials.submit')
        {{ Form::close() }}
        @else
        </div>
    @endif
@endsection

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