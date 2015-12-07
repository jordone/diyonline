@extends('wizard')

@section('wizardtitle')
Processing your payment!
@stop
@section('step') data-step-number="step8" @stop
@section('wizardcontent')

	<h4 class="info-text">Please wait while we process your payments.</h4>
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'paymentupdate', 'class' => 'form-horizontal ajaxcheck processpayment')) }}
	@stop
  
		@include('errors')
  		
		
		<div class="still_processing">
		<!-- lets give them some explaination that we have to wait for the DOE system to update the payment status before they can leave this page. -->
			<p>We are processing your payment and generating the forms for you. Please <b>DO NOT LEAVE THIS PAGE!</b></p>		
			<center><img src="/packages/wizard/images/loaders/spinner_squares_circle.gif"> Processing, this may take a few minutes!</center>
		</div>
		
		<script>
		var processpayment = function() {
			
			// trigger this form to post, it is a fake post!
			jQuery('form.processpayment:first').trigger('submit');
			
		}
		
		// setTimeout to processpayment
		// There system processes credit cards on every 1 minute.
		var now = new Date();
		var seconds = now.getSeconds();
		
		// next update is 60-seconds * 1000
		var nextcheck = (60-seconds) * 1000	
		
		// set a timeout to do that
		setTimeout(processpayment, nextcheck);
		
		</script>
		
 	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop

	 <script>jQuery('*[data-step-number="step8"]').ScrollTo();</script>

@stop