@extends('wizard')

@section('wizardtitle')
<?php if (isset($update_complete) && $update_complete)
{ ?>
Update Successful
<?php } else { ?>
We Are Currently Processing Your Payment
<?php } ?>
@stop
@section('step') data-step-number="step8" @stop
@section('wizardcontent')

	@include('errors')


	<center><h2 style="color:red">THANK YOU!</h2>
	
	<center><b>For Allowing {{COMPANY_NAME}} To Assist With Your Federal Student Loans</b></center><br/>
	
	@section('wizardform_open')
		{{ Form::open(array('url' => 'paymentupdate', 'class' => 'form-horizontal ajaxcheck processpayment paymentchecker1')) }}
	@stop
		
		<center><h2 style="color:red">DOWNLOAD YOUR FORMS NOW</h2></center>
		<center>
			<b>Your Forms Are Currently Being Generated.</b><br/>
			<b>Within 5 Minutes You Will Receive An Email With An Username And Password.</b><br/>
			<b>If You Have Not Recieved Your Email Within 5 Minutes Please Check Your Spam Folder.</b><br/>
			<b>Which Will Allow You To Download Your Forms &amp; Instructions At Your Convience.</b>
		</center>
		<br/>
		
		<div class="row">
			<div class="col-sm-4 col-sm-offset-3">
						<div style="text-align:right"><b>GENERATING FORMS</b> </div>
			</div>
			<div class="col-sm-5">
				<div class="progress">
					<div class="progress-bar progress-bar-danager" id="progressbar3m" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">0%</div>
				</div>
			</div>
		</div>
		
	
 	  
	  @section('wizardform_close')
		{{ Form::close() }}
	 @stop

	 <script>
	 jQuery('*[data-step-number="step8"]').ScrollTo();

	 function countdown(callback) {


	 	var bar = document.getElementById('progressbar3m'),
	 	time = 0;

	 	@if (!$instantly)
	 	var max = 5*60
	 	@else
	 	var max = 10;
	 	@endif


	 	var CheckTimer = setInterval(function() {
	 		time++;
	 		bar.style.width = Math.floor(100 * time / max) + '%';
	 		bar.innerHTML = Math.floor(100 * time / max) + '%';
	 		jQuery(bar).data('aria-valuenow', Math.floor(100 * time / max));

	 		if (time - 1 >= max) {
	 			clearInterval(CheckTimer);
	 			bar.innerHTML = '100%';
	 			// 600ms - width animation time

	 			callback && setTimeout(callback, 600);
	 		}
	 	},

	 	@if ($instantly)
	 	300

	 	@else

	 	1000

	 	@endif
	 	);
	 }

	 countdown(function() {
	 	jQuery('.paymentchecker1').submit();

	 });

		 function CheckPaymentStatus()
		 {
			 jQuery.get('{{ URL::to('/ajaxcheckpayment/'.Session::getId()) }}}', function(paymentResponse){
				 console.log(paymentResponse);

				 if (paymentResponse.status == "approved")
				 {
					 jQuery('.paymentchecker1').submit();
				 }
				 else
				 {
					 jQuery('#paymentStatusText').html(paymentResponse.status);
				 }
			 }, 'json');
		 }
	 </script>

@stop