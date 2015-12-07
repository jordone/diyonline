<?php
$unique_id="crm_".uniqid();
$iframe_id="crm_".uniqid();
// exit(); # PRZ
?>
@extends('wizard')

@section('wizardtitle')
CRM Portal
@stop
@section('step') data-step-number="stepcrm" @stop
@section('wizardcontent')

	<center><h4 style="color:black"><B>TO DOWNLOAD YOUR APPLICATIONS PLEASE FILL IN THE INFORMATION BELOW. YOU MAY ALSO CHECK YOUR EMAIL FOR INSTRUCTIONS ON HOW AND WHERE TO DOWNLOAD YOUR APPLICATIONS AT A LATER TIME.</B></h4>
	 <iframe name="{{ $iframe_id }}" id="{{ $iframe_id }}" height="420" width="100%" frameborder="0"></iframe>
	 <form id="{{ $unique_id }}" method="POST" action="http://www.diy-sls.com/diyapplications/" 
target="{{ $iframe_id }}">
		<!-- <input id="UserName" name="UserName" type="hidden" value="{{{ $UserName }}}" /> -->
		<!-- PRZ Disable auto login <input id="Password" name="Password" type="hidden" value="{{{ $Password }}}" /> -->
		<input id="RememberMe" name="RememberMe" type="hidden" value="true" />
		<input type="submit" value="Log On" class="" />
	 </form>

	 <script>
		 if ( !(CurrentStepsLoaded & page_crm) )
			 CurrentStepsLoaded = CurrentStepsLoaded | page_crm;

		 jQuery('*[data-step-number="stepcrm"]').ScrollTo();
	 jQuery('#{{ $unique_id }}').submit();
	 
	 </script>

@stop