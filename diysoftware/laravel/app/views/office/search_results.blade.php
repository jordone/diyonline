<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/16/2015
 * Time: 11:18 PM
 */
?>

@extends('wizard')
@section('wizardtitle')
    Results matching "<i>{{ Session::get('Office.Search.FirstName')  }} {{ Session::get('Office.Search.LastName')  }}</i>" <small><a href="{{ URL::To('/office/clearsearch') }}" class="btn btn-warning">Clear</a> </small>
@stop
@section('step') data-step-number="searching" @stop
@section('wizardcontent')

@section('wizardform_open')
    {{ Form::open(array('url' => 'office/search', 'class' => 'form-horizontal ajaxcheck')) }}
@stop
<h4 class="text-center">Search: Customers Found</h4>
<div class="recentclients">
    @if (isset($clientlists) && is_array($clientlists))
        <ul class="list-group">

            @foreach($clientlists as $c_file)
                <li class="list-group-item">
                    @include('office.partial.client', array('client' => $c_file))
                </li>

            @endforeach

        </ul>
    @else

        <div class="alert alert-warning">
            Hmm, no one has created or updated their documents in the last 24 hours.
        </div>

    @endif
</div>



@section('wizardform_close')
    {{ Form::close() }}
@stop

<script>
//    var prevElem = jQuery('*[data-step-number="searching"]').ScrollTo().prev(":first");
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
</script>

@stop
