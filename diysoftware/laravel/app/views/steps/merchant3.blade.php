@extends('wizard', array('stepnav' => false, 'class' => 'navbar-info text-info'))

@section('wizardtitle', "Checkout")
@section('step', 'data-step-number="merchant3"')
@section('wizardform_open')
    @if (isset($update_enabled) && $update_enabled)
        {{ Form::open(array('url' => 'office/merchant/payments/save/'.(isset($client->FileNumber) && $client->FileNumber ? $client->FileNumber : ''), 'class' => 'form-horizontal')) }}
    @else
        <div class="form form-horizontal">

            @endif
            @stop

            @section('wizardform_close')
                @if (isset($update_enabled) && $update_enabled)
                    <!-- JE@include('steps.partials.submit')-->
                    {{ Form::close() }}
                @else
        </div>
        @endif
        @stop

        @section('wizardcontent')

       <!-- JE @include('snipplets.steps_header_text', ['step' => 'STEP7']) -->
                <!-- errors -->
        @include('errors')


        @include('steps.partials.office_checkout', ['client' => $client])


        <input type="hidden" name="merchant[step]" value="3">

        <script>
            jQuery('#SameAsMailing').on('change', function(){
                var checked = jQuery(this).is(':checked');
                if (checked) {
                    jQuery('.billing_fields').hide();
                }
                else jQuery('.billing_fields').show();
            }).trigger('change');

            jQuery('#Tprice').on('keyup',function(){
                // jQuery('#checkbox5').prop('checked',true).trigger('change');
                var tprice = jQuery(this).val();
                if(isNaN(tprice))
                {
                    jQuery('.numerror').show();
                }
                else{
                    jQuery('.numerror').hide();
                }


            });

        </script>
@stop

