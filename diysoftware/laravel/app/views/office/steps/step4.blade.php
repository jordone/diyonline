<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/1/2015
 * Time: 1:55 AM
 */
?>

@extends('wizard', array('stepnav' => true, 'class' => 'navbar-info text-info'));
@section('wizardtitle')
    {{ STEP4_TITLE }}
@stop
@section('step') data-step-number="step124" @stop
@section('wizardcontent')

@section('wizardform_open')
    {{ Form::open(array('url' => 'save_cart_items', 'class' => 'form-horizontal ajaxcheck updatecartprices')) }}
@stop
<h4 class="text-center">{{ STEP4_DISPLAY_TEXT }}</h4>


@include('errors')



<?php
$Products_HidePrices = true;
?>


<div id="selectonlyone">

    @include('products/consolidation_note')
    @include('products/recertification_app')
    @include('products/pslf_app')
    @include('products/forebearance_app')

</div>

<script>

    CurrentStepsLoaded = CurrentStepsLoaded | step4;

    jQuery('#selectonlyone input[type="checkbox"]').on('change',function(){
        jQuery('#selectonlyone input[type="checkbox"]').not(this).not('.paid_already').attr('checked', false);
        jQuery('#checkbox_showforms').attr('checked', false);

    });

</script>

<div class="form-group hide">
    <input type="checkbox" class="formsubmitter checkbox-inline" id="checkbox_showforms" name="checkbox_showforms"><label for="checkbox_showforms" class="checkbox-label col-sm-offset-1" style="font-weight:bold;font-size:15px;">Check the box if all of the information is correct &amp; continue to STEP 5</label>
</div>

@section('wizardform_close')
    {{ Form::close() }}
@stop

<script>


    var prevElem = jQuery('*[data-step-number="step124"]').ScrollTo().prev(":first");
    //	 jQuery('.wizard-content',prevElem).slideUp();

    // remove other forms after this one so they get positioned correctly.
    jQuery('*[data-step-number="step4"]').remove();
    jQuery('*[data-step-number="step5"]').remove();
    jQuery('*[data-step-number="step6"]').remove();
    jQuery('*[data-step-number="step7"]').remove();

    jQuery('*[data-step-number="step91"]').remove();
    jQuery('*[data-step-number="stepcrm"]').remove();

</script>

@stop
