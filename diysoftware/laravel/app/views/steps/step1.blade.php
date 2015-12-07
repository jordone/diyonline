@extends('wizard', array('stepnav' => false, 'class' => 'navbar-info text-info'))

@section('wizardtitle')
{{ STEP1_TITLE }}
@stop

@section('step') data-step-number="step1" @stop

@section('wizardcontent')
    @include('snipplets.steps_header_text', ['step' => 'STEP1'])

    <h4 class="">This information is what {{COMPANY_NAME}} software relays to the Department of Education (DOE) so they can correspond with you.</h4>

	@section('wizardform_open')
        @if (isset($update_enabled) && $update_enabled)
	        {{ Form::open(array('url' => 'newclientform', 'class' => 'form-horizontal ajaxcheck')) }}
            @else
            <div class="form form-horizontal">
          @endif
      @stop

      <!-- errors -->
      @include('errors')

      <div class=" form-group">
              <label class="col-sm-2 control-label" for="inputFirstname">Full Name</label>
              <div class="col-sm-4"><input pattern="[^\!\@\#\$\%\^\&\*\<;\>]*" type="text" name="first_name" class="form-control" id="inputFirstname" placeholder="First Name" value="{{ $client->TProperties->FirstName }}" required></div>

			  <!--<label class="col-sm-1 control-label" for="inputLastname">Last Name</label>-->
              <div class="col-sm-4"><input pattern="[^\!\@\#\$\%\^\&\*\<;\>]*" type="text" name="last_name" class="form-control" id="inputLastname" placeholder="Last Name"  value="{{ $client->TProperties->LastName }}" required></div>
      </div>
	
	  <div class="form-group">
	    <label class="col-sm-2 control-label" for="InputPhone">Mobile Number</label>
	    <div class="col-sm-8"><input pattern="[^\!\@\#\$\%\^\&\*\<;\>]*" type="text" name="phone" class="form-control" id="InputPhone" placeholder="Enter phone number"  value="{{ $client->TProperties->HomeNumber }}" required></div>
	  </div> 
	  
	  	
	  <div class="form-group">
	    <label class="col-sm-2 control-label"  for="exampleInputEmail1">E-Mail</label>
	   <div class="col-sm-8">
	   		<input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"  value="{{ $client->TProperties->EmailAddress }}" required>
	    	<font color="red">This e-mail address is where {{COMPANY_NAME}} will send you your username and password </font>
	    </div>
	  </div>

                @if ( !Session::get('fileNumber') && (!isset($office_viewer) || $office_viewer != true) )
                @if (Request::Ajax())
                        <!--<script src="https://www.google.com/recaptcha/api.js?onload=showRecaptcha&render=explicit" async defer></script>-->
                <?php
                $randomcapacha = 'amihuman_'.uniqid().'';
				if (false): # PRZ
                ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label"  for="exampleInputEmail1">Prove Human</label>
                    <div class="col-sm-8" id="captchadiv"><div class="g-recaptcha" id="<?php echo $randomcapacha; ?>" data-sitekey="6LfMGg8TAAAAAAxcS0dmNYYHXk_F2Sq6Pc5IMoS0"></div></div>

                    <script type="text/javascript">
                        function showRecaptcha() {
                            grecaptcha.render(document.getElementById('{{$randomcapacha}}'), {
                                'sitekey' : '6LfMGg8TAAAAAAxcS0dmNYYHXk_F2Sq6Pc5IMoS0'
                            });
                        }
                        showRecaptcha();
                    </script>
                </div>
				<? endif; ?>

                @else
					<!-- PRZ
                    <div class="row">
                        <label  class="col-sm-2 control-label"  for="leadsource">Prove Human</label>
                        <div class="col-sm-8"><div class="g-recaptcha" id="amihuman2" data-sitekey="6LfMGg8TAAAAAAxcS0dmNYYHXk_F2Sq6Pc5IMoS0"></div></div>
                    </div>
					-->
                @endif

                @endif
	  
	  
	  @section('wizardform_close')
          @if (isset($update_enabled) && $update_enabled)
              @include('steps.partials.submit')
              {{ Form::close() }}
              @else
            </div>
          @endif
	 @stop
	 
	 <script>
		 if ( !(CurrentStepsLoaded & step1) )
			 CurrentStepsLoaded = step1;


	 $("#InputPhone").mask("(999) 999-9999")
	 </script>
@stop

