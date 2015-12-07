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
    We're Updating Your Documents, Please Wait!
@stop
@section('step') data-step-number="step91" @stop

@section('wizardcontent')


@section('wizardform_open')
    {{ Form::open(array('url' => 'crm', 'method' => 'GET', 'class' => 'form-horizontal ajaxcheck '.$form_class.'')) }}
@stop

@include('errors')
<h3 style="text-align:center;">Your documents are being updated</h3>
<p class="text-info text-center">Please wait we're generating your documents with the new information you have provided. <br/>
    Here you can see the status of your documents, You will see a check mark indicating your document has been updated. <br/><br/>

    <small class="text-muted">Please do not refresh this page, it will update for you!</small></p>
<div id="upgradeforms" class="well">
        @if ($ProductsOrdered['consolidation_app'])
        <div class="row" data-product-id="{{ cart_product_consolidation_app }}">
            <div class="col-sm-2 text-right fstatusc"><i class="glyphicon glyphicon-refresh"></i></div>
            <div class="col-sm-10 asset_type">A Federal Consolidation Application & Promissory Note</div>
        </div>
        @endif

        @if ($ProductsOrdered['repayment_app'])
            <div class="row" data-product-id="{{ cart_product_repayment_app }}">
                <div class="col-sm-2 text-right fstatusc"><i class="glyphicon glyphicon-refresh"></i></div>
                <div class="col-sm-10 asset_type">A Federal Consolidation Application & Promissory Note</div>
            </div>
        @endif

        @if ($ProductsOrdered['recertification_app'])
        <div class="row" data-product-id="{{ cart_product_recertification_app }}">
            <div class="col-sm-2 text-right fstatusc"><i class="glyphicon glyphicon-refresh"></i></div>
                <div class="col-sm-10 asset_type">Re-Certification of Income Based Repayment Plan</div>
            </div>
    @endif
    @if ($ProductsOrdered['pslf_app'])
                <div class="row" data-product-id="{{ cart_product_pslf_app }}">
                    <div class="col-sm-2 text-right fstatusc"><i class="glyphicon glyphicon-refresh"></i></div>
            <div class="col-sm-10 asset_type">Student Loan Forgiveness Application</div>
        </div>
    @endif
    @if ($ProductsOrdered['forebearance_app'])
        <div class="row" data-product-id="{{ cart_product_forebearance_app }}">
            <div class="col-sm-2 text-right fstatusc"><i class="glyphicon glyphicon-refresh"></i></div>
            <div class="col-sm-10 asset_type">Forbearance Application</div>
        </div>
    @endif
</div>

<script>
    var updateclientsdocuments = function(){
        jQuery('.<?php echo $form_class;?> #upgradeforms .row').each(function(){
            var product_id = jQuery(this).data('product-id');
            var loadingicon = jQuery('.fstatusc i', this).addClass('glyphicon-spin');

            (function(row, id) {
                $.ajax({
                    url: "{{ URL::to('/UpdateClientDocument/') }}",
                    dataType: 'json',
                    data: "pid=" + id,
                    type: "GET",
                    async: false,
                    success: function(data) {
                        jQuery('.fstatusc i', row).removeClass('glyphicon-spin');
                        jQuery('.fstatusc',row).html('<i class="glyphicon glyphicon-ok"></i>');
                        jQuery('.asset_type',row).append(' - UPDATED ');

//                    jQuery(row).addClass('bg-success');
                    }
                });
            }(this, product_id));

        });

        LoadStep('/crm');
    }

    jQuery(document).ready(function(){
        setTimeout(updateclientsdocuments, 1000);
    });

</script>



<div class="form-group">

</div>

@section('wizardform_close')
    {{ Form::close() }}
@stop

<script>
    var prevElem = jQuery('*[data-step-number="step91"]').ScrollTo().prev(":first");
    //	 jQuery('.wizard-content',prevElem).slideUp();
</script>

@stop