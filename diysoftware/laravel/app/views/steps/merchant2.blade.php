@extends('wizard', array('stepnav' => false, 'class' => 'navbar-info text-info'))

@section('wizardtitle', "Customer's Cart")
@section('step', 'data-step-number="merchant2"')
@section('wizardform_open')
    @if (isset($update_enabled) && $update_enabled)
        {{ Form::open(array('url' => 'office/merchant/cart/save/'.(isset($client->FileNumber) && $client->FileNumber ? $client->FileNumber : ''), 'class' => 'form-horizontal ajaxcheck')) }}
    @else
        <div class="form form-horizontal">

            @endif
            @stop

            @section('wizardform_close')
                @if (isset($update_enabled) && $update_enabled)
                    @include('steps.partials.submit')
                    {{ Form::close() }}
                @else
        </div>
        @endif
        @stop

        @section('wizardcontent')

       <!-- JE @include('snipplets.steps_header_text', ['step' => 'STEP4']) -->
                <!-- errors -->
        @include('errors')

        <div id="selectonlyone">

            @include('products/consolidation_note', ['client' => $client])
            @include('products/recertification_app', ['client' => $client])
            @include('products/pslf_app', ['client' => $client])
            @include('products/forebearance_app', ['client' => $client])

        </div>


        <input type="hidden" name="merchant[step]" value="2">

        <script>
        </script>
@stop

