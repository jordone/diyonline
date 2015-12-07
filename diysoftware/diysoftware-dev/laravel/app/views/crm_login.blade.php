@extends('wizard')

@section('wizardtitle')
CRM Portal
@stop
@section('step') data-step-number="stepcrm" @stop
@section('wizardcontent')

	<center><h2 style="color:red">Access your files!</h2>
	 <iframe name="crm" id="crm" height="420" width="100%" frameborder="0"></iframe>
	 
	 <form id="crmlogin" method="POST" action="https://portal.leadtrac.net/diy/Account/LogOn" target="crm">
	 	                    <input id="UserName" name="UserName" type="hidden" value="{{{ $UserName }}}" />
	 	                                        <input id="Password" name="Password" type="hidden" value="{{{ $Password }}}" />
	 	                                        <input id="RememberMe" name="RememberMe" type="hidden" value="true" />
	 	                                         <input type="submit" value="Log On" class="" />
	 </form>
	

	 <script>
	 jQuery('*[data-step-number="stepcrm"]').ScrollTo();
	 jQuery('#crmlogin').submit();
	 
	 </script>

@stop