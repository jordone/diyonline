@extends('wizard')

@section('wizardtitle')
Forms are being updated!
@stop
@section('step') data-step-number="step9" @stop
@section('wizardcontent')

	<h4 class="info-text">Update complete!</h4>
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'paymentupdated', 'class' => 'form-horizontal ajaxcheck upgradecompletedform')) }}
	@stop
  
		@include('errors')
  		
		
		<div class="still_processing">
		<!-- lets give them some explaination that we have to wait for the DOE system to update the payment status before they can leave this page. -->
		<p>Your Loan Repayment Forms are being regenerated and will be sent to you via email. Please come back here if you wish to update your forms again. Thank you!</p>
	
		
		</div>
		
 	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop

	 <script>
	 var prevElem = jQuery('*[data-step-number="step9"]').ScrollTo().prev(":first");
	 jQuery('.wizard-content',prevElem).slideUp();
	 </script>

@stop