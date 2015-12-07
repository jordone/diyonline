<?php
/**
 * Created by PhpStorm.
 * User: Banworks
 * Date: 9/16/2015
 * Time: 11:32 PM
 */
?>
<div class="card row ">
    <div class="col-sm-1 col-md-1 col-xs-1">
        @if (isset($client->TStatus->Accounting))
            @if ($client->TStatus->Accounting->status == '100% Payments Received')
                <span class="label label-success"><a href="#" data-toggle="tooltip"  title="Status: {{ $client->TStatus->Accounting->status }}"><i class="fa fa-stop"></i></a></span>
            @elseif(strstr($client->TStatus->Accounting->status, 'Declined'))
                <span class="label label-danger"><a href="#" data-toggle="tooltip" title="Status: {{ $client->TStatus->Accounting->status }}"><i class="fa fa-stop"></i></a></span>
            @elseif(strstr($client->TStatus->Accounting->status, 'Process'))
                <span class="label label-info"><a href="#" data-toggle="tooltip" title="Status: {{ $client->TStatus->Accounting->status }}"><i class="fa fa-stop"></i></a></span>
            @else
                <span class="label label-warning"><a href="#" data-toggle="tooltip" title="Status: {{ $client->TStatus->Accounting->status }}"><i class="fa fa-stop"></i></a></span>

            @endif
        @else
            <span class="label label-warning"><a href="#" data-toggle="tooltip" title="Status: {{ $client->TStatus->Sales->status }}"><i class="text-warning fa fa-stop"></i></a></span>
        @endif
    </div>
        <div class="col-sm-7 col-md-7 col-xs-11">

        @if (isset($client->NextStep))
            @if ($client->NextStep == "8")
                <span class="badge pull-right">COMPLETED</span>
                @else

                <span class="badge pull-right">{{ "Step ".$client->NextStep }}</span>
            @endif
        @else
            <span class="label label-default bg-danger pull-right">N/A</span>
        @endif
            @if( $client->TDates->UpdatedMinutesAgo <= 5)

                {{--<span class="label label-success">ONLINE</span>--}}

            @endif
    <span style="font-size:18px; text-transform: uppercase;">
        {{{ $client->TProperties->FirstName }}} {{{ $client->TProperties->LastName }}}
    </span>

        <hr style="margin:0px;">
            @if( $client->TDates->UpdatedMinutesAgo <= 5)
                @if ($client->TDates->UpdatedMinutesAgo <= 0)
                    <b>Seen online right now</b>
                @else
                    <b>Seen about {{ $client->TDates->UpdatedMinutesAgo }} minutes ago</b>
                @endif

             @else
                <span class="text-muted">
                    Last updated {{ $client->TDates->UpdatedOn }} [{{ $client->TDates->UpdatedMinutesAgo }} minutes ago]
                </span>
            @endif

            @if (floor( (time() -  $client->Generated_UTIME) / 60) > 0)
            <font size="-2" class="">[i/o {{ floor( (time() -  $client->Generated_UTIME) / 60) }} min]</font>
            @endif

            @if (!isset($HideDocumentLink) || isset($HideDocumentLink) && $HideDocumentLink != true)
            <br/>
            <a href="{{ URL::To('/office/view', array('file' => $client->FileNumber)) }}" class=""><i class="fa fa-goggles"></i> View Document</a> |
            <a href="{{ URL::To('/office/merchant/signup', array('file' => $client->FileNumber)) }}" class=""><i class="fa fa-cash"></i> Open in Merchant</a>
            @endif

            @if (isset($MerchantCloseFileLink) && $MerchantCloseFileLink)            <br/>

            <a href="{{ URL::To('/office/merchant/close') }}" class=""><i class="fa fa-close"></i> Close Client File</a>
            @endif
    </div>

    <div class="col-sm-4 col-md-4 col-xs-12">
        <div class="progress" style="height:25px;">
            @if ($client->TDates->UpdatedMinutesAgo <= 60 && isset($client->TProperties->BrowserCSL))
                @include('office.partial.steps', array('steps' => $client->TProperties->BrowserCSL, 'BrowserCSL' => true))
            @else
                @include('office.partial.steps', array('steps' => $client->TProperties->completed_steps_bitwise, 'BrowserCSL' => false))
            @endif

        </div>
    </div>

</div>


