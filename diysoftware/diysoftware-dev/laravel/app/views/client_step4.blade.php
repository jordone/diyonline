@extends('wizard')
@section('wizardtitle')
STEP 4: FAFSA PIN
@stop
@section('step') data-step-number="step4" @stop

@section('wizardcontent')
	<h4 class="info-text">Personal Student Loan Identification Number</h4>
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'step4f', 'class' => 'form-horizontal ajaxcheck')) }}
	@stop
  
	@include('errors')
  
  <div class="form-group">
    <label for="fafsapin" class="col-sm-2 col-sm-offset-1 control-label">Your FAFSA Pin</label>
      <div class="col-sm-4"><input type="text" name="fafsa_pin" class="form-control" id="fafsapin" placeholder="Enter your FAFSA Pin"></div>
  </div> 
  
  <div class="row">
  	<div class="col-sm-offset-1">
  		<font color="red">This is required by the DOE to pull your loans &amp; generate your new repayment options.</font>
  	</div>
  </div>
 <br/>
  <div class="row">
  	<div class="col-md-1"  style="width:30px;">

  	</div>
  	<div class="col-md-8" style="">
  		  	
  	
  	    <input type="checkbox" name="request_duplicate_pin" class="checkbox-inline" value="1" id="duppinbox"><label for="duppinbox" style=""><span style="background-color: red; font-size:16px; color:#FFF;padding-left:15px;">CHECK THIS BOX TO REQUEST A DUPLICATE PIN (FAFSA).</span></label>
		<div class="pinlink duppin hidden">
  			TO REQUEST A DUPLICATE PIN CLICK <a href="https://pin.ed.gov/PINWebApp/PINServlet" target="_blank">HERE</a><br/>
  			ONCE THE PAGE OPENS SELECT <br/>
  			"REQUEST A DUPLICATE PIN" ON THE LEFT SIDE. <br/>
  			FILL OUT YOUR INFORMATION <br/>
  			<font color="RED"><b>VERY IMPORTANT!</b> PLEASE VERIFY YOUR INFORMATION IS CORRECT BEFORE YOU CLICK "SUBMIT REQUEST"</font><br/>
  			ONCE YOU'VE RECEIVED YOUR FAFSA PIN CLOSE <br/>
  			GOVERMENT SITE WINDOW AND ENTER YOUR PIN ABOVE<br/>
  			<font color="RED">IF "NO MATCH PAGE" COMES UP, RETURN BACK HERE &amp; CHECK THE BOX BELOW TO APPLY FOR A NEW PIN</font> 
  		</div>
  	</div>
  </div>
  <br/>
  <div class="row">
  	<div class="col-md-1" style="width:30px;">

  	</div>
  	<div class="col-md-8">
  	   <input type="checkbox" name="request_new_pin" value="1" id="newpinbox">
  		<label for="newpinbox"><span style="background-color: red; font-size:16px; color:#FFF;padding-left:15px;">CHECK THIS BOX TO REQUEST A NEW PIN (FAFSA).</span></label>
  		<div class="pinlink newpininfo hidden">
  			TO APPLY FOR A NEW PIN CLICK <a href="https://pin.ed.gov/PINWebApp/PINServlet" target="_blank">HERE</a><br/>
  			ONCE THE PAGE OPENS SELECT <br/>
  			"APPLY FOR A PIN" ON THE LEFT SIDE. <br/>
  			FILL OUT YOUR INFORMATION <br/>
  			<font color="RED"><b>VERY IMPORTANT!</b> PLEASE VERIFY YOUR INFORMATION IS CORRECT AS YOUR APPLYING FOR THE PIN</font><br/>
  			A NEW PIN WILL BE EMAILED TO YOU WITHIN 2-3 DAYS <br/>
  			ONCE YOU HAVE YOUR FAFSA PIN, COME BACK TO DIY STUDENT LOAN SERVICES WEBSITE.
			CLICK "<b>GET STARTED</b>" AND CLICK THE CHECK BOX FOR RETURNING CUSTOMER TO CONTINUE.  			
  		</div>
  	</div>
  </div>
  <br/>
  	  <div class="row">
  	  	<div class="col-sm-12">
  	  		<center>By requesting DIY document preparation service for assistance with your Federal Student Loan, you are agreeing to allow DIY and its authorized agents to access your profile and all data contained within the profile.</center>
  	  	</div>
  	  </div>
  	  <br/>
	  <div class="form-group">
	  	<input type="checkbox" class="formsubmitter checkbox-inline" id="checkbox4" name="checkbox4"><label for="checkbox4" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to STEP 5</label>
	  	<br/>
	  	
	  </div>
	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop
	 
	 
<script>
// Update the client status if they have chosen to have a new or duplicate pin.
// This also shows the 'pin box' information.
jQuery('#duppinbox').on('change',function(){
	var checked = jQuery(this).is(':checked');
	var form = jQuery(this).parents('form');
	if (checked)
	{
		// Send a Post Request to update the Status to FAFSA New Pin
		// Show the Dialog for the checkbox.
		var postUrl = '/updatestatus';
		var postFields = {'status': 'fafsaduppin', '_token': '{{ csrf_token() }}' };
		jQuery('.duppin').removeClass('hidden');

		jQuery.post(postUrl, postFields, function(data){

		});

	}
	else
	{
		jQuery('.duppin').addClass('hidden');
	}
});

jQuery('#newpinbox').on('change',function(){
	var checked = jQuery(this).is(':checked');
	var form = jQuery(this).parents('form');
	if (checked)
	{
		// Send a Post Request to update the Status to FAFSA New Pin
		// Show the Dialog for the checkbox.
		var postUrl = '/updatestatus';
		var postFields = {'status': 'fafsanewpin', '_token': '{{ csrf_token() }}' };
		jQuery('.newpininfo').removeClass('hidden');

		jQuery.post(postUrl, postFields, function(data){

		});

	}
	else
	{
		jQuery('.newpininfo').addClass('hidden');
	}
});

var prevElem = jQuery('*[data-step-number="step4"]').ScrollTo().prev(":first");

</script>	 
	 
	 
@stop


