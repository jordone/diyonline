@extends('wizard')

@section('wizardtitle')
SERVIECE NOT AVALIABLE. <small>MAINTENANCE MODE</small>
@stop
@section('step') data-step-number="stepmaintenance" @stop
@section('wizardcontent')

<!--	<h4 class="info-text">This information is relayed to Department of Education (DOE)!<small><br/>Access a list of all your Federal Student Loans that will be consolidated into your new re-payment plan options.</small></h4>-->
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'step7f', 'class' => 'form-horizontal whitebackground')) }}
	@stop
  	
		@include('errors')

	  	<br/>
					<hr>	  	
    	<div class="row-fluid" style="padding-bottom:0px;">
    		<div class="col-sm-10 col-sm-offset-1" style="padding-bottom:0px;">

			
			    	<div class="alert btn-block alert-warning" style="padding-bottom:0px;padding-top:0px;">
			    	<div class="row " style="padding-top:8px;padding-bottom:0px;">
			    	
			    	<div class="col-sm-1 text-danger" style="font-size:40px;">!</div>
			    	<div class="col-sm-11">
<h4 class="page-title">Maintenance Enabled</h4>		
<hr>	    	
				    	<p class="well well-sm text-muted" style="margin-bottom:0;">
<b>					    	Maintenance has been scheduled to start at {{ MAINTENANCE_START }}.
<br/> We're expected to resume all operations at {{ MAINTENANCE_END }}.<br>
<br><small><b>During this time our online services will be inaccessible</b>.</small><br/>

 Thank you</b>
				    	</p><br>

					</div>
					</div>
    		
    		</div>
    	</div>
    	</div>
    	<hr>

<!--
		<div class="panel panel-default paypanel">
			<div class="panel-heading"><b>Office use login</b> <span class="pull-right"><img src="/packages/wizard/images/lock.png" align="absmiddle"> This page is secured with SSL Encryption</span></div>
			<div class="panel-body">
		
			
				<div class="form-group">
			       		<div class="col-sm-3">Password</div>
					 	<div class="col-sm-5"><input type="text" id="officepasswd" class="form-control"></div>
					 	<div class="col-sm-2"><button type="button" id="verify_office_pw" data-loading-text="Logging in" id="verify_office_pw" class="btn btn-primary">Login</button>      		
				</div>
				
				
				<hr>
				 
			  	
			
			
			</div>
		</div>-->
		    
 	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop


@stop