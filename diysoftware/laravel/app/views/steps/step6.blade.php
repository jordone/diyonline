<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/1/2015
 * Time: 6:42 AM
 */

        if (isset($client) && isset($client->TProperties->Special))
        {
            extract($client->TProperties->Special, EXTR_OVERWRITE);
            $Plans = $PaymentPlans;
        }
?>
@extends('wizard', array('stepnav' => false, 'class' => 'navbar-info text-info'))
@section('wizardtitle')
    {{ STEP6_TITLE }}
@stop
@section('step') data-step-number="step6" @stop

@section('wizardcontent')


    @section('wizardform_open')
        @if (isset($update_enabled) && $update_enabled)
            {{ Form::open(array('url' => 'step6f', 'class' => 'form-horizontal ajaxcheck updatecartprices')) }}

        @else
            <div class="form form-horizontal">
        @endif
     @endsection

<div class="" style="margin-top:-5px;">
    @include('errors')
    @include('snipplets.steps_header_text', ['step' => 'STEP6'])



    <div class=" list-group">
        <div class="list-group-item">

            <div class="row">
                <div class="col-sm-3 col-md-3 col-xs-6 col-lg-3 align-center" style=""><br/><br/>
                    <img src="/packages/static/gradstudent.png" class="img-responsive">
                </div>
                <div class="col-sm-8 col-md-8 col-xs-12 col-lg-9">
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
                    </font>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-12">                    <font style="font-size:13px;line-height:10px">
                    <br/>
                    <font color="blue">If you are quoted a $0.00 payment, IT IS NOT AN ERROR.</font> That is what your payment is for the next 12 month until your do your next 12 month recertification.
                    There is no penalty or repercussion for making a $0.00 payment, it just means that based on your current financial situation the Department of Education has deemed that this is the payment you can afford.
                    At the end of the loan term, all of the payments that you have made will be added up and whatever amount is unpaid, will be forgiven and you loan will be considered paid in full.
                    </font>
                </div>
            </div>
        </div>
    </div>
</div>


<table class="table table-no-border" class="planselect" width="100%">
    <thead style="background-color: #398ecb;font-size:23px;color:#fff;text-transform:uppercase;">
    <tr>
        <th align="center" width="20">&nbsp;</th>
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
    <tr>
        <td colspan="4" id="selectonlyplanplz">
            @if ($Plans->STD->Qualified== 1)
                <?php
                $plan_id = "STD";
                $plan_value = "standard";
                $plan_name = "Standard";
                #plan_explained = "Standard Repayment Plan - Under this plan, you will make fixed monthly payments and repay your loan in full within 10 to 30 years (not including periods of deferment or forbearance) from the date the loan entered repayment.";
				$plan_explained = "Standard Repayment Plan - Under this plan, you will make fixed monthly payments and repay your loan in full.  Payments are a fixed amount of at least $50 per month. Up to 10 years.  You'll pay less interest for your loan over time under this plan than you would under other plans.";
                ?>
                 @include('snipplets.planlisting', ['Plans' => $Plans, 'plan_id' => $plan_id, 'plan_name' => $plan_name, 'plan_value' => $plan_value,  'plan_explained' => $plan_explained])
            @endif

            @if ($Plans->GRAD->Qualified == 1)
                <?php
                $plan_id = "GRAD";
                $plan_value = "graduated";
                $plan_name = "Graduated";
                #plan_explained = "Graduated Repayment Plan - Under this plan, your payments will be lower at first and will then increase over time, usually every two years. You will repay your loan in full within 10 to 30 years (not including periods of deferment or forbearance) from the date the loan entered repayment. ";
                $plan_explained = "Graduated Repayment Plan - If your income is low now, but you expect it to increase steadily over time, this plan may be right for you. Under this plan, your payments will be lower at first and will then increase over time, usually every two years. Up to 10 years.  You'll pay more for your loan over time than under the 10-year standard plan.";
                ?>
                @include('snipplets.planlisting', ['Plans' => $Plans, 'plan_id' => $plan_id, 'plan_name' => $plan_name, 'plan_value' => $plan_value,  'plan_explained' => $plan_explained])
            @endif

            @if ($Plans->IBR->Qualified)
                <?php
                $plan_id = "IBR";
                $plan_name = "Income Based";
                $plan_value = "income_based";
                #plan_explained  = "The income based repayment option calculate your monthly payment based on your family size and your annual adjusted gross income.";
                $plan_explained  = "Income Based Plan - The income based repayment option calculates your monthly payment based on your family size and your annual adjusted gross income.  Your payments change as your income changes. Up to 25 years.  Your maximum monthly payments will be 15 percent of discretionary income, the difference between your adjusted gross income and 150 percent of the poverty guideline for your family size and state of residence (other conditions apply). ";
                $plan_explained .= "You must have a partial financial hardship.  Your monthly payments will be lower than payments under the 10-year standard plan. ";
                $plan_explained .= "You'll pay more for your loan over time than you would under the 10-year standard plan.";
                ?>
                @include('snipplets.planlisting', ['Plans' => $Plans, 'plan_id' => $plan_id, 'plan_name' => $plan_name, 'plan_value' => $plan_value,  'plan_explained' => $plan_explained])
            @endif

            @if ($Plans->ICR->Qualified)
                <?php
                $plan_id = "ICR";
                $plan_name = "Income Contingent";
                $plan_value = "income_contingent";
                #plan_explained = "The income contingent repayment option calculate your monthly payment solely off of your income.";
				$plan_explained = "Income Contingent Plan - The income contingent repayment option calculates your monthly payment solely off of your income.  Payments are calculated each year and are based on your adjusted gross income, family size, and the total amount of your Direct Loans.  Your payments change as your income changes.  Up to 25 years.  You'll pay more for your loan over time than under the 10-year standard plan.";
                ?>
                @include('snipplets.planlisting', ['Plans' => $Plans, 'plan_id' => $plan_id, 'plan_name' => $plan_name, 'plan_value' => $plan_value,  'plan_explained' => $plan_explained])
            @endif

            @if ($Plans->EXG->Qualified == 1)
                <?php
                $plan_id = "EXG";
                $plan_value = "extended_repayment_plan";
                $plan_name = "Extended Repayment Plan";
                #plan_explained  = "Extended Repayment Plan - You may choose this plan only if. (1) you had no outstanding balance on a Direct Loan Program loan as of October 7, 1998, or on the date you obtained a Direct Loan Program loan on or after October 7, 1998; and (2) you have an outstanding balance on Direct Loan Program Loans that exceeds $30,000. Under this plan, you may choose to make either fixed or graduated monthly payments and will repay your loan in full over a repayment period not to exceed 25 years (not including periods of deferment or forbearance) from the date your loan entered repayment. ";
                $plan_explained  = "Extended Repayment Plan - You may choose this plan only if. (1) you had no outstanding balance on a Direct Loan Program loan as of October 7, 1998, or on the date you obtained a Direct Loan Program loan on or after October 7, 1998; and (2) you have an outstanding balance on Direct Loan Program Loans that exceeds $30,000. Under this plan, you may choose to make either fixed or graduated monthly payments and will repay your loan in full over a repayment period not to exceed 20 years. ";
                $plan_explained .= "You must be a new borrower on or after Oct. 1, 2007, and must have received a disbursement of a Direct Loan on or after Oct. 1, 2011.  You must have a partial financial hardship.  Your monthly payments will be lower than payments under the 10-year standard plan.";
                ?>
                @include('snipplets.planlisting', ['Plans' => $Plans, 'plan_id' => $plan_id, 'plan_name' => $plan_name, 'plan_value' => $plan_value,  'plan_explained' => $plan_explained])
            @endif

            <!-- if ($Plans->PAY->Qualified) # PRZ -->
            @if (false)
                <?php
                $plan_id = "PAY";
                $plan_name = "Pay as you Earn";
                $plan_value = "pay";
                $plan_explained = "The pay as you earn option is one of the newest programs under President Obama's legislature and calculate a monthly payment based off a very low percentage of your income.";
                ?>
                @include('snipplets.planlisting', ['Plans' => $Plans, 'plan_id' => $plan_id, 'plan_name' => $plan_name, 'plan_value' => $plan_value,  'plan_explained' => $plan_explained])
            @endif
        </td>
    </tr>
    </tbody>
</table>

<script>
    jQuery('#selectonlyplanplz input[type="checkbox"]').on('change',function(){
        jQuery('#selectonlyplanplz input[type="checkbox"]').not(this).not('.paid_already').attr('checked', false);
        jQuery('#checkthebox5').attr('checked', false);

    });

</script>


@section('wizardform_close')
    @if (isset($update_enabled) && $update_enabled)
        @include('steps.partials.submit')
        {{ Form::close() }}
        @else
        </div>
    @endif
@endsection

<script>

    var prevElem = jQuery('*[data-step-number="step6"]').ScrollTo().prev(":first");
    jQuery('*[data-step-number="step7"]').remove();

</script>

@stop
