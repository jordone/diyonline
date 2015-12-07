@extends('wizard', array('stepnav' => true, 'class' => 'navbar-info text-info'));

@section('wizardtitle')
{{ STEP1_TITLE }}
@stop

@section('step') data-step-number="step1" @stop
@section('wizardcontent')
	<h3 class="text-center">{{ STEP1_DISPLAY_TEXT }}</h3>

	<h3 class="text-center">This information is what {{COMPANY_NAME}} software relays to the Department of Education (DOE) so they can correspond with you</h3>
	
	@section('wizardform_open')
	{{ Form::open(array('url' => 'office/updatefile/', 'class' => 'form-horizontal ajaxcheck')) }}
	@stop
	
	<!-- errors -->
	@include('errors')

	<div class=" form-group">

		    <label class="col-sm-2 control-label" for="inputFirstname">Full Name</label>
		    <div class="col-sm-4"><input type="text" name="first_name" class="form-control" id="inputFirstname" placeholder="First Name" value="{{ $client->TProperties->FirstName }}" required></div>

<!--			<label class="col-sm-1 control-label" for="inputLastname">Last Name</label>-->
			<div class="col-sm-4"><input type="text" name="last_name" class="form-control" id="inputLastname" placeholder="Last Name"  value="{{ $client->TProperties->LastName }}" required></div>
	  
	</div>
	
	  <div class="form-group">
	    <label class="col-sm-2 control-label" for="InputPhone">Mobile Number</label>
	    <div class="col-sm-8"><input type="text" name="phone" class="form-control" id="InputPhone" placeholder="Enter phone number"  value="{{ $client->TProperties->HomeNumber }}" required></div>
	  </div> 
	  
	  	
	  <div class="form-group">
	    <label class="col-sm-2 control-label"  for="exampleInputEmail1">E-Mail</label>
	   <div class="col-sm-8">
	   		<input type="email" name="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"  value="{{ $client->TProperties->EmailAddress }}" required>
	    	<font color="red">This e-mail address is where {{COMPANY_NAME}} will send you your username and password </font>
	    </div>
	  </div>

	  
	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop
	 
	 <script>
		 if ( !(CurrentStepsLoaded & step1) )
			 CurrentStepsLoaded = step1;


	 $("#InputPhone").mask("(999) 999-9999")
	 </script>
@stop

