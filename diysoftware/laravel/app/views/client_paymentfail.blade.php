@extends('wizard')

@section('wizardtitle')
Payment Failed
@stop
@section('step') data-step-number="step9" @stop
@section('wizardcontent')

	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'step7', 'class' => 'form-horizontal ajaxcheck processpayment')) }}
	@stop
  
		@include('errors')
  		
		<h2 class="page-title">Payment Failed!</h2>
		<center><font color="red">Your payment has failed.</font> Please <a href="/step7">click here to update your credit card information</a></center>
		
		<script>
		//remove the processing dialog.
		jQuery('*[data-step-number="step8"]').remove();
		
		</script>
		
 	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop

	 <script>jQuery('*[data-step-number="step9"]').ScrollTo();</script>

@stop