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
    Viewing Customer
@stop
@section('step') data-step-number="documentview" id="docviewsheader" @stop
@section('wizardcontent')

@section('wizardform_open')
    {{ Form::open(array('url' => 'office/results', 'class' => 'form-horizontal ajaxcheck')) }}
@stop
@include('errors')

<div class="list-group">
    <div class="list-group-item">
      @include('office.partial.client', array('client' => $client, 'HideDocumentLink' => true))
    </div>
</div>

@include('office.partial.stepnav',array('client' => $client))

@section('wizardform_close')
    {{ Form::close() }}
@stop


<style>
    #docviewsheader .mywizardsteptext { display: none; }

</style>

@stop
