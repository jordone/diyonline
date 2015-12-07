@extends('wizard')

@section('wizardtitle')
Payment complete
@stop
@section('step') data-step-number="step9" @stop
@section('wizardcontent')

	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'paymentupdate', 'class' => 'form-horizontal ajaxcheck processpayment')) }}
	@stop
  
		@include('errors')
  		
		<h2 class="page-title">Success!</h2>
		<center><font color="green">Your payment has been completed</font> &amp; we have generated the forms for you. These forms will be emailed to you.</center>
		
		<h3>What's next?</h3>
		<p>Simple! Print the generated forms and follow the instructions we have sent to you.</p>
		
		<script>
		//remove the processing dialog.
		jQuery('*[data-step-number="step8"]').remove();
		
		</script>
		
 	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop

	 <script>jQuery('*[data-step-number="step9"]').ScrollTo();</script>

@stop