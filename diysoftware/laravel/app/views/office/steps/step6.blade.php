<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/1/2015
 * Time: 6:42 AM
 */

        if (isset($client) && isset($client->TProperties->Special))
        {
            extract($client->TProperties->Special);

        $Plans = &$PaymentPlans;


        }
?>
@extends('wizard', array('stepnav' => true, 'class' => 'navbar-info text-info'));
@section('wizardtitle')
    {{ STEP6_TITLE }}
@stop
@section('step') data-step-number="step6" @stop

@section('wizardcontent')

@section('wizardform_open')
    {{ Form::open(array('url' => 'step6f', 'class' => 'form-horizontal ajaxcheck updatecartprices')) }}
@stop

<div class="row" style="margin-top:-5px">
    @include('errors')

    <h3 class="text-center">{{ STEP6_DISPLAY_TEXT }}</h3>
    <div class="col-sm-12 col-md-12 col-xs-12 list-group">
        <div class="list-group-item">
            <h1 class="text-center" style="color: red;font-size:25px;font-weight:bold;margin-top:0px;">HERE ARE YOUR RE-PAYMENT PLAN OPTIONS</h1>

            <div class="row">
                <div class="col-sm-3 col-md-3 col-xs-6 col-lg-3 align-center" style="">
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


    </div></div>

</div>

<!-- Plans -->
<style>

</style>
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
            @if ($Plans->ICR->Qualified)

                <?php
                $plan_id = "ICR";
                $plan_name = "Income Contingent";
                $plan_value = "income_contingent";
                $plan_explained = "The income contingent repayment option calculate your monthly payment solely off of your income.";

                ?>

                @include('snipplets.planlisting')


            @endif


            @if ($Plans->IBR->Qualified)

                <?php
                $plan_id = "IBR";
                $plan_name = "Income Based";
                $plan_value = "income_based";
                $plan_explained = "The income based repayment option calculate your monthly payment based on your family size and your annual adjusted gross income.";
                ?>

                @include('snipplets.planlisting')


            @endif

            @if ($Plans->PAY->Qualified)

                <?php
                $plan_id = "PAY";
                $plan_name = "Pay as you Earn";
                $plan_value = "pay";
                $plan_explained = "The pay as you earn option is one of the newest programs under President Obama's legislature and calculate a monthly payment based off a very low percentage of your income.";
                ?>

                @include('snipplets.planlisting')
            @endif

            @if ($Plans->STD->Qualified== 1)
                <?php
                $plan_id = "STD";
                $plan_value = "standard";
                $plan_name = "Standard";
                $plan_explained = "Standard Repayment Plan - Under this plan, you will make fixed monthly payments and repay your loan in full within 10 to 30 years (not including periods of deferment or forbearance) from the date the loan entered repayment.";
                ?>
                @include('snipplets.planlisting')

            @endif

            @if ($Plans->GRAD->Qualified == 1)

                <?php
                $plan_id = "GRAD";
                $plan_value = "graduated";
                $plan_name = "Graduated";
                $plan_explained = "Graduated Repayment Plan - Under this plan, your payments will be lower at first and will then increase over time, usually every two years. You will repay your loan in full within 10 to 30 years (not including periods of deferment or forbearance) from the date the loan entered repayment. ";
                ?>
                @include('snipplets.planlisting')


            @endif

            @if ($Plans->EXG->Qualified == 1)

                <?php
                $plan_id = "EXG";
                $plan_value = "extended_repayment_plan";
                $plan_name = "Extended Repayment Plan";
                $plan_explained = "Extended Repayment Plan - You may choose this plan only if. (1) you had no outstanding balance on a Direct Loan Program loan as of October 7, 1998, or on the date you obtained a Direct Loan Program loan on or after October 7, 1998; and (2) you have an outstanding balance on Direct Loan Program Loans that exceeds $30,000. Under this plan, you may choose to make either fixed or graduated monthly payments and will repay your loan in full over a repayment period not to exceed 25 years (not including periods of deferment or forbearance) from the date your loan entered repayment. ";
                ?>
                @include('snipplets.planlisting')


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
    {{ Form::close() }}
@stop

<script>

    var prevElem = jQuery('*[data-step-number="step6"]').ScrollTo().prev(":first");
    //	 jQuery('.wizard-content',prevElem).slideUp();

//    jQuery('*[data-step-number="step6"]').remove();
    jQuery('*[data-step-number="step7"]').remove();

</script>

@stop
