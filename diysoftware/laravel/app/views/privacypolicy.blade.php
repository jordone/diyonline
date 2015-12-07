<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/1/2015
 * Time: 2:54 AM
 */
?>
@extends('wizard')
@section('wizardtitle')
    Privacy Policy
@stop
@section('step') data-step-number="step82891" @stop

@section('wizardcontent')


@section('wizardform_open')
    {{ Form::open(array('url' => URL::to('/disclosuref'), 'class' => 'form-horizontal ajaxcheck')) }}
@stop

<h4 style="text-align:center;">Privacy Policy</h4>

@include('errors')

<div class="form-group">
    <div class="col-sm-10 col-md-10 col-sm-offset-1 col-md-offset-1">
        <div class="well well-sm">
            <div style="max-height: 500px; overflow-y:scroll">
        {{ get_privacypolicy_text(true) }}
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <input type="checkbox" class="formsubmitter checkbox-inline" id="checkbox92891" name="checkbox92891"><label for="checkbox92891" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">
        By checking this box you agree that you have read, understand, and accept the Privacy Policy described above and our Disclosure.
    </label>
</div>

@section('wizardform_close')
    {{ Form::close() }}
@stop

<script>
    var prevElem = jQuery('*[data-step-number="step82891"]').ScrollTo().prev(":first");
    //	 jQuery('.wizard-content',prevElem).slideUp();
    jQuery('#checkbox92891').on('change',function(){
        jQuery('#disclosure_display_box').parents('form').submit();
    });
</script>

@stop