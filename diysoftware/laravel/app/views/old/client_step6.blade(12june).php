@extends('wizard')
@section('wizardtitle')
STEP 6: Application Cart
@stop
@section('step') data-step-number="step6" @stop

@section('wizardcontent')

	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'step6f', 'class' => 'form-horizontal ajaxcheck updatecartprices')) }}
	@stop
  	  
	  <h3 style="text-align:center;">CONGRATULATIONS! <br/> YOU HAVE SELECTED YOUR NEW<br/> FEDERAL STUDENT LOAN RE-PAYMENT PROGRAM.<br/> A  PROCESS THAT CAN NORMALLY TAKE HOURS TO COMPLETE HAS JUST BEEN COMPLETED</h3>
  		
	  <font color="red"><center>Based on the information you have provided {{COMPANY_NAME}} and the Repayment Plan you have selected:</center></font> <br/>
	  
	  <h4 style="color: black;text-align:center;background-color:yellow">IF YOU CONSOLIDATE YOUR NEW MONTHLY FEDERAL STUDENT LOAN PAYMENT WILL BE  <u>${{{ $NewPayment }}}</u></h4>
	  
	  
	  <center><strong>If you would like to have your 15 page student loan consolidation application completely filled out along with your request for student loan forgiveness and forbearance forms, please see below and select which forms you would like completed and instantly emailed to you. ALL OF THE APPLICATIONS ARE BASED OFF THE INFORMATION YOU HAVE PROVIDED.</strong></center>
	  <br/>
	  
	  <center><img src="/packages/wizard/images/moneyback.png" class="img-responsive" alt="Money Back Guarentee"></center><br/>
	  
	  <font color="red"><center>If the DOE fails to approve your student loan consolidation application due to an error of {{COMPANY_NAME}}, {{COMPANY_NAME}} will refund <font color="red">100%</font> of the service charged for the preparation of your documents. <br/> OUR MONEY BACK GUARANTEE IS SUBJECT TO YOU PROVIDING ACCURATE AND TRUE INFORMATION.</center></font>
	  <br/>
	  
	  
	  <!-- Check if there is any errors -->
	  @if (Session::get('ERROR_STEP6'))
	  <div class="alert alert-danger" role="alert">{{{ Session::get('ERROR_STEP6') }}}</div>
	  @endif	
	  
	  @include('errors')  
	  
	  
	  <!-- if they have defaulted we need to ask them some information -->
<?php
/*************Amit*******************/
$c = '';
Session::put('ordered_cpn',$c);
Session::put('ordered_pslf',1);
Session::put('ordered_forebearance',1);
Session::put('ordered_repayment_promisory_note',$c);
Session::put('ordered_forebearance',$c);
Session::put('ordered_pslf',$c);

$LoanType = 'consolidate1';
// if(Session::get('ordered_repayment') == 1)
// {
	// $LoanType = '';
// }

$PublicService = '1';
/********************************/
?>
	  
	  
	  <!-- PROGRAMS !!!! -->
	  @if ($LoanType == 'consolidate')
	  <!-- Consolidated Plans -->
	  <div class="CartProgram">
	  	<div class="row">
		  	<div class="col-sm-12">
		  	
			  	<div class="">
			  		<!-- are we providing them with the 99.00 option or the 179.00 option? -->
			  		<?php
			  		if (Session::get('ordered_cpn'))
			  		{
			  			?>
					  		<input type="checkbox" name="" value="1" data-price="0.00" id="consolidation_promisory_note" checked="checked" disabled><label for="consolidation_promisory_note" class="checkbox-label"> <b><abbr title="You have already purchased this option!">$0.00</abbr> &nbsp; A FEDERAL CONSOLIDATION APPLICATION AND PROMISORY NOTE</b></label>
			  			<?php
			  		}
			  		else
			  		{
						?>
					  		<?php if (number_format($NewPayment,2) <= 0) { ?>
					  		<input type="checkbox" name="consolidation_promisory_note_1" value="1" data-price="49.00" id="consolidation_promisory_note_1"><label for="consolidation_promisory_note_1" class="checkbox-label"> <b>$49.00 &nbsp; A FEDERAL CONSOLIDATION APPLICATION AND PROMISORY NOTE</b></label>
					  		<?php } else { ?>
					  		<input type="checkbox" name="consolidation_promisory_note" value="1" data-price="49.00" id="consolidation_promisory_note"><label for="consolidation_promisory_note" class="checkbox-label"> <b>$49.00 &nbsp; A FEDERAL CONSOLIDATION APPLICATION AND PROMISORY NOTE</b></label>
					  		<?php } ?>
						<?php
			  		}
					?>
			  	</div>
			  	<div style="font-size:11px;">
			  	To HELP you lock in your new re-payment plan, a Federal Consolidation Application with the information you have provided will be emailed to you. Alll you have to do is Review, Sign and Mail away. In addition, an easy to follow detailed mailing instruction and what to expect explaination from will be included.
			  	<br/>
			  	<center>
			  		Including: one of the following<br/>
			  		(A) STANDARD REPAYMENT FORM<br/>
			  		(B)INCOME BASED REPAYMENT FORM
			  	</center>
			  	</div>
		  	
		  	</div>
	  	</div>
	  
	  </div>
	  
	  	  
	  @if ($PublicService )
	  <!-- Public service only -->
	  <div class="CartProgram">
	  	<div class="row">
		  	<div class="col-sm-12">
		  	
			  	<div class="">

		  		<?php			  	
		  		if (Session::get('ordered_pslf'))
		  		{
			  	?>
			  		<input type="checkbox" name="pslf_app" id="pslf_app" data-price="0.00" checked="checked" disabled><label for="pslf_app" class="checkbox-label"> <b><abbr title="You have already purchased this option!">$0.00</abbr> &nbsp; STUDENT LOAN FOREGIVENESS APPLICATION:</b></label>
			  	<?php
		  		}
		  		else
		  		{
				?>
			  		<input type="checkbox" name="pslf_app" id="pslf_app" data-price="9.99"><label for="pslf_app" class="checkbox-label"> <b>$9.99 &nbsp; STUDENT LOAN FOREGIVENESS APPLICATION:</b></label>
				<?php
		  		}
		  		?>			
			  	</div>
			  	<div style="font-size:11px;">
				To HELP you apply for Public Service Student Loan Forgiveness (PSLF), which can possibly eliminate some or all of your Student Loan Debt, a FULL Public Service Loan Forgiveness Package, filled out with the information you provided will be generated for your review. In Aaddition, a step by step explanation of what needs to be done for completion and submission of your application will be included.
			  	</div>
		  	
		  	</div>
	  	</div>
	  
	  </div>
	  <!-- End Public service only -->
	  @endif
	  
	@if (1 == 1)
	  <div class="CartProgram">
	  	<div class="row">
		  	<div class="col-sm-12">
		  	
			  	<div class="">

		  		<?php			  	
		  		if (Session::get('ordered_forebearance'))
		  		{
			  	?>
				  		<input type="checkbox" name="" data-price="0.00" id="forebearance_app2" checked="checked" disabled><label class="checkbox-label" for="forebearance_app2"> <b><abbr title="You have already purchased this option!">$0.00</abbr> &nbsp; FORBEARANCE APPLICATION:</b></label>
			  	<?php
		  		}
		  		else
		  		{
				?>
				  		<input type="checkbox" name="forebearance_app" data-price="9.99" id="forebearance_app"><label class="checkbox-label" for="forebearance_app"> <b>$9.99 &nbsp; FORBEARANCE APPLICATION:</b></label>
				<?php
		  		}
		  		?>		
			  	
			  	</div>
			  	<div style="font-size:11px;">
					To HELP you apply for a forbearance, which will assist you in postponing your student loan payments 3-36 months. A Forbearance application filled out with the information you provided will be generated for you. In addition, a step by step explanation of what needs to be done for completion and submission of your application will be included.
			  	</div>
		  	
		  	</div>
	  	</div>
	  
	  </div>
	  @endif
	  
	  
	  @else
	  <!-- REPAYMENT Plan -->
	  <div class="CartProgram">
	  	<div class="row">
		  	<div class="col-sm-12">
		  	
		  	<div class="">
		  		<?php			  	
		  		if (Session::get('ordered_repayment_promisory_note'))
		  		{
			  	?>
				<input type="checkbox" name="" value="1" data-price="0.00" id="consolidation_promisory_note_1" checked="checked" disabled><label for="consolidation_promisory_note_1" class="checkbox-label"> <b><abbr title="You have already purchased this option!"><abbr title="You have already purchased this option!">$0.00</abbr></abbr> &nbsp; A FEDERAL CONSOLIDATION APPLICATION AND PROMISORY NOTE</b></label>
			  	<?php
		  		}
		  		else
		  		{
				?>
		  		<?php if (number_format($NewPayment,2) <= 0) { ?>
		  		<input type="checkbox" name="repayment_promisory_note_1" id="repayment_promisory_note_1" value="1" data-price="49.00"><label class="checkbox-label" for="repayment_promisory_note_1"> <b>$49.00 &nbsp; RE-PAYMENT REQUEST APPLICATION:</b></label>
		  		<?php } else { ?>
		  		<input type="checkbox" name="repayment_promisory_note" id="repayment_promisory_note" value="1" data-price="49.00"> <label class="checkbox-label" for="repayment_promisory_note">$49.00 &nbsp; RE-PAYMENT REQUEST APPLICATION:</label>
		  		<?php } ?>
				<?php
		  		}
				?>


			  	</div>
			  	<div style="font-size:11px;">
				To HELP you lock in your new re-payment plan, a federal re-payment request application filled out with the information you have provided will be generated for you. All you have to do is review, sign and mail away. In addition, an easy to follow detailed mailing instructions will be included.
			  	<br/>
			  	<center>
			  		Including: one of the following<br/>
			  		(A) STANDARD REPAYMENT FORM<br/>
			  		(B)INCOME BASED REPAYMENT FORM
			  	</center>
			  	</div>
		  	
		  	</div>
	  	</div>
	  
	  </div>
<!--	  change 1000 to 1 so this shows. -->
		  @if ($HasForbearance && 1 == 1000)
	
		  <div class="CartProgram">
		  	<div class="row">
			  	<div class="col-sm-12">
			  	
				  	<div class="">
		  		<?php			  	
		  		if (Session::get('ordered_forebearance'))
		  		{
			  	?>
				  		<input type="checkbox" name="" data-price="0.00" id="forebearance_app2" checked="checked" disabled><label class="checkbox-label" for="forebearance_app2"> <b><abbr title="You have already purchased this option!">$0.00</abbr> &nbsp; FORBEARANCE APPLICATION:</b></label>
			  	<?php
		  		}
		  		else
		  		{
				?>
				  		<input type="checkbox" name="forebearance_app" data-price="9.99" id="forebearance_app2"><label class="checkbox-label" for="forebearance_app2"> <b>$9.99 &nbsp; FORBEARANCE APPLICATION:</b></label>
				<?php
		  		}
		  		?>				  		
				  	</div>
				  	<div style="font-size:11px;">
						To HELP you apply for a forbearance, which will assist you in postponing your student loan payments 3-36 months. A Forbearance application filled out with the information you provided will be generated for you. In addition, a step by step explanation of what needs to be done for completion and submission of your application will be included.
				  	</div>
			  	
			  	</div>
		  	</div>
		  
		  </div>
		  
		  @endif
		  <!-- 1000 doesnt equal 1, so this wont show -->
	  @if ($PublicService && 1 == 1)
	  <!-- Public service only -->
	  <div class="CartProgram">
	  	<div class="row">
		  	<div class="col-sm-12">
		  	
			  	<div class="">

		  		<?php			  	
		  		if (Session::get('ordered_pslf'))
		  		{
			  	?>
			  		<input type="checkbox" name="pslf_app" id="pslf_app" data-price="0.00" checked="checked" disabled><label for="pslf_app" class="checkbox-label"> <b><abbr title="You have already purchased this option!">$0.00</abbr> &nbsp; STUDENT LOAN FOREGIVENESS APPLICATION:</b></label>
			  	<?php
		  		}
		  		else
		  		{
				?>
			  		<input type="checkbox" name="pslf_app" id="pslf_app" data-price="9.99"><label for="pslf_app" class="checkbox-label"> <b>$9.99 &nbsp; STUDENT LOAN FOREGIVENESS APPLICATION:</b></label>
				<?php
		  		}
		  		?>			
			  	</div>
			  	<div style="font-size:11px;">
				To HELP you apply for Public Service Student Loan Forgiveness (PSLF), which can possibly eliminate some or all of your Student Loan Debt, a FULL Public Service Loan Forgiveness Package, filled out with the information you provided will be generated for your review. In Aaddition, a step by step explanation of what needs to be done for completion and submission of your application will be included.
			  	</div>
		  	
		  	</div>
	  	</div>
	  
	  </div>
	  <!-- End Public service only -->
	  @endif
	  
	  @endif
	  
	  
	  <br/>
	  
	  <center><b>Total Amount Due: <span id="cart_total_due">$0.00</span></b></center>
	  <br/>

	  <script>
	  jQuery('.updatecartprices input[type="checkbox"]').on('change', function(){
	  	// we'll have a calculate function! but not yet.
	  	// run the calculate function.. woo
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
	  	jQuery('.updatecartprices input[type="checkbox"]:checked').each(function(){
	  		var price = parseFloat(jQuery(this).data('price'));
	  		if (price) priceTotal += price;
	  	});
		
	  	priceTotal = number_format(priceTotal, 2);
	  	// update the container: #total_due
	  	jQuery('#cart_total_due').html('$'+priceTotal);
	  }

	  </script>
	    
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
