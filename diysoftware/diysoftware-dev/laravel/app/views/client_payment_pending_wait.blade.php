@extends('wizard')

@section('wizardtitle')
Payment Pending!
@stop
@section('step') data-step-number="step12" id="step12" @stop
@section('wizardcontent')

	<h4 class="info-text">Whoops.</h4>
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'step12f', 'class' => 'form-horizontal ajaxcheck')) }}
	@stop
  
		@include('errors')
  
		<h1>Your Payment is pending, please come back later to upgrade your files.</h1>
 	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop

	 <script>

	 var prevElem = jQuery('*[data-step-number="step12"]').ScrollTo().prev(":first");
//	 jQuery('.wizard-content',prevElem).slideUp();

	 
	 </script>

@stop