<?php
if (isset($Plans->{$plan_id}) && isset($Plans->{$plan_id}->Payment)):

/**
 * These are expected to be defined
 * 
 */
//$plan_id = "ICR";
//$plan_name = "Income Contingent";
//$plan_value = "income_contingent";

/**
 * These are auto defined because We're amazing!
 */

$plan_payment = $Plans->{$plan_id}->Payment;
$plan_forgiven = $Plans->{$plan_id}->Forgiven;
$plan_term = $Plans->{$plan_id}->Term;



/**
 * No more variables, theme is next.
 */
?>
<div class="row planbg">
	  		<div class="col-sm-6 col-md-6 col-lg-6 col-xs-7">
				<input type="checkbox" id="{{{ strtolower($plan_id) }}}payradio" name="repayment_plan" value="{{{ $plan_value }}}"  data-cartitemname="{{{ $plan_name }}}" data-repaymentprice="{{{ $plan_payment }}}" {{ ($Loan_Program_Abbr == $plan_id ? "checked=1" : "")  }} >
				<label for="{{{ strtolower($plan_id) }}}payradio">{{{ $plan_name}}}</label>
	  		</div>
	  		<div class="col-sm-3 col-md-3 col-lg-3 col-xs-4">
		  		<label for="{{{ strtolower($plan_id) }}}payradio">${{{ $plan_payment }}}</label>
	  		</div>
	  		<div class="col-sm-3 col-md-3 col-lg-3 col-xs-12">
				<label for="{{{ strtolower($plan_id) }}}payradio">Term: {{{ $plan_term }}} Months</label>
	  		</div>
</div>

<div class="row">
	  		<div class="explained col-sm-12 text-info" id="explain_income_based">
@if ($Plans->{$plan_id}->Payment <= 0)
 <font color="Red">If you received a $0 payment due to your current financial situation, the Department of Education feels that it is not affordable for you to make monthly payments at this time. As a result you are able to enjoy the benefits of $0 payment until the Department of Education deems that your income can support a monthly payment.</font><br/>
	  			@endif
 			{{{ $plan_explained }}}
	  		</div>
</div>


<?php endif; ?>
