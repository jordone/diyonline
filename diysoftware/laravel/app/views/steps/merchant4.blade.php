@extends('wizard', array('stepnav' => false, 'class' => 'navbar-info text-info'))

@section('wizardtitle', "Processing Cart")
@section('step', 'data-step-number="merchant3"')
@section('wizardform_open')
    @if (isset($update_enabled) && $update_enabled)
        {{ Form::open(array('url' => 'office/merchant/payments/process/'.(isset($client->FileNumber) && $client->FileNumber ? $client->FileNumber : ''), 'class' => 'form-horizontal ajaxcheck')) }}
    @else
        <div class="form form-horizontal">

            @endif
            @stop

            @section('wizardform_close')
                @if (isset($update_enabled) && $update_enabled)
                    @include('steps.partials.submit')
                    {{ Form::close() }}
                @else
        </div>
        @endif
        @stop

        @section('wizardcontent')

        {{--        @include('snipplets.steps_header_text', ['step' => 'STEP4'])--}}
                <!-- errors -->
        @include('errors')
        <div id="processing_info">
            <h1 class="page-title">Payment is being processed, please wait</h1>

            <div class="row">
                <div class="col-sm-4 col-sm-offset-3">
                    <div style="text-align:right">
                        <b>Processing, please wait</b> </div>
                </div>
                <div class="col-sm-8 col-sm-offset-2">
                    <div id="progressbar1">
                        @include('steps.partials.progressbar', ["progress" => $Processing_ExpiresTime_Percent, 'text' => $ClientData['cart_processing_text']])
                    </div>

                    <div id="progressbar2" class="hide">
                        @include('steps.partials.progressbar', ["progress" => $Processing_NextCheck_Percent, 'text' => $ClientData['cart_processing_text']])
                    </div>

                    <h3>Payment status is described below <b class="label label-warning">You must wait for payment confirmation</b></h3>
                    <ul class="checkresults">
                        @foreach($Checks as $checkname)
                            <li id="{{$checkname['id']}}"><label>{{$checkname->id}}</label>: {{$checkname['result']}}</li>
                        @endforeach

                    </ul>

                </div>
            </div>
        </div>

        <div class="row hide" id="processing_failed_page">
            <div class="col-sm-8 col-sm-offset-3">
            <h1 class="text-danger">Payment Failed</h1>
            <blockquote>The payment has been declined.
                <br/>
                <strong>CUSTOMER'S ORDER IS NOT COMPLETED!</strong></blockquote>
            <a class="btn btn-lg btn-success btn-round" href="{{URL::to('/office/merchant/payments', ['file' => $client->FileNumber])}}">Update Payment Info</a>

            </div>
        </div>

        <div id="processing_success" class="hide">
            <h1 class="text-success page-title">Payment Successful!</h1>

        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="list-group">
            <blockquote class="list-group-item-hover success bg-white  bg-light">Perfect, all steps have been completed.
                <br/>
                Instructions to download documents will be relayed to your customer

            <h3><a href="{{URL::to('/office/merchant/close')}}">Close Customers File</a></h3>
            </blockquote> </div>
            </div>

        </div></div>

        <script>
            var Process_Delay = parseFloat('{{"".$client->Processing_CheckEvery}}');
            var ClientData = [];
            var FileNumber = '{{$client->FileNumber}}';

            function UpdateProgressBars()
            {
                Pace.ignore(function(){
                    $('#progressbar2 .progress-bar').addClass('active progress-bar-info');

                    jQuery.get('{{URL::to('/office/merchant/payments/progress')}}/'+FileNumber+'', function(data){
                        if (typeof data.Progress1 != "undefined")
                        {
                            $('#progressbar1 .progress-bar').css('width', parseFloat(data.Progress1.progress)+'%').attr('aria-valuenow', parseFloat(data.Progress1.progress));
                            $('#progressbar1 .progress-number').html(data.Progress1.progress);
                            $('#progressbar1 .progress-status').html(data.Progress1.text);
                        }
                        if (typeof data.Progress2 != "undefined")
                        {
                            $('#progressbar2 .progress-bar').css('width', parseFloat(data.Progress2.progress)+'%').attr('aria-valuenow', parseFloat(data.Progress2.progress));
                            $('#progressbar2 .progress-number').html(data.Progress2.progress);
                            $('#progressbar2 .progress-status').html(data.Progress1.text);
                            if (data.Progress2.progress>=100 && data.ClientData.cart_processing_type == 'check')
                            {
                                // we need to execute process data.
                                data.ClientData.cart_processing_type = 'process_now';

                            }
                        }
                        if (typeof data.Checks != "undefined")
                        {
                            $.each(data.Checks, function( index, checkresult ) {
                                var checkname = data.TProperties[checkresult.id]

                                if (jQuery('.checkresults #'+checkresult.id+'').length < 1)
                                {
                                    jQuery('.checkresults').append('<li id="'+checkresult.id+'"><label>'+checkname+'</label>: '+checkresult.result+'</li>');
                                }
                                else
                                {
                                    jQuery('.checkresults #'+checkresult.id+'').html('<label>'+checkname+'</label>: '+checkresult.result+'</li>');
                                }

                                if (!checkresult.result)
                                    jQuery('.checkresults #'+checkresult.id+'').hide();
                                else
                                    jQuery('.checkresults #'+checkresult.id+'').show();

                            });
                        }
                        if (data.ClientData.cart_processing_type == 'check')
                        {
                            // check if we're at 100% and if so, we're going to just recall our progress bar.


                            setTimeout(function(){ UpdateProgressBars(); }, 3102);
                        } else if (data.ClientData.cart_processing_type == 'process_now')
                        {
                            $('#progressbar2 .progress-bar').addClass('active progress-bar-success');
                            RunProcessPage();
                        } else if (data.ClientData.cart_processing_type ==  'failed')
                        {
                            $('#progressbar1 .progress-bar').removeClass('progress-bar-warning progress-bar-info active').addClass('progress-bar-danger');
                            $('#progressbar2 .progress-bar').removeClass('progress-bar-warning ').addClass('progress-bar-danger');

                            jQuery('#processing_info').addClass('hide');
                            jQuery('#processing_failed_page').removeClass('hide');
                            jQuery('#processing_success').addClass('hide');

                        } else if (data.ClientData.cart_processing_type == 'finished')
                        {
                            $('#progressbar1 .progress-bar').removeClass('progress-bar-warning progress-bar-info progress-bar-danger').addClass('progress-bar-success');
                            $('#progressbar2 .progress-bar').removeClass('progress-bar-warning progress-bar-danger').addClass('progress-bar-success');

                            jQuery('#processing_info').removeClass('hide');
                            jQuery('#processing_failed_page').addClass('hide');
                            jQuery('#processing_success').removeClass('hide');

                            jQuery('#processing_info .page-title:first-child').html('Payment has been successful!')
                        }


                    }).always(function(){
                        $('#progressbar2 .progress-bar').removeClass('progress-bar-info progress-bar-warning progress-bar-success').addClass('progress-bar-warning');
                    });});
            }

            function RunProcessPage()
            {
                $('#progressbar2 .progress-bar').addClass('active progress-bar-success');

                Pace.ignore(function(){

                    jQuery.get('{{URL::to('/office/merchant/payments/process')}}/'+FileNumber+'?json=1', function(data){
                        UpdateProgressBars();
                    });

                });
            }
            UpdateProgressBars();



        </script>
        <style>
            /**
     * Progress bars with centered text
     */

            .progress {
                position: relative;
            }

            .progress span {
                position: absolute;
                line-height:30px !important;
                display: block;
                width: 100%;
                color: #ffffff;
                text-shadow: 1px 1px #000000;
            }
        </style>
@stop

