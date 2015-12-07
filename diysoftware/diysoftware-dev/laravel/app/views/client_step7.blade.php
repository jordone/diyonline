@extends('wizard')

@section('wizardtitle')
STEP 7: Payment Information
@stop
@section('step') data-step-number="step7" @stop
@section('wizardcontent')

<!--	<h4 class="info-text">This information is relayed to Department of Education (DOE)!<small><br/>Access a list of all your Federal Student Loans that will be consolidated into your new re-payment plan options.</small></h4>-->
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'step7f', 'class' => 'form-horizontal')) }}
	@stop
  	
		@include('errors')
	
		<div class="panel panel-default paypanel">
			<div class="panel-heading"><b>Pay with credit card</b> <span class="pull-right"><img src="/packages/wizard/images/lock.png" align="absmiddle"> This page is secured with SSL Encryption</span></div>
			<div class="panel-body">

			
			  	<div class="row">
			  		<div class="col-sm-3" style="text-align:right;line-height:30px;">
			  		Total Amount Due:
			  		</div>
			  		<div class="col-sm-2"><input type="text" class="form-control" value="${{{ $TotalDue }}}" readonly></div>
			  		<div class="col-sm-1"><small>USD</small></div>
			  	</div>
			  	
				<hr>
				
				<!-- Billing Information Panel -->
				<h3 class="page-title">Mailing Address</h3>
				
				<div class="form-group">
					<label class="control-label col-sm-3">First Name</label>
					<div class="col-sm-9"><input class="form-control" name="Mailing_Address_first_name" placeholder="First Name" required></div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-sm-3">Last Name</label>
					<div class="col-sm-9"><input type="text" class="form-control" name="Mailing_Address_last_name" placeholder="Last Name" required></div>
				</div>				
				<div class="form-group">
					<label class="control-label col-sm-3">Address</label>
					<div class="col-sm-9"><input type="text" class="form-control" name="Mailing_Address" placeholder="Address" required></div>
				</div>				
				<div class="form-group">
					<label class="control-label col-sm-3">City</label>
					<div class="col-sm-9"><input type="text" class="form-control" name="Mailing_Address_city" placeholder="City" required></div>
				</div>				
				<div class="form-group">
					<label class="control-label col-sm-3">Country</label>
					<div class="col-sm-9"><select name="Mailing_Address_country" class="form-control">
			  				<option value="US">United States</option>
			  			</select></div>
				</div>				
				<div class="form-group">
					<label class="control-label col-sm-3">State/Province</label>
					<div class="col-sm-9"><input type="text" class="form-control" name="Mailing_Address_state" placeholder="State" required></div>
				</div>				
				<div class="form-group">
					<label class="control-label col-sm-3">Zip/Postal Code</label>
					<div class="col-sm-9"><input type="text" class="form-control" name="Mailing_Address_zipcode" placeholder="Zip Code" required></div>
				</div>

				<div class="form-group">
					<label class="control-label col-sm-3">Email</label>
					<div class="col-sm-9"><input type="email" class="form-control" name="Mailing_Address_email" placeholder="Email Address" required></div>
				</div>
				
				<!-- Billing Information Panel -->
				<h3 class="page-title">Billing Information <div class="pull-right"><input type="checkbox" value="1" name="sameasmailing" id="SameAsMailing"> <label for="SameAsMailing" class="label-checkbox" style="line-height:40px;font-size:16px;">Same as Mailing Address</label>&nbsp;&nbsp;</div></h3>
				<div class="billing_fields">
					<div class="form-group">
						<label class="control-label col-sm-3">First Name</label>
						<div class="col-sm-9"><input class="form-control" name="Billing_first_name" placeholder="First Name"></div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-sm-3">Last Name</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Billing_last_name" placeholder="Last Name"></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">Address</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Billing_address" placeholder="Address"></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">City</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Billing_city" placeholder="City"></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">Country</label>
						<div class="col-sm-9"><select name="Billing_country" class="form-control">
				  				<option value="US">United States</option>
				  			</select></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">State/Province</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Billing_state" placeholder="State"></div>
					</div>				
					<div class="form-group">
						<label class="control-label col-sm-3">Zip/Postal Code</label>
						<div class="col-sm-9"><input type="text" class="form-control" name="Billing_zipcode" placeholder="Zip Code" ></div>
					</div>
	
					<div class="form-group">
						<label class="control-label col-sm-3">Email</label>
						<div class="col-sm-9"><input type="email" class="form-control" name="Billing_email" placeholder="Email Address" ></div>
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
		</div>
	
  
		<center><u><b>Terms &amp; CONDITIONS</b></u></center>
		<center>
		By Submitting this Form You Are Agreeing To These Terms and Conditions! <br/>
		You Give Permission To DIY To Charge/Debit Your Account For the Amount Indicated On The Total Due Line Above.
		You Have Read And Understand The Disclosures Contained On The DIY Website.
		</center>
	    
	    
	    <div class="form-group">
	    	<center><input type="submit" name="action_checkout" value="SUBMIT" class="btn btn-success"></center> <br/>
	    	<center>* Please click submit once, the following page will load confirming your payment. After, you'll be notified on that page once your payment has been completed.</center>
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
	 </script>

@stop