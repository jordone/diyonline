@extends('wizard')
@section('wizardtitle')
STEP 5: Repayment Plans Available
@stop
@section('step') data-step-number="step5" @stop

@section('wizardcontent')

	<h4 class="info-text"></h4>
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'step5f', 'class' => 'form-horizontal ajaxcheck updatecartprices')) }}
	@stop
	
  	@include('errors')

  	<div class="row" style="background-color:#fff;margin-top:-14px;padding-top:-5px;">
	
		<img src="/packages/static/gradstudent.png" class="img-responsive" align="left" style="padding-right:5px;">
  		<h1 style="color: red;font-size:25px;font-weight:bold;margin-top:0px;">HERE ARE YOUR RE-PAYMENT PLAN OPTIONS</h1>
		<font style="font-size:13px;line-height:10px">
			The Department of Education's Income Based repayment options are designed to allow you the ability to make a low affordable monthly payment. 
			These repayment options are based on your INCOME and FAMILY SIZE.  
			The lower your income and the larger your family size is, the lower your monthly payment will be. 
			Unlike a mortgage or car loan where you have to pay the loan in full, the only requirement of the Income Based repayment options are that you make your minimum required payment for the duration of the loan. 
			At the end of the term, if there is any unpaid balance or interest, it will be forgiven and your loans will be considered paid in full. <br/>
			<br/>
			<font color="blue">This is how it works: Every 12 months the Department of Education will require you to refile your income, this is called Recertification.</font>
			The DOE will review your income and family size and let you know what you minimum payment will be for the next 12 months.
			You will do this every year until your loan matures. This will insure that you will always have a payment that you can afford.
			<br/><br/>
			
			<font color="blue">If you are quoted a $0.00 payment, IT IS NOT AN ERROR.</font> That is what your payment is for the next 12 month until your do your next 12 month recertification. 
			There is no penalty or repercussion for making a $0.00 payment, it just means that based on your current financial situation the Department of Education has deemed that this is the payment you can afford. 
			At the end of the loan term, all of the payments that you have made will be added up and whatever amount is unpaid, will be forgiven and you loan will be considered paid in full. 
			</font>  
			<br/><br/>

  	</div>
  	  
	  <!-- Plans -->
<style>
	.planbg {
		font-size:20px;
		color: #444444;
		background: url('/packages/static/payment_opt_bg.png') bottom left repeat-x;
		height:60px;

		vertical-align:middle !important;
	}
	.planbg td {

		vertical-align:middle !important;
	}
</style>
	  <table class="table table-no-border" class="planselect">
	  	<thead style="background-color: #398ecb;font-size:23px;color:#fff;text-transform:uppercase;">
	  		<tr>
	  			<th align="center" width="40">&nbsp;</th>
	  			<th align="center">Repayment Plan</th>
	  			<th align="center" colspan="2">Your New Monthly Payments Are</th>
<!--	  			<th align="center">Amount that will be <u>forgiven</u></th>-->
	  		</tr>
	  	</thead>
	  	
	  	<tbody>
	  		<tr>
	  			<td colspan="4">
	  			<center><font color="red" size="+2">Please pick a program that best fits your financial situation</font></center>
	  			</td>
	  		</tr>
	  	@if ($Plans->ICR->Qualified)
	  	
	  		<tr class="planbg" valign="middle">
	  			<td height="60" align="center" ><input type="radio" id="icrpayradio" name="repayment_plan" value="income_contingent"  data-cartitemname="INCOME CONTINGENT" data-repaymentprice="{{{ $Plans->ICR->Payment }}}"></td>
	  			<td height="60" ><label for="icrpayradio">Income Contingent</label></td>	  
	  			<td height="60" >${{{ $Plans->ICR->Payment }}}</td>
<!--	  			<td>${{{ $Plans->ICR->Forgiven }}}</td>-->
<td>Term: {{{ $Plans->ICR->Term }}} Months</td>
	  		</tr>
	  		
			<tr class="explained " id="explain_income_contingent">
				<td colspan="4">
				@if ($Plans->ICR->Payment == 0 && 100 == 1)<font color="Red">If you received a $0 payment due to your current financial situation, the Department of Education feels that it is not affordable for you to make monthly payments at this time. As a result you are able to enjoy the benefits of $0 payment until the Department of Education Deans that your income can support a monthly payment.</font>
				@endif
<!--Income Contingent Repayment (ICR) Plan - Under this plan, your monthly payment amount will be based on your annual adjusted gross income (and that of your spouses if you are married), your family size, and the total amount of your Direct Loans. As your income changes, your payments may change. If you do not repay your loan after the term of months listed above, under this plan any unpaid interest or principal balance will be forgiven.-->
The income contingent repayment option calculate your monthly payment solely off of your income. 
				</td>
			</tr>
		@endif
		
		@if ($Plans->IBR->Qualified)
	  		<tr class="planbg">
	  			<td height="60" valign="middle"  align="center" ><input type="radio" id="ibrpayradio" name="repayment_plan" value="income_based" data-cartitemname="INCOME BASED" data-repaymentprice="{{{ $Plans->IBR->Payment }}}"></td>
	  			<td height="60" valign="middle"><label for="ibrpayradio">Income Based</label></td>
	  			<td height="60" valign="middle">${{{ $Plans->IBR->Payment }}}</td>	  			
<!--	  			<td>${{{ $Plans->IBR->Forgiven }}}</td>-->
<td>Term: {{{ $Plans->IBR->Term }}} Months</td>
	  		</tr>
	  		
	  		<tr class="explained" id="explain_income_based">
	  			<td colspan="4">
	  			@if ($Plans->IBR->Payment == 0  && 100 == 1) <font color="Red">If you received a $0 payment due to your current financial situation, the Department of Education feels that it is not affordable for you to make monthly payments at this time. As a result you are able to enjoy the benefits of $0 payment until the Department of Education Deans that your income can support a monthly payment.</font>
	  			@endif
<!--				Income-Based Repayment (lBR) Plan - Under this plan, your required monthly payment amount will be based on your income. To initially qualify for this plan and to continue to make income-based payments, you must have a partial financial hardship. Your monthly payment amount may be adjusted annually per your income. If your loan is not paid in full after the terms of months listed above, you may qualify for forgiveness of any unpaid interest or principal left on your loan.-->
The income based repayment option calculate your monthly payment based on your family size and your annual adjusted gross income.
	  			</td>
	  		</tr>
	  	@endif
	  	
		@if ($Plans->PAY->Qualified)
	  		<tr class="planbg">
	  			<td height="60" valign="middle"  align="center" ><input type="radio" id="paypayradio" name="repayment_plan" value="pay"  data-cartitemname="PAY AS YOU EARN" data-repaymentprice="{{{ $Plans->PAY->Payment }}}"></td>
	  			<td height="60" valign="middle"><label for="paypayradio">Pay as you Earn</label></td>
	  			<td height="60" valign="middle">${{{ $Plans->PAY->Payment }}}</td>	  			
<!--	  			<td>${{{ $Plans->IBR->Forgiven }}}</td>-->
<td>Term: {{{ $Plans->PAY->Term }}} Months</td>
	  		</tr>
	  		
	  		<tr class="explained" id="explain_pay">
	  			<td colspan="4">
	  			@if ($Plans->PAY->Payment == 0 && 100 == 1) <font color="Red">If you received a $0 payment due to your current financial situation, the Department of Education feels that it is not affordable for you to make monthly payments at this time. As a result you are able to enjoy the benefits of $0 payment until the Department of Education Deans that your income can support a monthly payment.</font>
	  			@endif
<!--				Pay As You Earn Repayment (PAY) Plan - Under this plan, your required monthly payment amount will be based on your income. To initially qualify for this plan and to continue to make income-based payments, you must have a partial financial hardship. Your monthly payment amount may be adjusted annually per your income. If your loan is not paid in full after the terms of months listed above, you may qualify for forgiveness of any unpaid interest or principal left on your loan.-->
The pay as you earn option is one of the newest programs under President Obama's legislature and calculate a monthly payment based off a very low percentage of your income.

	  			</td>
	  		</tr>
	  	@endif
	  	
	  	@if ($Plans->STD->Qualified== 1)
	  		<tr class="planbg">
	  			<td><input type="radio" name="repayment_plan" value="standard" data-repaymentprice="{{{ $Plans->STD->Payment }}}"></td>
	  			<td>Standard</td>
	  			<td>${{{ $Plans->STD->Payment }}}</td>	  			
<!--	  			<td>${{{ $Plans->STD->Forgiven }}}</td>-->
<td>Term: {{{ $Plans->STD->Term }}} Months</td>

	  		</tr>
	  		
	  		<tr class="explained" id="explain_standard">
	  			<td colspan="4">Standard Repayment Plan - Under this plan, you will make fixed monthly payments and repay your loan in full within 10 to 30 years (not including periods of deferment or forbearance) from the date the loan entered repayment. </td>
	  		</tr>
	  	@endif
	  	
	  	@if ($Plans->GRAD->Qualified == 1)	
	  		<tr class="planbg">
	  			<td><input type="radio" name="repayment_plan" value="graduated" data-repaymentprice="{{{ $Plans->GRAD->Payment }}}"></td>
	  			<td>Graduated</td>
	  			<td>${{{ $Plans->GRAD->Payment }}}</td>	  		
<td>Term: {{{ $Plans->GRAD->Term }}} Months</td>				
<!--	  			<td>${{{ $Plans->GRAD->Forgiven }}}</td>-->
	  		</tr>
	  		<tr class="explained" id="explain_graduated">
	  			<td colspan="4">Graduated Repayment Plan - Under this plan, your payments will be lower at first and will then increase over time, usually every two years. You will repay your loan in full within 10 to 30 years (not including periods of deferment or forbearance) from the date the loan entered repayment. </td>
	  		</tr>
	  	@endif
	  	
	  	@if ($Plans->EXG->Qualified == 1)
	  		<tr class="planbg">
	  			<td><input type="radio" name="repayment_plan" value="extended_repayment_plan" data-repaymentprice="{{{ $Plans->EXG->Payment }}}"></td>
	  			<td>Extended Repayment Plan</td>
	  			<td>${{{ $Plans->EXG->Payment }}}</td>	  		
<td>Term: {{{ $Plans->EXG->Term }}} Months</td>				
<!--	  			<td>${{{ $Plans->EXG->Forgiven }}}</td>-->

	  		</tr>
	  		<tr class="explained " id="explain_extended_repayment_plan">
	  			<td colspan="4">Extended Repayment Plan - You may choose this plan only if. (1) you had no outstanding balance on a Direct Loan Program loan as of October 7, 1998, or on the date you obtained a Direct Loan Program loan on or after October 7, 1998; and (2) you have an outstanding balance on Direct Loan Program Loans that exceeds $30,000. Under this plan, you may choose to make either fixed or graduated monthly payments and will repay your loan in full over a repayment period not to exceed 25 years (not including periods of deferment or forbearance) from the date your loan entered repayment.</td>
	  		</tr>
	  	@endif
	  	
	  	</tbody>
	  </table>

	  <div style="background-color:#ebf3fa;margin-left:-15px;margin-right:-15px;margin-top:-15px;padding-top:-15px;">
	  	<center style="display:none">
	  	<span class="jsdonef hide">
	  	<font color="#125280" size="+1">If you consolidate your new monthly</font><br/>
	  	<font color="#125280" size="+3">FEDERAL STUDENT LOAN PAYMENT</font><br/>
	  	<font color="#125280" size="+4">WILL BE</font><br/>
	  	<font color="red" style="font-size:50px;font-weight:bold;display:none" id="repaymentpricehere">$0</font><br/>
	  	</span>
	  	
	  	<label for="checkbox5" style="cursor:pointer"><img src="/packages/static/lockinpayment.png" class="img-responsive"></label><br/>
	  	<br/>
	  	</center>
		
		
		<br/>
	  <div class="form-group">
	  	<input type="checkbox" class="formsubmitter checkbox-inline" id="checkthebox5" name="checkthebox5"><label for="checkthebox5" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to STEP 6</label>
	  	<br/>
	  	
	  </div>
		
		<br/>
		
		
			  		<font color="#125280">
				  		<center><strong>
							Your 15 page Federal Consolidation Application with the information you have provided will be completed for you. All you have to do is follow the instructions to download your application, Review, Sign and Mail away. In addition, an easy to follow detailed mailing instruction and what to expect explanation from will be included.  				  		
				  		</strong></center>
				  	</font>
				  	<br/>	  	
	  	<div class="row">
	  		<div class="col-sm-3 col-sm-offset-1"><center><img src="/packages/static/xmoneyback.png" class="img-responsive" alt="Money Back Guarentee"></center></div>	  	
		  	<div class="col-sm-6">
				<br/>
				<center>
<font color="red"> If the DOE fails to approve your student loan consolidation application due to an error of {{COMPANY_NAME}}, {{COMPANY_NAME}} will refund 100% of the service charged for the preparation of your documents.
OUR MONEY BACK GUARANTEE IS SUBJECT TO YOU PROVIDING ACCURATE AND TRUE INFORMATION.</font>
				</center>
		  	</div>
	  	</div>
	  	
	  	<br/>
	  	
	  </div>

	  
	  <!-- Check if there is any errors -->
	  @if (Session::get('ERROR_STEP6'))
	  <div class="alert alert-danger" role="alert">{{{ Session::get('ERROR_STEP6') }}}</div>
	  @endif	
	  
	  @include('errors')  
	  
<style>
.cartitem .cartlabel {
	font-size:20px;
	color: #125280;
}
.cartitem .cartredprice sup {
font-size:10px; 
}
.cartitem .cartredprice {
 color: red;
}
.cartitem .cartdesc {
	color: #125280;
	font-size:12px;
	font-weight:bold;
	max-width:600px;
}
.cartitem .cartincludes {
font-weight:normal;
margin-left:40px; 
line-height:20px;
}
.cibgblue {
background-color: #125280;
color:#FFF;
padding:2px;
border-radius:4px;
}
</style>
	  
	  <!-- if they have defaulted we need to ask them some information -->
	  @if ($LoanType == 'consolidate')
	  <input type="hidden" name="consolidation_promisory_note" value="1">
	  <!-- Item for Consolidate -->
	  @if (100 == 1)
	  <div class="cartitem">
	  	<div class="cartlabel">
			<input type="checkbox" name="consolidation_promisory_note" value="1" data-price="49.00" checked id="consolidation_promisory_note">
			<label for="consolidation_promisory_note" class="checkbox-label"><b><span class="cartredprice">$49<sup>.00</sup></span> &nbsp; <span class="cartitemname" data-carttype="CONSOLIDATION FORM">A FEDERAL CONSOLIDATION APPLICATION AND PROMISORY NOTE</span></b></label>
	  	</div>
	  	<div class="cartdesc">
		  	To HELP you lock in your new re-payment plan, a Federal Consolidation Application with the information you have provided will be emailed to you. Alll you have to do is Review, Sign and Mail away. In addition, an easy to follow detailed mailing instruction and what to expect explaination from will be included.
			  	<br/><br/>
				
			  	<div class="cartincludes">
			  		<font size="+2" style="font-weight:normal !important"><b>Including</b>: One of the following</font><br/>
			  		<span class="cibgblue">(A)</span> INCOME CONTINGENT REPAYMENT FORM <br/>
			  		<span class="cibgblue">(B)</span> INCOME BASED REPAYMENT FORM
			  	</div>	  	
	  	</div>
	  </div>
	  @endif
	  <!-- End Item For Consolidate -->
	  @else
<!--	  Item for Repayment -->
	  <input type="hidden" name="repayment_promisory_note" value="1">
	  @if (100 == 1)
	  <div class="cartitem">
	  	<div class="cartlabel">
			<input type="checkbox" name="repayment_promisory_note" id="repayment_promisory_note" value="1" data-price="49.00" checked>
			<label class="checkbox-label" for="repayment_promisory_note"><b><span class="cartredprice">$49<sup>.00</sup></span> &nbsp; <span class="cartitemname" data-carttype="REPAYMENT FORM">A FEDERAL CONSOLIDATION APPLICATION AND PROMISORY NOTE</span></b></label>
	  	</div>
	  	<div class="cartdesc">
				To HELP you lock in your new re-payment plan, a federal re-payment request application filled out with the information you have provided will be generated for you. All you have to do is review, sign and mail away. In addition, an easy to follow detailed mailing instructions will be included.
			  	<br/><br/>
				
			  	<div class="cartincludes">
			  		<font size="+2" style="font-weight:normal !important"><b>Including</b>: One of the following</font><br/>
			  		<span class="cibgblue">(A)</span> INCOME CONTINGENT REPAYMENT FORM<br/>
			  		<span class="cibgblue">(B)</span> INCOME BASED REPAYMENT FORM
			  	</div>
	  	</div>
	  	@endif
	  </div>
	  	  
	  @endif
	<div class="hide">	  
	  
 
	  <center><b>Total Amount Due: <span id="cart_total_due">$49.00</span></b></center>
	  <br/>
</div>
	  <script>
	  jQuery('#checkthebox5').on('click',function(){
	  	jQuery('#checkbox5').prop('checked',true).trigger('change');
	  	
	  });
	  jQuery('.updatecartprices .CartProgram input[type="checkbox"]').on('change', function(){

	  	UpdateCartPrices();
	  });
		
function number_format(number, decimals, dec_point, thousands_sep) {
  //  discuss at: http://phpjs.org/functions/number_format/
  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function (n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}	  
	  
	  UpdateCartPrices = function() {
	  	var priceTotal = 0.00;
	  	jQuery('.updatecartprices .CartProgram input[type="checkbox"]:checked').each(function(){

	  		var price = parseFloat(jQuery(this).data('price'));
	  		if (price) priceTotal += price;
	  	});
		
	  	priceTotal = number_format(priceTotal, 2);
	  	// update the container: #total_due
	  	jQuery('#cart_total_due').html('$'+priceTotal);
	  }
	  


	  </script>
	    

	  
	  <script>
	  jQuery('input[name="repayment_plan"]').on('change', function(){
	  	// get the current value
	  	var Radio_selected = jQuery(this).val();
	  	var Explained_id = 'explain_'+Radio_selected;
		var RepaymentPrice = jQuery(this).data('repaymentprice');
		if (RepaymentPrice == undefined) RepaymentPrice = '0';
		
	  	//find one that isn't hidden.
//	  	jQuery('.explained:visible').addClass('hidden');
//	  	jQuery('#'+Explained_id).removeClass('hidden');
		
		// update the name of the product to which one they have selected
		var labelforitem = jQuery('.cartitem .cartitemname').data('carttype');

		var cartitemname = jQuery(this).data('cartitemname');
		var cartitemname = cartitemname + ' - '+labelforitem;
		
		jQuery('.cartitem .cartitemname').html(cartitemname);

	  	jQuery('#repaymentpricehere').html('$'+RepaymentPrice);
	  	jQuery('.jsdonef').removeClass('hide');
	  });
//	  jQuery('input[name="repayment_plan"]:first').prop('checked',true);
	  
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
  
	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop
	 
	<script>

	var prevElem = jQuery('*[data-step-number="step5"]').ScrollTo().prev(":first");
//	 jQuery('.wizard-content',prevElem).slideUp();
	</script>

@stop