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
			<div class="panel-heading"><b>Custom Payment Options</b> <span class="pull-right"><img src="/packages/wizard/images/lock.png" align="absmiddle"> This page is secured with SSL Encryption</span></div>
			<div class="panel-body">
		
			  	<div class="row">
			  		<div class="col-sm-3" style="text-align:right;line-height:30px;">Total Amount Due: &dollar;</div>
			  		<div class="col-sm-3">
						@if ($orderpay == 'yes')
						<input type="text"  class="form-control"  name="tprice" id='Tprice' style="float:left" value="{{ $TotalDue }}" >
						@else
						<input type="text" class="form-control" name="tprice" id='Tprice'  style="float:left"  value="{{ $TotalDue }}" >
						@endif 
						<span class="numerror" style="display:none;color:red;margin-top:5px;">Please enter a valid number!</span>
					</div>
			  		<div class="col-sm-1"><small>USD</small></div>
			  	</div>
			  	<br/>
		
				@if (true)
				<!-- PRZ if (Session::get('auth_office_use')) -->
				<div class="row" style="padding-top:10px;">
					<div class="col-sm-8">
						<div class="panel panel-success">
							<div class="panel-heading">{{ OFFICE_LINK_B_NAME }}</div>
							<div class="panel-body">
							
							
								<div class="fixed_payments_b">

								<?php
								$number_payments = intval(OFFICE_LINK_B_NUM_PAYMENTS);
								$payment_amount_default = number_format($TotalDue/$number_payments,2);
								$duedate_constant = "OFFICE_LINK_B_PAYMENT%s_DEFAULT_DATE";

								// this is the last default due.
								if (defined(sprintf($duedate_constant , 2 )))
								$last_default_due = constant(sprintf($duedate_constant , 2));
								else
								$last_default_due = '+1 month';

								$due_date_now = time();

								for($i=0;$i<$number_payments;$i++)
								{
									//determin the due date
									if (defined(sprintf($duedate_constant , ($i+1) )) && constant(sprintf($duedate_constant , ($i+1) )))
									{
										$default_due = constant(sprintf($duedate_constant , ($i+1) ));
										$last_default_due = $default_due;
									}
									else
									{
										$default_due = $last_default_due;
									}
									$due_date_now = strtotime($default_due,$due_date_now);
									$due_date = date('m/d/Y', $due_date_now);


									?>
									<div class="form-group">
										<label class="control-label col-sm-3">Amount<br/><span class="text-success">Payment #{{ ($i+1) }}</span></label>
										<div class="col-sm-4">		
											<input type="text"  class="payment_amount form-control" name="fixed_payments_price_{{ ($i+1) }}" value="{{ $payment_amount_default }}"> 
											<div class="amounterror hide text-danger">You must enter a numeric value</div>
										</div>
										<div class="col-sm-5">
										    <div class="input-group date">
													<input value="{{ $due_date }}" name="fixed_payments_duedate_{{ ($i+1) }}" type="text" class="form-control"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
										    </div>
										</div>
									</div> 
									
								
									
									<?php
								}
								?>
								
								Total Amount &dollar;<span id="payment_amount_total">{{ $TotalDue }}</span><br/>
								<div id="payment_amount_total_error" class="hide text-danger"><b>The total amount being paid does not cover how much is owed. </b></div>
<script>
	if ( !(CurrentStepsLoaded & step7) )
		CurrentStepsLoaded = CurrentStepsLoaded | step7;

// We need to automatically calculate fields below the one they modified
$(".payment_amount").number( true, 2 );

$(".input-group.date input").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});

$('.payment_amount').on('keyup',function() {
	var amount_must_equal = parseFloat( jQuery('#Tprice').val() ).toFixed(2);

	
	var total = 0;
	var amount = $(this).val();
	
	if (parseFloat(amount) > parseFloat(amount_must_equal)) jQuery(this).val(amount_must_equal-1);
	var amount = $(this).val();

	var parent_formgroup = jQuery(this).parents('.form-group');
	
	var prev_payments = jQuery(parent_formgroup).prevAll('.form-group');
	var next_payments = jQuery(parent_formgroup).nextAll('.form-group');
	
	var number_more_payments = jQuery('.payment_amount', next_payments).length;	
	var prev_payments_amount = calculate_totals_from_elements( jQuery('.payment_amount',prev_payments) );

	
	if (number_more_payments >= 1)
	{
	// now change all of the others so they have an equal chance at this.
	var amount_needed = amount_must_equal - amount - prev_payments_amount;	
	
	jQuery('.payment_amount', next_payments).val( '$'+ (amount_needed / number_more_payments) );
	
	}
	
	
	
	
	if(isNaN(amount))
	{
		jQuery('.amounterror', parent_formgroup).removeClass('hide');
	}
	else{
		jQuery('.amounterror', parent_formgroup).addClass('hide');
	}


	var total = parseFloat ( calculate_totals_from_elements('.payment_amount') ).toFixed(2);
	console.log( (parseFloat(total) < parseFloat(amount_must_equal)) ? 'Payment not enough' : 'Payment good (Total: '+total+' need: '+amount_must_equal );
	if (parseFloat(total) < parseFloat(amount_must_equal))
	{
		jQuery('#payment_amount_total_error').removeClass('hide');
	}
	else 
	jQuery('#payment_amount_total_error').addClass('hide');
	$('#payment_amount_total').text(total);
});


function calculate_totals_from_elements(elements)
{
	total = 0.00;
	
	jQuery(elements).each(function() {
		total += parseFloat($(this).val());
	});
	return total.toFixed(2);
	
}

$('.input-group.date').datepicker({
	format: "mm/dd/yyyy",
	startDate: "08/08/2015",
	todayBtn: "linked",
	autoclose: true,
	todayHighlight: true,
	//        defaultViewDate: { year: 1977, month: 04, day: 25 }
});
</script>
								</div>
							</div>					
						</div>					
					</div>
				</div>
				
				 @endif
				<div class="collapse in" id="creditcard_section">
				<hr>

				<!-- Billing Information Panel -->
				<!-- Billing Information Panel -->
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
				
				<h3 class="page-title">Credit Card Information</h3>
<br/>
			  		
			  		<div class="form-group">
			  			<label class="control-label col-sm-3">Card Number</label>
			  			<div class="col-sm-9"><input type="text" name="card_number" class="form-control" placeholder="Card Number (no spaces or dashes)" required></div>
			  		</div>
			  		
			  		<div class="form-group">
			  			<label for="billing_name" class="control-label col-sm-3">Name on Card</label>
			  			<div class="col-sm-9"><input type="text" id="billing_name" name="billing_name" class="form-control" placeholder="Name as it appears on your credit card." required></div>
			  		</div>
			  		
			  		<div class="form-group">
			  			<label class="control-label col-sm-3">Expiration</label>
			  				<div class="col-sm-6">
			  					<select name="expiration_month" class="form-control">
			  						<option value="1">1 - January</option>
			  						<option value="2">2 - February</option>
			  						<option value="3">3 - March</option>
			  						<option value="4">4 - April</option>
			  						<option value="5">5 - May</option>
			  						<option value="6">6 - June</option>
			  						<option value="7">7 - July</option>
			  						<option value="8">8 - August</option>
			  						<option value="9">9 - September</option>
			  						<option value="10">10 - August</option>
			  						<option value="11">11 - November</option>
			  						<option value="12">12 - December</option>
			  					</select>
			  				</div>
			  				<div class="col-sm-3">
			  					<select name="expiration_year" class="form-control">
			  						@foreach ($exp_years as $exp_year)
			  						<option value="{{{ $exp_year }}}">{{{ $exp_year }}}</option>
			  						@endforeach
			  					</select>
			  				</div>
			  		</div>
			  		
			  		
			  		
			  		<div class="form-group">
			  			<label class="control-label col-sm-3">CVV</label>
			  			<div class="col-sm-2"><input type="text" name="cvv" class="form-control" placeholder="CVV" required></div>
			  		</div>
			  					  		
			  					  		
			  		</div>
<!--					<a href="#creditcard_section" data-toggle="collapse" class="btn btn-warning">Show Credit Card Form</a>-->
			</div>
		</div>

	    
	    <div class="form-group">
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