<?php defined('WEBSITE_STYLE_VERSION') or define('WEBSITE_STYLE_VERSION', md5_file('style.css')); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo ( defined("WEBSITE_TITLE") ? WEBSITE_TITLE : "DIY STUDENT LOAN SERVICES" ); ?></title>
	<link rel="stylesheet" href="{{ URL::to('packages/wizard/css/site.css?v=2') }}" />   
    <link href="{{ URL::to('packages/wizard/css/bootstrap.min-flat.css') }}" rel="stylesheet">
	<link href="{{ URL::to('packages/wizard/css/pace.css?v=4') }}" rel="stylesheet" />	
	<link href="{{ URL::to('packages/fa/css/font-awesome.min.css?v=4') }}" rel="stylesheet" />
	<link href="{{ URL::to('packages/datepicker/css/bootstrap-datepicker3.min.css?v=1') }}" rel="stylesheet" />	
	
    <script src="{{ URL::to('packages/jslibs/pace.js') }}"></script>   

    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ URL::to('packages/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::to('packages/jslibs/jquery-scrollto.js') }}"></script>
    <script src="{{ URL::to('packages/jslibs/loader.js') }}"></script>
	<link href="{{ URL::to('style.css?v='.WEBSITE_STYLE_VERSION) }}" rel="stylesheet" />  
	
<!--	Date Picker Stylesheets-->
    <script src="{{ URL::to('packages/datepicker/js/bootstrap-datepicker.min.js?v=1') }}"></script>
    <script src="{{ URL::to('packages/jslibs/jquery.number.js?v=1') }}"></script>
   
    
<script src="{{ URL::to('packages/jslibs/jquery.maskedinput.js') }}" type="text/javascript"></script>	 
   <script src="{{ URL::to('packages/jslibs/phonenumbers.js?v=11') }}"></script>   
<script type="text/javascript" src="//www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>

	<script>

		var CurrentStepsLoaded = 0;

		var step1 = 1;
		var step2 = 2;
		var step3 = 4;
		var step4 = 8;
		var step5 = 16;
		var step6 = 32;
		var step7 = 64;
		var step8 = 128;
		var step9 = 256;
		var step10 = 512;

		var page_crm=step8;
		var page_privacypolicy = step9;
		var page_loans=step10;

	</script>
	<style>
		@if (isset($_GET['textalign']))
		.text-center {
			text-align: {{ $_GET['textalign'] }} !important;
		}
		@endif
		@if (isset($_GET['fontsize']))
		h3, h4{
			font-size: {{{ Input::get('fontsize') }}}px !important;
		}
		@else
		h3, h4{
			font-size: 18px;
		}
		@endif
		.page-title {
			font-size: 28px !important;
		}

		.btn-file {
			position: relative;
			overflow: hidden;
		}
		.btn-file input[type=file] {
			position: absolute;
			top: 0;
			right: 0;
			min-width: 100%;
			min-height: 100%;
			font-size: 100px;
			text-align: right;
			filter: alpha(opacity=0);
			opacity: 0;
			outline: none;
			background: white;
			cursor: inherit;
			display: block;
		}
		.fa-spin-custom, .glyphicon-spin {
			-webkit-animation: spin 1000ms infinite linear;
			animation: spin 1000ms infinite linear;
		}
		@-webkit-keyframes spin {
			0% {
				-webkit-transform: rotate(0deg);
				transform: rotate(0deg);
			}
			100% {
				-webkit-transform: rotate(359deg);
				transform: rotate(359deg);
			}
		}
		@keyframes spin {
			0% {
				-webkit-transform: rotate(0deg);
				transform: rotate(0deg);
			}
			100% {
				-webkit-transform: rotate(359deg);
				transform: rotate(359deg);
			}
		}
	</style>
  </head>
  <body>

  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-58838977-2', 'auto');
  ga('send', 'pageview');

</script>
  
  
  <!-- navigation -->

    <div style="padding:8px 0px 12px 0px;" id="logo_container">
	<div  class="container-fluid">
	<div class="row">
			<div class="col-sm-2 col-md-2 col-md-offset-2 col-xs-3">
				@if (defined('WEBSITE_LOGO'))
	  			 <img align="right" src="{{ URL::to(WEBSITE_LOGO) }}" class="img-responsive">
	  			 @endif
	        </div>

			<div class="col-sm-11 col-md-8 col-xs-11 text-center">
			@if (defined('MAIN_TEXT_TOP'))
					<h4 style="text-align:center;color: #2d3cff;margin:0;padding:0;text-transform:uppercase;font-size:18px;font-weight:bold;">Office Use Only</h4>
			@endif
    		</div>
    	</div>


    </div></div>



    

    <!-- Container -->
    <div class="container"> 


       <div class="form-content">

       		<?=$content?>
	     </div>
         <div class="mask hidden"><div id="intro-loader"></div></div>



		<br/>
		<br/>




		@if (Request::secure())
			<div class="text-center">
				<img class="img-responsive" src="http://www.diy-sls.com/wp-content/uploads/2015/02/ssl-300x134.png" alt="ssl" width="150" height="67" />
			</div>
		@else
			<div class="text-center text-muted">
				{{--<i class="fa fa-warning"></i>	Insecure connection detected <a href="https://{{ $_SERVER['HTTP_HOST'] }}/{{ Route::getCurrentRoute()->getPath() }}">Go Secure!</a>--}}
			</div>
		@endif
    </div>



  <script>
	  function HidePreviousStep(element)
	  {
		  var beforeelemet= jQuery(element).prev();


		  if (typeof beforeelemet !== 'undefined')
		  {
			  if (beforeelemet.length >= 1)
			  {
				  if (beforeelemet.data('step-number'))
				  {
					  var stepnumber = beforeelemet.data('step-number');
					  jQuery('.wizard-content',beforeelemet).slideUp(function(){ jQuery(element).ScrollTo(); });
				  }
			  }
		  }

	  }
			  <?php
              if (!Request::Ajax())
              {
                  ?>

//                  var CheckBrowserIdInterval = setInterval(function(){ jQuery(document).trigger('CheckIsDocumentOwner'); }, 5500);

	  function DisplayWrongBrowserDialog()
	  {
		  jQuery('#browsermismatchconfirm').modal('show');
	  }

	  jQuery('#browsermismatchconfirm').on('show.bs.modal', function(){
		  clearInterval(CheckBrowserIdInterval);
	  });

	  jQuery('#browsermismatchconfirm').on('hidden.bs.modal', function(){
		  clearInterval(CheckBrowserIdInterval);
		  CheckBrowserIdInterval = setInterval(function(){ jQuery(document).trigger('CheckIsDocumentOwner'); }, 5500);
	  });


	  function RegisterThisBrowserSession() {
		  var postargs = {'_token': '{{ Session::token() }}', 'browser': 'register!'};

		  jQuery.post('{{ Url::to('/RegisterBrowserSession') }}',postargs, function(data){

			  // tell our browser which steps to reload
			  loadstepsplz = [CurrentStepsLoaded];
			  loadstepz = true;

			  // trigger the step reload functionality
			  jQuery(document).trigger('LoadStepCompleted');
		  });
	  }


	  jQuery(document).bind('CheckIsDocumentOwner', function(){
		  if (jQuery('#dontcheckagain').is(':checked')) return false;

		  jQuery.get('{{ Url::to('/IsDocumentOwner/') }}', function(result){
			  if (result.compare_result == false)
			  {
				  DisplayWrongBrowserDialog();
			  }
		  }, 'json');
	  });

	  jQuery('#register_this_browser').on('click', function(e){

		  RegisterThisBrowserSession();
		  jQuery('#browsermismatchconfirm').modal('hide');
		  return false;
	  });

	  jQuery('#returning_check_box').on('change',function(){
		  var checked = jQuery(this).is(':checked');

		  if (checked)
		  {
			  jQuery('#returning_client').removeClass('hidden');
		  }
		  else
			  jQuery('#returning_client').addClass('hidden');

	  });

	  $(document).on('change', '.btn-file :file', function() {
		  var input = $(this),
				  numFiles = input.get(0).files ? input.get(0).files.length : 1,
				  label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		  input.trigger('fileselect', [numFiles, label]);
	  });

	  jQuery('.form-content').on('submit', 'form.ajaxcheck', function(e){
		  e.preventDefault();
		  form = this;
		  // submit the form, we've checked the box, so !
		  // we need to post the form:

		  if(jQuery("#fafsapin").val())
		  {
			  var formfields = new FormData(jQuery(form)[0]);
			  var pd = false;
			  var ct = false;
		  }else{
			  var formfields = jQuery(form).serialize();
			  var pd = true;
			  var ct = 'application/x-www-form-urlencoded; charset=UTF-8';
		  }



		  // debugger;
		  // jQuery.each(jQuery('#inputFile')[0].files, function(i, file) {
		  // data.append('file, file);
		  // });


		  var data = '';

		  //get the container.
		  var parentNode = jQuery(form).parents('.wizard-container2');

		  // Find if they have any alerts and remove them.
		  jQuery('.alert', parentNode).remove();


		  // Show a Loader.
		  var ajaxLoading = new ajaxLoader(parentNode, {classOveride: 'blue-loader', bgColor: '#000', opacity: '0.6'});




		  Pace.track(function(){

			  jQuery.ajax({
				  url: jQuery(form).attr('action'),
				  method: "POST",
				  data: formfields,
				  processData: pd,
				  contentType: ct,
				  success: function(data){
					  var html = jQuery(data);
					  var content = jQuery('.form-content .stepdef', html);
					  var step_number = jQuery('.stepdef',html).data('step-number');

					  if (typeof step_number == "undefined")
					  {
						  console.log("Error: Step number is undefined.");
						  console.log(data);
					  }
					  //    				alert('Step Number: '+step_number);
					  console.log('Step Number: '+step_number+' loaded.');
					  // if the container exists already we need to append our content before it, then remove the old one.
					  if (jQuery('.stepdef[data-step-number="'+step_number+'"]').length)
					  {
						  console.log('.stepdef[data-step-number="'+step_number+'"] has been found. Updating!');
						  var stepfound = jQuery('.stepdef[data-step-number="'+step_number+'"]');

						  stepfound.addClass('hidden');

						  stepfound.before(content);

						  stepfound.remove();

						  HidePreviousStep('.stepdef[data-step-number="'+step_number+'"]');
						  jQuery('*[data-step-number="'+step_number+'"]').ScrollTo();

					  }
					  else
					  {
						  console.log('.stepdef[data-step-number="'+step_number+'"] not found');

						  jQuery('.form-content:first').append(content);

						  HidePreviousStep('.stepdef[data-step-number="'+step_number+'"]');
						  jQuery('*[data-step-number="'+step_number+'"]').ScrollTo();
					  }
				  }
			  }).fail(function(){
				  alert('An error occured. Please try again later.');
			  }).always( function(){
				  ajaxLoading.remove();
			  });


//			  jQuery('.form-control', form).each(function(){ jQuery(this).addClass('disabled',true); });

		  });

		  return false;

	  });
	  jQuery('.form-content').on('click', '.disabled', function(){
		  var form = jQuery(this).parents('.form-content');
		  // try to find the form submitter
		  var formsubmitter = jQuery('.formsubmitter', form);

		  if (formsubmitter.length)
		  {
			  // see if it is checked
			  if (jQuery(formsubmitter).is(':checked'))
			  {
				  // uncheck it
				  jQuery(formsubmitter).removeProp('checked').trigger('change');
			  }
		  }


	  });

	  jQuery('.form-content').on('change','.formsubmitter', function(){
		  var checked = jQuery(this).is(':checked');
		  var form = jQuery(this).parents('form');

		  // disable the fields if they have checked the box (meaning this is the data we've submitted to the server.)
		  // default: steps will be disabled unless they uncheck this box.
		  if (checked)
		  {
			  form.submit();
		  }
		  else
		  {
			  jQuery('.form-control', form).each(function(){ jQuery(this).removeClass('disabled'); });
		  }

		  //lets get a serialized list of form fields.


	  });


	  function LoadStep(stepurl, container)
	  {
          if (typeof closure == "undefined") var closure = function(){};

		  if (typeof container != "undefined")
			  var parentNode = jQuery(this);
		  // start the loading signal from the active window.
		  //get the container.
		  var parentNode = jQuery('.wizard-content').not(':hidden');
		  // Find if they have any alerts and remove them.

		  if (parentNode.length < 1) var parentNode = jQuery('.wizard-content:last');

		  if (parentNode.length < 1) {
			  jQuery('.container:first').append('<div class="wizard-content"><div class="form-content"></div></div>');
			  var parentNode = jQuery('.wizard-content').not(':hidden');
		  }

//		  console.log('Load Page Activated. Page Loading: '+stepurl);
//		  console.log('Parent Node Length: '+parentNode.length);


		  // Show a Loader.
		  var ajaxLoading = new ajaxLoader(parentNode, {classOveride: 'blue-loader', bgColor: '#000', opacity: '0.6'});


		  Pace.track(function(){



			  jQuery.get(stepurl, function(data){
				  var html = jQuery(data);
				  var content = jQuery('.form-content .stepdef', html);
				  var step_number = jQuery('.stepdef',html).data('step-number');
				  var defined_route = jQuery('.stepdef',html).data('route');
                  var current_route = stepurl;

				  //    				alert('Step Number: '+step_number);
				  // if the container exists already we need to append our content before it, then remove the old one.
				      if (jQuery('.stepdef[data-step-number="'+step_number+'"]').length)
				      {
					  var stepfound = jQuery('.stepdef[data-step-number="'+step_number+'"]');
					  stepfound.addClass('hidden');
					  stepfound.before(content);
					  stepfound.remove();

					  HidePreviousStep('.stepdef[data-step-number="'+step_number+'"]');
					  jQuery('*[data-step-number="'+step_number+'"]').ScrollTo();

				     }
				    else
				    {
					  jQuery('.form-content:first').append(content);

					  HidePreviousStep('.stepdef[data-step-number="'+step_number+'"]');
					  jQuery('*[data-step-number="'+step_number+'"]').ScrollTo();
				    }
			         })
					  .fail(function(){
						  alert('An error occured. Please try again later.');
					  })
					  .always(function(){
						  ajaxLoading.remove();
						  jQuery(document).trigger('LoadStepCompleted');

                          jQuery('*[data-toggle="loadstep"]').each(function(){
                              var whenrouteloads = jQuery(this).data('toggle-route-load');
                              var action = jQuery(this).data('do');

                              if (typeof whenrouteloads != 'undefined')
                              {
                                  if (typeof action == 'undefined' || action == 'remove')
                                  jQuery(this).remove();
                                  else if (action == 'toggle')
                                  jQuery(this).toggle();

                              }
                          });
					  });
		  });
	  }


	  <?php } ?>


      jQuery('.form-content').on('click','.mywizardsteptext', function(){
				  // toggle the content

				  var parentNode = jQuery(this).parents('.stepdef');

				  jQuery('.wizard-content',parentNode).toggle();
				  //    		prevElem.data('hidden', jQuery('.wizard-content',parentNode).is(':visible'));


			  });


	  var loadstepsplz = {{ json_encode(explode('|',Input::get('wl'))) }};
	  var loadstepz = true;


	  jQuery(document).bind('LoadStepCompleted', function(){
		  if (loadstepz == true)
		  {
			  console.log('Load Steps is: '+typeof loadstepsplz+'');

			  if (loadstepsplz.length)
			  {
				  var step_to_load = parseFloat(loadstepsplz.shift());
				  console.log('Loading BITWISE: '+step_to_load);

			  }
			  if (step_to_load >0)
			  {
				  if (step_to_load & step1)
				  {
					  step_to_load = step_to_load  ^ step1;
					  LoadStep('/step1');
				  }

				  else if (step_to_load & step2)
				  {
					  step_to_load = step_to_load  ^ step2;
					  LoadStep('/step2');
				  }
				  else if (step_to_load & step3)
				  {
					  step_to_load = step_to_load  ^ step3;
					  LoadStep('/step3');
				  }
				  else if (step_to_load & step4)
				  {
					  step_to_load = step_to_load  ^ step4;
					  LoadStep('/step4');
				  }

				  else if (step_to_load & step5)
				  {
					  step_to_load = step_to_load  ^ step5;
					  LoadStep('/step5');
				  }
				  else if (step_to_load & step6)
				  {
					  step_to_load = step_to_load  ^ step6;
					  LoadStep('/step6');
				  }
				  else if (step_to_load & step10)
				  {
					  step_to_load = step_to_load  ^ step10;
					  LoadStep('/stepshowloans');
				  }
				  else if (step_to_load & step7)
				  {
					  step_to_load = step_to_load  ^ step7;
					  LoadStep('/step7');
				  }
				  else if (step_to_load & step8)
				  {
					  step_to_load = step_to_load  ^ step8;
					  LoadStep('/crm');
				  }
				  else if (step_to_load & step9)
				  {
					  step_to_load = step_to_load  ^ step9;
					  LoadStep('/privacypolicy');
				  }
				  else
				  {
					  loadstepz = false;
					  console.log("Unable to load the the following bits: "+ loadstepsplz);
				  }

				  if (step_to_load > 0)
				  {
					  console.log('This instruction has more bits remaining, placing back at the top of our load array');
					  console.log("LoadSteps Bits Unshifted: "+step_to_load);
					  loadstepsplz.unshift(step_to_load);
				  }
			  }

		  }
	  });

	  @if (Session::get('accepted_disclosure') && Input::get('wl'))
           jQuery(document).trigger('LoadStepCompleted');
	  @endif



	$(function () {
		  $('[data-toggle="tooltip"]').tooltip()
	});

  </script>

	
	<script type="text/javascript">
	setTimeout(function(){var a=document.createElement("script");
	var b=document.getElementsByTagName("script")[0];
	a.src=document.location.protocol+"//script.crazyegg.com/pages/scripts/0030/4817.js?"+Math.floor(new Date().getTime()/3600000);
	a.async=true;a.type="text/javascript";b.parentNode.insertBefore(a,b)}, 1);
</script>
    
    
    </body>
</html>