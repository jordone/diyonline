<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/1/2015
 * Time: 3:31 AM
 */
?>
@extends('wizard', array('stepnav' => true, 'class' => 'navbar-info text-info'));
@section('wizardtitle')
    {{ STEP5_TITLE }}
@stop
@section('step') data-step-number="step5" @stop

@section('wizardcontent')

    <h3 class="text-center">{{ STEP5_DISPLAY_TEXT }}</h3>


@section('wizardform_open')
    {{ Form::open(array('url' => 'step5f', 'class' => 'form-horizontal ajaxcheck', 'files' => true)) }}
@stop

@include('errors')



<div class="form-inline form-group hide">
    <input type="hidden" name="fafsa_pin" class="form-control" value="28393" id="fafsapin" placeholder="Enter your FAFSA Pin">
    <label class="col-sm-2 control-label" for="inputFirstname">Username</label>
    <div class="col-sm-4"><input type="text" name="username" class="form-control" id="inputUsername" placeholder="Enter your username" value="" required></div>

    <label class="col-sm-2 control-label" for="inputLastname">Password</label>
    <div class="col-sm-4"><input type="password" name="password" class="form-control" id="inputPassword" placeholder="Enter your password"  value="" required></div>
</div>

<div class="row">
    <div class="col-sm-10 col-sm-offset-1">
        <center>
        <span class="text-info text-center">
        Upload your student loan debt file here (which is a list of all of your federal student loans). <br/>
            If you do not have your file you must download it, select one of the boxes below on how to do so.
        </span>
        <br/>
        <span class="text-danger text-center">This is required by the DOE to pull your loans &amp; generate your new repayment options.</span>
        </center>
        <hr>
        <div class="input-group">
                <span class="input-group-btn">
                    <span class="btn btn-info btn-file">
                        <i class="glyphicon glyphicon-upload"></i> Upload Your Student Loans &hellip; <input type="file"  name="file" id="inputFile">
                    </span>
                </span>
            <input type="text" class="form-control" value="No file selected. Please select your student loan debt file." readonly>
        </div>
        <br/>
        <div class="help-block">            <u>NOTE: Your file may be saved in your "downloads" folder if you haven't chosen the saved location</u>
        </div>
        <hr>

    </div>
</div>






<div class="row">
    <div class="col-sm-offset-2">
        {{--<font color="red">This is required by the DOE to pull your loans &amp; generate your new repayment options.</font>--}}
    </div>
</div>
<br/>

<div class="row">
    <div class="col-sm-1 col-md-1 col-xs-1 text-right">
        <input type="checkbox" name="request_duplicate_pin" class="checkbox" value="1" id="duppinbox">&nbsp;<label for="duppinbox">&nbsp;</label>
    </div>
    <div class="col-sm-11 col-md-11 col-xs-11">
        <label for="duppinbox" style="font-weight:normal; font-size:14px;">
            <strong>If you DO NOT have a FSA Username & Password or if you have forgotten your FSA Username & Password</strong>. Click the 1st box to the left (this will link you over to the FSA site to create a Username & Password. Then you will need to click on the Create an FSA ID Tab.)<br/>
            Once you have completed the FSA on-screen steps you will need download your student debt file. It will be directly on the screen after you have created your FSA Username & Password as a blue circle that says "My Student Data". Click download, then come back to this page and click <i class="glyphicon glyphicon-upload"></i> Upload Your Student Loans button above.
            <br/><br/>

            {{--<u>NOTE: Your file may be saved in your "downloads" folder if you haven't chosen the saved location</u>--}}
            {{--If you DO NOT have your FSA Username & Password or if you have forgotten your FSA Username & Password. (by checking box, it will link you over to the FSA site to create a Username & Password. you need click on Create an FSA ID Tab.)--}}
        </label>
    </div>
</div>
<br/>
<div class="row">
    <div class="col-sm-1 col-md-1 col-xs-1 text-right">
        <input type="checkbox" name="request_new_pin" class="checkbox" value="1" id="newpinbox">&nbsp;<label for="newpinbox">&nbsp;</label>
    </div>
    <div class="col-sm-11 col-md-11 col-xs-11">
        <label for="newpinbox" style="font-weight:normal; font-size:14px;">
            <strong>If you HAVE your FSA Username & Password</strong> click the box to the left. (this box will link you over to the DOE site to download the clients Federal student loans.) <br/>
            Once you have logged in you will need download your student debt file. It will be directly on-screen as a blue circle that says "My Student Data". Click download, then come back to this page and click <i class="glyphicon glyphicon-upload"></i> Upload Your Student Loans button above.
            <br/><br/>
            <u>NOTE: Your file may be saved in your "downloads" folder if you haven't chosen the saved location</u>
            {{--If you DO NOT have your FSA Username & Password or if you have forgotten your FSA Username & Password. (by checking box, it will link you over to the FSA site to create a Username & Password. you need click on Create an FSA ID Tab.)--}}
        </label>
    </div>
</div>
<br/>

<div class="row">
    <div class="col-sm-12">
        <center>By requesting {{COMPANY_NAME}} document preparation service for assistance with your Federal Student Loan, you are agreeing to allow {{COMPANY_NAME}} and its authorized agents to access your profile and all data contained within the profile.</center>
    </div>
</div>
<br/>
<div class="form-group">
    <input type="checkbox" class="formsubmitter checkbox-inline" id="checkbox4" name="checkbox4"><label for="checkbox4" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to STEP 6</label>
    <br/>

</div>

@section('wizardform_close')
    {{ Form::close() }}
@stop


<script>
        CurrentStepsLoaded = CurrentStepsLoaded | step5;

    $(document).ready( function() {
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {

            var input = $(this).parents('.input-group').find(':text'),
                    log = numFiles > 1 ? numFiles + ' files selected' : label;

            if( input.length ) {
                input.val(log);
            }

        });
    });

    // Update the client status if they have chosen to have a new or duplicate pin.
    // This also shows the 'pin box' information.
    jQuery('#duppinbox').on('change',function(){
        var checked = jQuery(this).is(':checked');
        var form = jQuery(this).parents('form');
        if (checked)
        {
            window.open('https://www.nslds.ed.gov/npas/index.htm', '_blank');

        }
    });

    jQuery('#newpinbox').on('change',function(){
        var checked = jQuery(this).is(':checked');
        var form = jQuery(this).parents('form');
        if (checked)
        {
            window.open('https://www.nslds.ed.gov/npas/index.htm', '_blank');

        }
    });

    var prevElem = jQuery('*[data-step-number="step5"]').ScrollTo().prev(":first");

//    jQuery('*[data-step-number="step5"]').remove();

</script>


@stop




