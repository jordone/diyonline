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
		<div class="row text-center">
			<div class="form-group">
				<div class="col-sm-3">
					<a href="#" onclick="LoadStep('step7'); return false" class="btn btn-info"><i class="glyphicon glyphicon-menu-left"></i> Single Payment</a>
				</div>


				<div class="col-sm-9 text-right">

				@if (OFFICE_LINK_ENABLED == 'yes')
				
				@include('office_use_login')
				
				@endif
				
				</div>
				
			</div>
		</div>
		
	  	<center><font color="#125280" style="font-size:24px;">A process that can normally take hours to complete has just been completed</font>
	  	<br/></center>
		
		<div class="panel panel-default paypanel">
			<div class="panel-heading"><b>{{ OFFICE_LINK_NOPAYMENT_NAME }} </b> <span class="pull-right"><img src="/packages/wizard/images/lock.png" align="absmiddle"> This page is secured with SSL Encryption</span></div>
			<div class="panel-body">
		 		<center>
		 		<p class="text-muted"><span class="text-warning">No charges will be made to generate forms.</span> <br/> To complete this order press the <b>Submit</b> button below.</p>
                <br/>

                    <div class="form-group">
                        <input type="hidden" name="np" value="{{ Input::get('np') }}">
                        <center><input type="submit" name="action_checkout" value="SUBMIT" class="btn btn-success"></center> <br/>

                    </div>

                </center>
		 		
<!--Show the Shipping information still.		 		-->
				<div class="collapse in" id="creditcard_section">
				<hr>				
	<h3 class="page-title">Billing Information </h3>

					<div class="form-group">
						<label class="control-label col-sm-3">First Name</label>
						<div class="col-sm-9"><input class="form-control" name="Billing_first_name" value="{{ $Firstname }}" placeholder="First Name" required></div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-3">Last Name</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Billing_last_name" value="{{ $Lastname }}"  placeholder="Last Name" required></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">Address</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Billing_address" placeholder="Address" value="{{ $AddressLine1 }}" required></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">City</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Billing_city" placeholder="City" value="{{ $City }}" required></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">Country</label>
						<div class="col-sm-9"><select name="Billing_country" class="form-control">
				  				<option value="US">United States</option>
				  			</select></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">State/Province</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Billing_state" placeholder="State" value="{{ $State }}" required></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">Zip/Postal Code</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Billing_zipcode" placeholder="Zip Code" value="{{ $Zipcode }}" required ></div>
					</div>
	
					<div class="form-group">
						<label class="control-label col-sm-3">Email</label>
						<div class="col-sm-9"><input type="email" class="form-control" name="Billing_email" placeholder="Email Address" value="{{ $EmailAddress }}" required></div>
					</div>
					
				<hr>
				<h3 class="page-title">Mailing Address <div class="pull-right">
				<input type="checkbox" value="1" name="sameasmailing" id="SameAsMailing" checked> <label for="SameAsMailing" class="label-checkbox" style="line-height:40px;font-size:16px;">Same as Billing Address</label>&nbsp;&nbsp;</div></h3>
				<div class="billing_fields">
					<div class="form-group">
						<label class="control-label col-sm-3">First Name</label>
						<div class="col-sm-9"><input class="form-control" name="Mailing_Address_first_name" placeholder="First Name"></div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-3">Last Name</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Mailing_Address_last_name" placeholder="Last Name"></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">Address</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Mailing_Address" placeholder="Address"></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">City</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Mailing_Address_city" placeholder="City"></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">Country</label>
						<div class="col-sm-9"><select name="Mailing_Address_country" class="form-control">
				  				<option value="US">United States</option>
				  			</select></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">State/Province</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Mailing_Address_state" placeholder="State"></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">Zip/Postal Code</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Mailing_Address_zipcode" placeholder="Zip Code"></div>
					</div>
	
					<div class="form-group">
						<label class="control-label col-sm-3">Email</label>
						<div class="col-sm-9"><input type="email" class="form-control" name="Mailing_Address_email" placeholder="Email Address"></div>
					</div>
				</div>
				</div>
		
				 		
			</div>
		</div>
	
		

	    
	    <div class="form-group">
	    	<input type="hidden" name="np" value="{{ Input::get('np') }}">
	    	<center><input type="submit" name="action_checkout" value="SUBMIT" class="btn btn-success"></center> <br/>
	    	<center>* Please click submit once, the following page will load confirming your payment.<br />After, you'll be notified on that page once your payment has been completed.</center>
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




	 </script>

@stop