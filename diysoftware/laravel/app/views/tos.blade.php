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
    Terms of service
@stop
@section('step') data-step-number="step82891" @stop

@section('wizardcontent')


@section('wizardform_open')
    {{ Form::open(array('url' => '', 'class' => 'form-horizontal ajaxcheck')) }}
@stop

<h3 style="text-align:center;">Please Read and accept our Terms of Service</h3>

@include('errors')



<div class="form-group">
    <input type="checkbox" class="formsubmitter checkbox-inline" id="checkbox92891" name="checkbox92891"><label for="checkbox92891" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to regenerating your forms</label>
</div>

@section('wizardform_close')
    {{ Form::close() }}
@stop

<script>
    var prevElem = jQuery('*[data-step-number="step82891"]').ScrollTo().prev(":first");
    //	 jQuery('.wizard-content',prevElem).slideUp();

    jQuery('#checkbox92891').on('change', function(){
        if ( jQuery(this).is(':checked').length )
        {

        }
    })

</script>

@stop