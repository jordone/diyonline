<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/1/2015
 * Time: 1:55 AM
 */
?>

@extends('wizard', array('stepnav' => false, 'class' => 'navbar-info text-info'))
@section('wizardtitle')
    {{ STEP4_TITLE }}
@stop
@section('step') data-step-number="step124" @stop
@section('wizardcontent')

    @include('snipplets.steps_header_text', ['step' => 'STEP4'])



@section('wizardform_open')
    @if (isset($update_enabled) && $update_enabled)
        {{ Form::open(array('url' => 'save_cart_items', 'class' => 'form-horizontal ajaxcheck updatecartprices')) }}

    @else
        <div class="form form-horizontal">
            @endif
 @endsection

    @include('errors')


<?php
$Products_HidePrices = true;
?>


<div id="selectonlyone">

    @include('products/consolidation_note', ['client' => $client])
    @include('products/recertification_app', ['client' => $client])
    @include('products/pslf_app', ['client' => $client])
    @include('products/forebearance_app', ['client' => $client])

</div>

<script>

    CurrentStepsLoaded = CurrentStepsLoaded | step4;

    jQuery('#selectonlyone input[type="checkbox"]').on('change',function(){
        jQuery('#selectonlyone input[type="checkbox"]').not(this).not('.paid_already').attr('checked', false);
        jQuery('#checkbox_showforms').attr('checked', false);

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


    var prevElem = jQuery('*[data-step-number="step124"]').ScrollTo().prev(":first");
    //	 jQuery('.wizard-content',prevElem).slideUp();

    // remove other forms after this one so they get positioned correctly.
    jQuery('*[data-step-number="step5"]').remove();
    jQuery('*[data-step-number="step6"]').remove();
    jQuery('*[data-step-number="step7"]').remove();

    jQuery('*[data-step-number="step91"]').remove();
    jQuery('*[data-step-number="stepcrm"]').remove();

</script>

@stop
