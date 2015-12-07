<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/1/2015
 * Time: 2:54 AM
 */

$form_class = uniqid();
?>

@extends('wizard')
@section('wizardtitle')
    Error, 404. Page not found.
@stop
@section('step') data-step-number="step91" @stop

@section('wizardcontent')


@section('wizardform_open')
    {{ Form::open(array('url' => 'crm', 'method' => 'GET', 'class' => 'form-horizontal ajaxcheck '.$form_class.'')) }}
@stop

@include('errors')
<h3 style="text-align:center;">The page you have requested can not be loaded</h3>
<p class="text-info text-center">This is because the resource you have requested does not exists or you have mispelled the page url.
    Please contact us if the problem persist. Thank you!</p>

@section('wizardform_close')
    {{ Form::close() }}
@stop

<script>
    var prevElem = jQuery('*[data-step-number="step91"]').ScrollTo().prev(":first");
    //	 jQuery('.wizard-content',prevElem).slideUp();
</script>

@stop