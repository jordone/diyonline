@extends('wizard')
@section('wizardtitle')
{{ STEP4_TITLE }}
@stop
@section('step') data-step-number="step124" @stop
 
@section('wizardcontent')

	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'select_forms_post', 'class' => 'form-horizontal ajaxcheck updatecartprices')) }}
	@stop
  	  
	  <h3 style="text-align:center;">Please select which Application(s) you would like to apply for:</h3>
	   
	  
	  @include('errors')  
	  
	  

	 <?php
	 $Products_HidePrices = true;
	 ?>
	 
	   
	 <div id="selectonlyone">

		@include('products/consolidation_note')
		
		@include('products/recertification_app')
	  	  
		@include('products/pslf_app')
	  
 		@include('products/forebearance_app')
	  	  
	</div>

	<script>
		jQuery('#selectonlyone input[type="checkbox"]').on('change',function(){
			jQuery('#selectonlyone input[type="checkbox"]').not(this).not('.paid_already').attr('checked', false);
			jQuery('#checkbox_showforms').attr('checked', false);
			
		});
	
	</script> 
	     
	  <div class="form-group">
	  	<input type="checkbox" class="formsubmitter checkbox-inline" id="checkbox_showforms" name="checkbox_showforms"><label for="checkbox_showforms" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to STEP 5</label>
	  </div>
	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop
	 
	 <script>
	 var prevElem = jQuery('*[data-step-number="step124"]').ScrollTo().prev(":first");
	 //	 jQuery('.wizard-content',prevElem).slideUp();
	  
	 // remove other forms after this one so they get positioned correctly.
	 jQuery('*[data-step-number="step4"]').remove();
	 jQuery('*[data-step-number="step5"]').remove();
	 jQuery('*[data-step-number="step6"]').remove();
	 jQuery('*[data-step-number="step7"]').remove();
	 </script>

@stop
