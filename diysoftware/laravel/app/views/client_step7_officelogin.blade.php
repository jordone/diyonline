@extends('wizard')

@section('wizardtitle')
{{ Session::get('checkout_title') }}
@stop
@section('step') data-step-number="step7" @stop
@section('wizardcontent')

<!--	<h4 class="info-text">This information is relayed to Department of Education (DOE)!<small><br/>Access a list of all your Federal Student Loans that will be consolidated into your new re-payment plan options.</small></h4>-->
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'step7f', 'class' => 'form-horizontal whitebackground')) }}
	@stop
  	
		@include('errors')

<style>
.whitebackground .content {
	background-color:#fff !important;
}
</style>
				
		<center><img src="/packages/static/congrats.png" class="img-responsive"></center>
	  	<br/>
	  	
	  	
			<div class="form-group text-center">
				<div class="col-sm-3">
					<a href="#" onclick="LoadStep('step7'); return false" class="btn btn-success"><i class="glyphicon glyphicon-menu-left"></i> Checkout</a>
				</div>

				<div class="pull-right col-sm-2">

				@if (OFFICE_LINK_ENABLED == 'yes')
				
				@include('office_use_login')
				
				@endif
				
				</div>
				
			</div>
		</div>
		
	  	<center><font color="#125280" style="font-size:24px;">A process that can normally take hours to complete has just been completed</font>
	  	<br/></center>
		
		<div class="panel panel-default paypanel">
			<div class="panel-heading"><b>Office use login</b> <span class="pull-right"><img src="/packages/wizard/images/lock.png" align="absmiddle"> This page is secured with SSL Encryption</span></div>
			<div class="panel-body">
		
			
				<div class="form-group">
			       		<div class="col-sm-3">Password</div>
					 	<div class="col-sm-5"><input type="text" id="officepasswd" class="form-control"></div>
					 	<div class="col-sm-2"><button type="button" id="verify_office_pw" data-loading-text="Logging in" id="verify_office_pw" class="btn btn-primary">Login</button>      		
				</div>
				
				

			
			</div>
		</div>
		</div>

 	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop

	 <script>
	 jQuery('#SameAsMailing').on('change', function(){
	 	var checked = jQuery(this).is(':checked');
	 	if (checked) {
	 		jQuery('.billing_fields').hide();
	 	}
	 	else jQuery('.billing_fields').show();
	 }).trigger('change');
	 var prevElem = jQuery('*[data-step-number="step7"]').ScrollTo().prev(":first");
	 //	 jQuery('.wizard-content',prevElem).slideUp();

	 jQuery('#Tprice').on('keyup',function(){
	 	// jQuery('#checkbox5').prop('checked',true).trigger('change');
	 	var tprice = jQuery(this).val();
	 	if(isNaN(tprice))
	 	{
	 		jQuery('.numerror').show();
	 	}
	 	else{
	 		jQuery('.numerror').hide();
	 	}


	 });


	 /** Monitor for Office password enter presses **/
	 jQuery('#officepasswd').keypress(function(e) {
	 	if(e.which == 13) {
	 		e.preventDefault();
	 		e.stopPropagation();
	 		
	 		setTimeout(function(){ jQuery('#verify_office_pw').button('reset'); }, 2000);
	 		jQuery('#verify_office_pw').button('loading');

	 		LoadStep('office_login?np=login&p='+jQuery('#officepasswd').val());
	 		// post the password, and show them it's loading. Wonder how to do that.
	 		return false;
	 	}
	 });

	 jQuery('#verify_office_pw').click(function(e) {
	 	e.stopPropagation();
	 	jQuery('#verify_office_pw').button('loading');

	 	setTimeout(function(){ jQuery('#verify_office_pw').button('reset'); }, 2000);

	 		LoadStep('office_login?np=login&p='+jQuery('#officepasswd').val());

	 });


	 /** Do not auto collapse drop downs if you're using an input field **/
	 $('.dropdown input, .dropdown label,.dropdown button').click(function(e) {
	 	e.stopPropagation();
	 });

	 <?php


	 if (Session::get('checkout_title') == 'Step 5: Checkout')
	 {
	 	?>
	 	jQuery('*[data-step-number="step4"]').remove();
	 	jQuery('*[data-step-number="step5"]').remove();
	 	jQuery('*[data-step-number="step6"]').remove();
	 	<?php
	 }
	 ?>

	 </script>

@stop