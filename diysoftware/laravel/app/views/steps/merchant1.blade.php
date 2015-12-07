<center>
<div class="inner_block"><a href="http://www.demoprocess2.diy-sls.com/office/search"><img class="" src="http://www.diy-sls.com/wp-content/uploads/2015/11/Search-button.png" alt="Search Client" width="192" height="67" />
</a></div>
</center>
&nbsp;
@extends('wizard', array('stepnav' => false, 'class' => 'navbar-info text-info'))

@section('wizardtitle', "".(isset($client->FileNumber) && $client->FileNumber ? "Register New" : "Register New")." Client")
@section('step', 'data-step-number="merchant1"')
@section('wizardform_open')
    @if (isset($update_enabled) && $update_enabled)
        {{ Form::open(array('url' => 'office/merchant/save/ltf', 'class' => 'form-horizontal '.(isset($client->FileNumber) && $client->FileNumber ? 'ajaxcheck' : ''))) }}
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

         <!-- JE   @if (isset($client->FileNumber) && $client->FileNumber)
                <div class="list-group">
                    <div class="list-group-item">
                        @include('office.partial.client', array('client' => $client, 'HideDocumentLink' => true, 'MerchantCloseFileLink' => true))
                    </div>
                </div>
            @endif -->


  <!-- JE  @include('snipplets.steps_header_text', ['step' => 'STEP1']) -->
      <!-- errors -->
      @include('errors')

      <div class=" form-group">
          <label class="col-sm-2 control-label" for="inputFirstname">Full Name <span class="text-danger">*</span> </label>
          <div class="col-sm-4"><input pattern="[^\!\@\#\$\%\^\&\*\<;\>]*" type="text" name="ltf[FirstName]" class="form-control" id="inputFirstname" placeholder="First Name" value="{{ $client->TProperties->FirstName }}" required></div>
          <div class="col-sm-4"><input pattern="[^\!\@\#\$\%\^\&\*\<;\>]*" type="text" name="ltf[LastName]" class="form-control" id="inputLastname" placeholder="Last Name"  value="{{ $client->TProperties->LastName }}" required></div>
	</div>

	  <div class="form-group">
          <label class="col-sm-2 control-label"  for="exampleInputEmail1">E-Mail <span class="text-danger">*</span></label>
          <div class="col-sm-8">
              <input type="email" name="ltf[EmailAddress]" class="form-control" id="exampleInputEmail1" placeholder="Email Address"  value="{{ $client->TProperties->EmailAddress }}" required>
          </div>
	  </div>



{{--'Campaign' => "New Lead", "EDAOption" => "Yes", "USERNAME" => "diy.admin"--}}
                <input type="hidden" name="ltf[Campaign]" value="New Lead">
                <input type="hidden" name="ltf[EDAOption]" value="Yes">
                <input type="hidden" name="ltf[USERNAME]" value="{{LEADTRAC_API_USERNAME}}">
                <input type="hidden" name="ltf[completed_steps_bitwise]" value="{{step1}}">


                <input type="hidden" name="merchant[step]" value="1">
                <input type="hidden" name="merchant[status]" value="">
                <input type="hidden" name="merchant[fields]" value="FirstName|LastName|EmailAddress">
                <input type="hidden" name="merchant[vr][EmailAddress]" value="required|email">

	 <script>
         $("#ssn2").mask("9999", {placeholder:"*"});
         $("#InputPhone").mask("(999) 999-9999")
	 </script>
@stop

