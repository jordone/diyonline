<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/1/2015
 * Time: 1:55 AM
 */
?>
<center>
<div class="inner_block"><a href="http://www.demoprocess2.diy-sls.com/office"><img class="" src="http://www.diy-sls.com/wp-content/uploads/2015/11/HOME.png" alt="Start New Client" width="192" height="67" />
</a></div>
</center>
<center><h3><strong>SEARCH CLIENT BY FIRST AND LAST NAME, THEN CLICK "OPEN IN MERCHANT" TO COMPLETE THEIR SET UP.</strong></h3></center>

@extends('wizard')
@section('wizardtitle')
    Lookup customers in your database
@stop
@section('step') data-step-number="search" id="searchbox" @stop
@section('wizardcontent')

@section('wizardform_open')
    {{ Form::open(array('url' => 'office/searching', 'method' => 'GET',  'class' => 'ajaxcheck form-horizontal')) }}
@stop

@include('errors')

<div class="well well-sm" style="margin-bottom:0px;">
    <div class="form-group">
        <label for="fn22" class="col-sm-2 control-label">
            <a href="javascript:void(0)" data-toggle="tooltip" data-title="Enter your customers name">Name</a>
        </label>
        <div class="col-sm-4"><input type="text" name="firstname" class="form-control" id="fn22" placeholder="First Name" value="{{ Input::get('firstname', Session::get('Office.Search.FirstName')) }}" ></div>
        <div class="col-sm-4"><input type="text" name="lastname" class="form-control" id="fn22" placeholder="Last Name"  value="{{ Input::get('lastname', Session::get('Office.Search.LastName')) }}"></div>
        <div class="col-sm-2">
            <button class="formsubmitter btn btn-primary"><i class="fa fa-search"></i> Search</button>
        </div>
    </div>



</div>

@section('wizardform_close')
    {{ Form::close() }}
@stop

<script>
    setTimeout(function(){
//        var prevElem = jQuery('*[data-step-number="search"]').ScrollTo().prev(":first");

    }, 200);

</script>

<style>
    #searchbox .mywizardsteptext { display: none; }

</style>

@stop
