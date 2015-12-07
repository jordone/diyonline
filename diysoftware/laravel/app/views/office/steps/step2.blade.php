@extends('wizard', array('stepnav' => true, 'class' => 'navbar-info text-info'));
@section('wizardtitle')
{{ STEP2_TITLE }}
@stop 
@section('step') data-step-number="step2" id="imstep2" @stop
@section('wizardcontent')

<h4 class="text-center">{{ STEP2_DISPLAY_TEXT }}</h4>

<h4 class="text-center"> This information is needed to calculate your NEW Federal Student Loan Re-Payment Options. The next few questions are based on your <b>last year's tax return filings</b> </h4>
<h4 class="text-center"> If you did not file a Federal Income Tax return for the two most recently completed tax years or your AGI from your most recently filed federal income tax return does not reasonably reflect your current income (due to circumstances such as the loss of or change in employment),  put your anticipated income for the current year. You must be able to provide supporting documentation (example: pay stubs or a letter from your employer) </h4>
<h4 class="text-center"> Family Size:  includes you, your spouse, and your children (including unborn children who will be born during the year for which you certify your family size), if the children will receive more than half their support from you. It includes other people only if they live with you now, they receive more than half their support from you now, and they will continue to receive this support from you for the year that you certify your family size. Support includes money, gifts, loans, housing, food, clothes, car, medical and dental care, and payment of college costs. </h4>


	@section('wizardform_open')
		{{ Form::open(array('url' => 'step2f',  'class' => 'form-horizontal ajaxcheck')) }}
	@stop
  
  	@include('errors')
  	  
  
  <center>Are you <label><input type="radio" name="married" value="0" class="married_radio_status" {{ $client->TProperties->MaritalStatus != 'Married' ? 'checked="checked"' : '' }} required> Single</label> or 
	  <label><input type="radio" name="married" value="1" class="married_radio_status" {{ $client->TProperties->MaritalStatus == 'Married' ? 'checked="checked"' : '' }}  required> Married</label></center>
  <br/>
  
  <div class="show_married_0 form-group" style="display:none;">
  	<label class="col-sm-4 control-label" for="single_agi">Your Annually Adjusted Gross Income (AGI)</label>
  	<div class="col-sm-8">
		<div class="input-group">
			<span class="input-group-addon">$</span><input type="text" name="single_agi" id="single_agi" class="form-control" value="{{{ $client->TProperties->Income_Yearly }}}"  required>
		</div>
	</div>
  </div>  
  
  <div class="show_married_1 form-group" style="display:none;">
	  <label class="col-sm-5 control-label" for="filestatus">Did you file jointly on your last tax return?</label>
 	  <div class="col-sm-7"><select class="form-control married_filed_jointly" name="married_filed_jointly"><option value="no" {{ $client->TProperties->TaxFilingStatus == 'Married Filing Separately' ? 'selected="selected"' : '' }}>No</option><option value="yes" {{ $client->TProperties->TaxFilingStatus == 'Married Filing Jointly' ? 'selected="selected"' : '' }}>Yes</option></select></div>
  </div>
    
  <div class="show_filed_jointly_yes form-group" style="display:none;">
  	<label class="col-sm-5 control-label" for="single_agi">Your combined Adjusted Gross Income (AGI)</label>
  	 <div class="col-sm-7">
  	   	<div class="input-group">
			<span class="input-group-addon">$</span><input type="text" name="married_agi" class="form-control" value="{{{ $client->TProperties->CoIncome_Yearly }}}">
		</div>
	</div>
  </div>
  <div class="show_filed_jointly_no form-group" style="display:none;">
  	<label class="col-sm-4 control-label" for="single_agi">Your Adjusted Gross Income (AGI)</label>
  	<div class="col-sm-8">
  		<div class="input-group">
			  <span class="input-group-addon">$</span><input type="text" name="single_agi2" class="form-control" value="{{{ $client->TProperties->Income_Yearly }}}">
		</div>
	</div>
  </div>
  
  <br/>
  
  <div class="show_married_1" style="display:none">
	  <div class="form-group">
	    <label for="SpousesName_f" class="col-sm-5  control-label">Spouses First Name</label>
	    <div class="col-sm-7"><input type="text" name="spouses_first_name" class="form-control" id="SpousesName_f" placeholder="Enter your Spouses First Name" value="{{{ $client->TProperties->CoFirstName }}}" ></div>
	  </div>   
	  
	  <div class="form-group">
	    <label for="SpousesName_l" class="col-sm-5 control-label">Spouses Last Name</label>
	    <div class="col-sm-7"><input type="text" name="spouses_last_name" class="form-control" id="SpousesName_l" placeholder="Enter your Spouses Last Name"  value="{{{ $client->TProperties->CoLastName }}}"></div>
	  </div>   
	  
	  <div class="form-group">
	    <label for="SpousesSSN" class="col-sm-5 control-label">Spouses SSN</label>
	    <div class="col-sm-7"><input type="text" name="spouses_ssn" class="form-control" id="SpousesSSN" placeholder="Enter your Spouses SSN"  value="{{{ $client->TProperties->CoSSN }}}"></div>
	  </div>
	  
	  <div class="form-group">
	    <label for="SpousesDob" class="col-sm-5 control-label">Spouses Date of Birth</label>
	    <div class="col-sm-7"><input type="text" name="spouses_dob" class="form-control" id="SpousesDob" placeholder="Enter your Spouses Date of Birth"  value="{{{ $client->TProperties->CoDOB }}}"></div>
	  </div> 
  </div>
  
  <div class="form-group">
    <label for="Dependents" class="col-sm-7 control-label">Number of Dependents that you filed for on your last tax return including yourself(1)?</label>
    <div class="col-sm-5"><input type="text" name="dependents" class="form-control" id="Dependents" placeholder="Number of dependents that you filed on your last tax return"  value="{{{ $client->TProperties->FamilySize }}}"  required></div>
  </div> 
  
  <br/>
  <font color="red">Based on the employment information you provide, your Federal Student Loans may qualify for FORGIVENESS.
  The Public Service Loan Forgiveness Program (PSLF) provides for the forgiveness of the remaining balance of a borrower's eligible loans after the borrower has made 120 payments on those loans</font>

  <div class="form-group">
    <label for="" class="col-sm-5 control-label">Do you work in the Public Service Field?</label>
    <div class="col-sm-7"><select name="public_service" id="public_service_select" class="form-control"><option value="0" {{ $client->TProperties->PublicService != 'Yes' ? 'selected="selected"' : '' }}>No</option><option value="1" {{ $client->TProperties->PublicService == 'Yes' ? 'selected="selected"' : '' }}>Yes</option></select></div>
  </div> 
  
  <div class="form-group public_service_yes" style="display:none">
    <label for="" class="col-sm-4 control-label">If yes please select:</label>

    <div class="col-sm-8"><select name="public_service_jobname" class="form-control">
			
    <?php $Occupation = $client->TProperties->Occupation; ?>
			
    <option {{ $Occupation == 'Army' ? 'selected="selected"' : '' }}>Army</option>
    <option {{ $Occupation == 'Navy' ? 'selected="selected"' : '' }}>Navy</option>
    <option {{ $Occupation == 'Marine Corps' ? 'selected="selected"' : '' }}>Marine Corps</option>
    <option {{ $Occupation == 'Air Force' ? 'selected="selected"' : '' }}>Air Force</option>
    <option {{ $Occupation == 'Coast Guard' ? 'selected="selected"' : '' }}>Coast Guard</option>
    <option {{ $Occupation == 'Pre / Elementary / Middle / High School / College Teachers' ? 'selected="selected"' : '' }}>Pre / Elementary / Middle / High School / College Teachers</option>
    <option {{ $Occupation == 'Police Officer' ? 'selected="selected"' : '' }}>Police Officer</option>
    <option {{ $Occupation == 'Nursing field' ? 'selected="selected"' : '' }}>Nursing field</option>
    <option {{ $Occupation == 'EMT' ? 'selected="selected"' : '' }}>EMT</option>
    <option {{ $Occupation == 'Firefighters' ? 'selected="selected"' : '' }}>Firefighters</option>
    <option {{ $Occupation == 'State Attorney' ? 'selected="selected"' : '' }}>State Attorney</option>
    <option {{ $Occupation == 'Park Rangers' ? 'selected="selected"' : '' }}>Park Rangers</option>
    <option {{ $Occupation == 'Public Transportation Worker' ? 'selected="selected"' : '' }}>Public Transportation Worker</option>
    <option {{ $Occupation == 'Public Library Services' ? 'selected="selected"' : '' }}>Public Library Services</option>
    <option {{ $Occupation == 'Social Service Worker' ? 'selected="selected"' : '' }}>Social Service Worker</option>
    <option {{ $Occupation == 'Non-Profit Organizations' ? 'selected="selected"' : '' }}>Non-Profit Organizations</option>
    <option>Other</option>
    </select>
    </div>
  </div>   
  
  	  <div class="form-group">
	  	<input type="checkbox" class="formsubmitter checkbox-inline" id="checkbox2" name="checkbox2"><label for="checkbox2" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to STEP 3</label>
	  	
	  </div>
  
  	  <!-- back button -->
  
	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop
	 
<script>
		CurrentStepsLoaded = CurrentStepsLoaded | step2;


// Married options
jQuery('.married_radio_status').change(function(){
	showHideMarriedOptions();
});

var beforeelemet= jQuery('#imstep2').prev();
jQuery('.wizard-content',beforeelemet).slideUp();

/** This function shows or hides the married questions. **/
function showHideMarriedOptions()
{
	var value = jQuery('.married_radio_status:checked').val();
	if (value == 1)
	{
		jQuery('.show_married_0').hide();
		jQuery('.show_married_1').show();
		showHideFiledJointly();
	}
	else
	{
		jQuery('.show_married_0').show();
		jQuery('.show_married_1').hide();
		showHideFiledJointly();
	}
}
/** It has to run the first time, incase they have an option set.**/
showHideMarriedOptions();




// Did they file jointly or seperately?
jQuery('.married_filed_jointly').change(function(){
	showHideFiledJointly();
});



function showHideFiledJointly()
{
	var value = jQuery('.married_filed_jointly').val();
	//if the marriage option is set to no, we hide all jointly questions
	var is_married = jQuery('.married_radio_status:checked').val();

	if (is_married != 1)
	{

		jQuery('.show_filed_jointly_no').hide();
		jQuery('.show_filed_jointly_yes').hide();
	}
	else
	{
		if (value == 'no')
		{
			//show_filed_jointly_no
			jQuery('.show_filed_jointly_no').show();
			jQuery('.show_filed_jointly_yes').hide();
		}
		if (value == 'yes')
		{
			//show_filed_jointly_no
			jQuery('.show_filed_jointly_no').hide();
			jQuery('.show_filed_jointly_yes').show();
		}
	}
}

//do they work in the public sector
jQuery('#public_service_select').change(function(){
	showHidePublicServiceOptions();
}).trigger('change');

function showHidePublicServiceOptions()
{
	var value = jQuery('#public_service_select').val();

	if (value == 1)
	{
		jQuery('.public_service_yes').show();
	}
	else
	{
		jQuery('.public_service_yes').hide();
	}
}


// scroll to this class!
//var prevElem = jQuery('*[data-step-number="step2"]').ScrollTo().prev();
//
//if (!prevElem.data('hidden'))
//{
//	jQuery('.wizard-content',prevElem).slideUp();
//	prevElem.data('hidden', true);
//
//}
//else
//{
//
//}
</script>	 



@stop