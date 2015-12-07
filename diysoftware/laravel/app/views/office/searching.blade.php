<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/1/2015
 * Time: 1:55 AM
 */
?>

@extends('wizard')
@section('wizardtitle')
   <i class="fa fa-spinner fa-spin"></i> Search in progress
@stop
@section('step') data-step-number="searching" @stop
@section('wizardcontent')

@section('wizardform_open')
    {{ Form::open(array('url' => 'office/search', 'class' => 'ajaxcheck form-horizontal submitonload')) }}
@stop

@include('errors')

<p class="lead">This may take a few moments to process your request. Please do not leave or refresh this page, your search results will show up automatically</p>

<div class="well well-sm form-group form-disable disable disabled">
    <label for="fn22" class="col-sm-2 control-label">Search by name</label>
    <div class="col-sm-4"><input type="text" name="firstname" class="form-control" id="fn22" placeholder="First Name" value="{{ Input::get('firstname') }}" ></div>
    <div class="col-sm-4"><input type="text" name="lastname" class="form-control" id="fn23" placeholder="Last Name" value="{{ Input::get('lastname') }}"></div>
    <div class="col-sm-2">
        <button type="button" class="formsubmitter btn btn-info" disabled="disabled" ><i class="fa fa-spinner fa-spin"></i> Searching</button>
    </div>
</div>


@section('wizardform_close')
    {{ Form::close() }}
@stop

<script>

    jQuery('*[data-step-number="recentclients"]').parents('.step-wrapper').remove();
    jQuery('*[data-step-number="searchresults"]').parents('.step-wrapper').remove();
    var prevElem = jQuery('*[data-step-number="searching"]').ScrollTo().prev(":first");

    jQuery(document).ready(function(){
        jQuery('.submitonload').trigger('submit');
    });
</script>

@stop
