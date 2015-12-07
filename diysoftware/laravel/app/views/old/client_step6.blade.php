@extends('wizard')
@section('wizardtitle')
STEP 6: ADDITIONAL SERVICES
@stop
@section('step') data-step-number="step6" @stop
 
@section('wizardcontent')

	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'step6f', 'class' => 'form-horizontal ajaxcheck updatecartprices')) }}
	@stop
  	  
	  <h3 style="text-align:center;">These additional {{COMPANY_NAME}} are listed below. These are optional, please check which forms you would like included in your order.</h3>
	  
	  
	  <!-- Check if there is any errors -->
	  @if (Session::get('ERROR_STEP6'))
	  <div class="alert alert-danger" role="alert">{{{ Session::get('ERROR_STEP6') }}}</div>
	  @endif	
	  
	  @include('errors')  
	  
	  

	  
	  
	  <!-- PROGRAMS !!!! -->
	  @if ($LoanType == 'consolidate')
	  <!-- Consolidated Plans -->

		@include('products.consolidation_note')
		
		@include('products.recertification_app')
	  	  
		@if ($PublicService)
		<!-- Public service only shows if we've got $PublicServer variable set to true -->
			@include('products.pslf_app')
		 <!-- End Public service only -->
		@endif
	  
 		@include('products.forebearance_app')
	  
	  
	  @else
	  <!-- REPAYMENT Plan -->
	  
		@include('products.repayment_note')
		@include('products.recertification_app')
		

		@if ($HasForbearance)
	 		@include('products.forebearance_app')
		@endif
		
		@if ($PublicService)
		  <!-- Public service only -->
			@include('products.pslf_app')
		  <!-- End Public service only -->
		@endif
	  
	  @endif
	  
		  
	  
	    
	  <div class="form-group">
	  	<input type="checkbox" class="formsubmitter checkbox-inline" id="checkbox6" name="checkbox6"><label for="checkbox6" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to STEP 7</label>
	  </div>
	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop
	 
	 <script>
	 var prevElem = jQuery('*[data-step-number="step6"]').ScrollTo().prev(":first");
	 //	 jQuery('.wizard-content',prevElem).slideUp();
	 </script>

@stop
