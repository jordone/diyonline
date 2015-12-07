@extends('wizard')

@section('wizardtitle')
    <?php if (isset($update_complete) && $update_complete)
    { ?>
    Update Successful
    <?php } else { ?>
    We Are Currently Processing Your Payment
    <?php } ?>
@stop
@section('step') data-step-number="step8" @stop
@section('wizardform_open')
    {{ Form::open(array('url' => '/step7', 'class' => 'form-horizontal ajaxcheck processpayment paymentchecker1')) }}
@stop
@section('wizardform_close')
    {{ Form::close() }}
@stop

@section('wizardcontent')

    @include('errors')
    <div id="processing_info">

    <center><h2 style="color:red">THANK YOU!</h2></center>
    <center><b>For Allowing {{COMPANY_NAME}} To Assist With Your Federal Student Loans</b></center><br/>

    <center><h2 style="color:red">DOWNLOAD YOUR FORMS NOW</h2></center>
    <center>
        <b>Your Forms Are Currently Being Generated.</b><br/>
        <b>Within 5 Minutes You Will Receive An Email With Your Username And Password.</b><br/>
        <b>This Will Allow You To Download The Forms &amp; Instructions At Your Convenience.</b><br/>
        <b>If You Have Not Recieved Your Email Within 5 Minutes Please Check Your Spam Folder.</b>
    </center>
    <br/>

    <div class="row">
        <div class="col-sm-4 col-sm-offset-3">
            <div style="text-align:right"><b>GENERATING FORMS</b> </div>
        </div>
    </div>

        <div class="row">
            <div class="col-sm-6 col-sm-offset-3">

                <div id="progressbar1">
                    @include('steps.partials.progressbar', ["progress" => $Processing_ExpiresTime_Percent, 'text' => $ClientData['cart_processing_text']])
                </div>

                <div id="progressbar2" class="hide">
                    @include('steps.partials.progressbar', ["progress" => $Processing_NextCheck_Percent, 'text' => $ClientData['cart_processing_text']])
                </div>


            </div>
        </div>
    </div>

    <div class="row hide" id="processing_failed_page">
        <div class="col-sm-8 col-sm-offset-3">
            <h1 class="text-danger">Payment Failed</h1>
            <blockquote>The payment has been declined.
                <br/>
                <strong>CUSTOMER'S ORDER IS NOT COMPLETED!</strong></blockquote>


            <a class="btn btn-lg btn-success btn-round" href="{{URL::to('step7')}}" data-ajax="1">Update Payment Info</a>

        </div>
    </div>

    <div id="processing_success" class="hide clearfix">

                <div class="list-group-item">
                    <blockquote class="list-group-item-hover success bg-white  bg-light" style="margin:0px;">COMPLETED
                        <br/>
                        Your documents are available for download
                        <a class="btn btn-success btn-sm" href="{{URL::to('/crm')}}" onclick="LoadStep('crm'); return false;"><i class="glyphicon glyphicon-link"></i> Load your documents</a>
                    </blockquote>
                </div>
    </div>



    <script>
        jQuery('*[data-step-number="step8"]').ScrollTo();

    </script>

    <script>
        var Process_Delay = parseFloat('{{"".$client->Processing_CheckEvery}}');
        var ClientData = [];
        var FileNumber = '{{$client->FileNumber}}';

        function UpdateProgressBars()
        {
            Pace.ignore(function(){
                $('#progressbar2 .progress-bar').addClass('active progress-bar-info');

                jQuery.get('{{URL::to('/getprogress')}}', function(data){
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
                        LoadStep('step7');

                    } else if (data.ClientData.cart_processing_type == 'finished')
                    {
                        $('#progressbar1 .progress-bar').removeClass('progress-bar-warning progress-bar-info progress-bar-danger').addClass('progress-bar-success');
                        $('#progressbar2 .progress-bar').removeClass('progress-bar-warning progress-bar-danger').addClass('progress-bar-success');

                        jQuery('#processing_info').removeClass('hide');
                        jQuery('#processing_failed_page').addClass('hide');
                        jQuery('#processing_success').removeClass('hide');

                        LoadStep('crm');

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

                jQuery.get('{{URL::to('/processpayment')}}?json=1', function(data){
                    UpdateProgressBars();
                });

            });
        }
        UpdateProgressBars();



    </script>



@stop