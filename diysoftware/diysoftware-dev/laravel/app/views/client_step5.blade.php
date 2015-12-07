@extends('wizard')
@section('wizardtitle')
STEP 5: Contact Information
@stop
@section('step') data-step-number="step5" @stop

@section('wizardcontent')

	<h4 class="info-text"></h4>
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'step5f', 'class' => 'form-horizontal ajaxcheck')) }}
	@stop
	
  	@include('errors')

  	<table class="table table-striped">
	  	<thead>
	  		<tr>
		  		<th>Loan</th>
		  		<th>Balance</th>
		  		<th>Status</th>
	  		</tr>
	  	</thead>
	  	<tbody>
	  	
	  	@foreach($LoanData->Loans as $Loan)
	  	<tr>
	  		<td>{{{ $Loan->Creditor->Name }}}</td>
	  		<td>${{{ $Loan->Creditor->Balance }}}</td>
	  		<td>
	  		@if ($Loan->AccountStatus == 'L')
	  		Good
	  		@else
	  			@if ($Loan->AccountStatus == 'E' || $Loan->AccountStatus == 'K')
	  			Consolidated
	  			
	  			@else
					{{{ $Loan->AccountStatus }}}
	  			@endif
	  			
	  		@endif
	  		</td>
	  	</tr>
	  	@endforeach
	  	</tbody>
	  
	  </table>
  	  
	 
	  <font color="Red">Please choose the Re-payment Plan you wish to Select</font>
	  
	  <!-- Plans -->
	  <table class="table table-no-border">
	  	<thead>
	  		<tr>
	  			<th align="center">&nbsp;</th>
	  			<th align="center">Repayment <u>Plan</u></th>
	  			<th align="center">Months <u>In Repayment</u></th>
	  			<th align="center">Monthly <u>Payments</u></th>
	  		</tr>
	  	</thead>
	  	
	  	<tbody>
	  	
	  	@if ($Plans->ICR->Qualified)
	  	
	  		<tr>
	  			<td><input type="radio" name="repayment_plan" value="income_contingent"></td>
	  			<td>Income Contingent</td>
	  			<td>{{{ $Plans->ICR->Term }}}</td>
	  			<td>${{{ $Plans->ICR->Payment }}}</td>
	  		</tr>
			<tr class="explained hidden" id="explain_income_contingent">
				<td colspan="4">
				@if ($Plans->ICR->Payment == 0)<font color="Red">If you received a $0 payment due to your current financial situation, the Department of Education feels that it is not affordable for you to make monthly payments at this time. As a result you are able to enjoy the benefits of $0 payment until the Department of Education Deans that your income can support a monthly payment.</font>
				@endif
Income Contingent Repayment (ICR) Plan - Under this plan, your monthly payment amount will be based on your annual adjusted gross income (and that of your spouses if you are married), your family size, and the total amount of your Direct Loans. As your income changes, your payments may change. If you do not repay your loan after the term of months listed above, under this plan any unpaid interest or principal balance will be forgiven.
				</td>
			</tr>
		@endif
		
		@if ($Plans->IBR->Qualified)
	  		<tr>
	  			<td><input type="radio" name="repayment_plan" value="income_based"></td>
	  			<td>Income Based</td>
	  			<td>{{{ $Plans->IBR->Term }}}</td>
	  			<td>${{{ $Plans->IBR->Payment }}}</td>
	  		</tr>
	  		
	  		<tr class="explained hidden" id="explain_income_based">
	  			<td colspan="4">
	  			@if ($Plans->IBR->Payment == 0) <font color="Red">If you received a $0 payment due to your current financial situation, the Department of Education feels that it is not affordable for you to make monthly payments at this time. As a result you are able to enjoy the benefits of $0 payment until the Department of Education Deans that your income can support a monthly payment.</font>
	  			@endif
				Income-Based Repayment (lBR) Plan - Under this plan, your required monthly payment amount will be based on your income. To initially qualify for this plan and to continue to make income-based payments, you must have a partial financial hardship. Your monthly payment amount may be adjusted annually per your income. If your loan is not paid in full after the terms of months listed above, you may qualify for forgiveness of any unpaid interest or principal left on your loan.
	  			</td>
	  		</tr>
	  	@endif
	  	
	  	@if ($Plans->STD->Qualified)
	  		<tr>
	  			<td><input type="radio" name="repayment_plan" value="standard"></td>
	  			<td>Standard</td>
	  			<td>{{{ $Plans->STD->Term }}}</td>
	  			<td>${{{ $Plans->STD->Payment }}}</td>

	  		</tr>
	  		
	  		<tr class="explained hidden" id="explain_standard">
	  			<td colspan="4">Standard Repayment Plan - Under this plan, you will make fixed monthly payments and repay your loan in full within 10 to 30 years (not including periods of deferment or forbearance) from the date the loan entered repayment. </td>
	  		</tr>
	  	@endif
	  	
	  	@if ($Plans->GRAD->Qualified)	
	  		<tr>
	  			<td><input type="radio" name="repayment_plan" value="graduated"></td>
	  			<td>Graduated</td>
	  			<td>{{{ $Plans->GRAD->Term }}}</td>
	  			<td>${{{ $Plans->GRAD->Payment }}}</td>
	  		</tr>
	  		<tr class="explained hidden" id="explain_graduated">
	  			<td colspan="4">Graduated Repayment Plan - Under this plan, your payments will be lower at first and will then increase over time, usually every two years. You will repay your loan in full within 10 to 30 years (not including periods of deferment or forbearance) from the date the loan entered repayment. </td>
	  		</tr>
	  	@endif
	  	
	  	@if ($Plans->EXG->Qualified)
	  		<tr>
	  			<td><input type="radio" name="repayment_plan" value="extended_repayment_plan"></td>
	  			<td>Extended Repayment Plan</td>
	  			<td>{{{ $Plans->EXG->Term }}}</td>
	  			<td>${{{ $Plans->EXG->Payment }}}</td>

	  		</tr>
	  		<tr class="explained hidden" id="explain_extended_repayment_plan">
	  			<td colspan="4">Extended Repayment Plan - You may choose this plan only if. (1) you had no outstanding balance on a Direct Loan Program loan as of October 7, 1998, or on the date you obtained a Direct Loan Program loan on or after October 7, 1998; and (2) you have an outstanding balance on Direct Loan Program Loans that exceeds $30,000. Under this plan, you may choose to make either fixed or graduated monthly payments and will repay your loan in full over a repayment period not to exceed 25 years (not including periods of deferment or forbearance) from the date your loan entered repayment.</td>
	  		</tr>
	  	@endif
	  	
	  	</tbody>
	  </table>
	  
	  @if($LoanData->HasDefaulted)
	  <h3><center>Are your currently being garnished or have your taxes been withheld due to non payment of your Federal Student Loans?</center></h3>
	  <center><label><input type="radio" name="garnished" value="1" class="garnished"> Yes</label>  <label><input type="radio" name="garnished" value="0" class="garnished"> No</label></center>
	  
	  <br/>
	  
	  <div class="garnished_text" style="display:none">
	  	<center><h3><font color="red">Thank you for visiting our site but unfortunally we are unable to assist with your federal student loan consolidation. Please contact your current servicer/s for default options.</font></h3></center>
	  </div>
	  
	  @endif
	  
	  <script>
	  jQuery('input[name="repayment_plan"]').on('change', function(){
	  	// get the current value
	  	var Radio_selected = jQuery(this).val();
	  	var Explained_id = 'explain_'+Radio_selected;

	  	//find one that isn't hidden.
	  	jQuery('.explained:visible').addClass('hidden');
	  	jQuery('#'+Explained_id).removeClass('hidden');
	  });
	  jQuery('input[name="repayment_plan"]').each(function(){ if (jQuery(this).is(':checked')) { jQuery(this).trigger('change'); }  });

	  // check for garnished
	  jQuery('.garnished').on('change',function(){
	  	var val = jQuery(this).val();

	  	if (val == 1)
	  	{
	  		//show the garnished_text, and stop them from going any farther.
	  		jQuery('.CartProgram').hide();
	  		jQuery('.formsubmitter').prop('disabled',true);

	  		jQuery('.garnished_text').show();
	  	}
	  	else
	  	{
	  		jQuery('.CartProgram').show();
	  		jQuery('.formsubmitter').removeProp('disabled');

	  		jQuery('.garnished_text').hide();
	  	}

	  });

	  </script>
  
	  <div class="form-group">
	  	<input type="checkbox" class="formsubmitter checkbox-inline" id="checkbox5" name="checkbox5"><label for="checkbox5" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to STEP 6</label>
	  </div>
	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop
	 
	<script>

	var prevElem = jQuery('*[data-step-number="step5"]').ScrollTo().prev(":first");
//	 jQuery('.wizard-content',prevElem).slideUp();
	</script>

@stop