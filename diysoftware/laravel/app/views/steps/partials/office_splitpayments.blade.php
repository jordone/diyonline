<div class="row" style="padding-top:10px;">
    <div class="col-sm-8 col-sm-offset-3">
        <div class="panel panel-success">
            <div class="panel-heading">Split across <span id="NoPaymentsValue">{{$client->TProperties->NoPayments}}</span> payments</div>
            <div class="panel-body">


                <div class="fixed_payments">

                    <?php
                    $number_payments = 5;
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
                    <div class="form-group splitpaymentx" id="spc_{{  ($i+1) }}">
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

                            var prev_payments = jQuery(parent_formgroup).prevAll('.form-group:not(.disabled)');
                            var next_payments = jQuery(parent_formgroup).nextAll('.form-group:not(.disabled)');

                            var number_more_payments = jQuery('.payment_amount', next_payments).length;
                            var prev_payments_amount = calculate_totals_from_elements( jQuery('.payment_amount',prev_payments) );


                            if (number_more_payments >= 1)
                            {
                                // now change all of the others so they have an equal chance at this.
                                var amount_needed = amount_must_equal - amount - prev_payments_amount;
                                jQuery('.payment_amount', next_payments).val(  (amount_needed / number_more_payments) );

                            }


                            if(isNaN(amount))
                            {
                                jQuery('.amounterror', parent_formgroup).removeClass('hide');
                            }
                            else{
                                jQuery('.amounterror', parent_formgroup).addClass('hide');
                            }
                            var total = parseFloat ( calculate_totals_from_elements('.splitpaymentx:not(.disabled) .payment_amount') ).toFixed(2);

                            if (parseFloat(total) < parseFloat(amount_must_equal))
                            {
                                jQuery('#payment_amount_total_error').removeClass('hide');
                            }
                            else if (parseFloat(total) > parseFloat(amount_must_equal))
                            {
                               var amount_exceeded_by =   parseFloat ( parseFloat(total) - parseFloat(amount_must_equal)).toFixed(2);

                                var last_payment_input = jQuery(jQuery('.payment_amount', next_payments).slice(-1));
                                var last_payment_amt = parseFloat(last_payment_input.val()).toFixed(2);
                                var last_payment_newamt = last_payment_amt - amount_exceeded_by;

                                last_payment_input.val(  last_payment_newamt );
                                var total = parseFloat ( calculate_totals_from_elements('.splitpaymentx:not(.disabled) .payment_amount') ).toFixed(2);

                            }
                            else {
                                jQuery('#payment_amount_total_error').addClass('hide');
                            }

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