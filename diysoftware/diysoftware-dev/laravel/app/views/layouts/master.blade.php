<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DIYSolutions - Customers</title>
    <script src="/packages/jslibs/pace.js"></script>
    <!-- Bootstrap -->
<!--    <link href="http://diy.ingeniousdigital.com/packages/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
	<link href="/packages/wizard/css/bootstrap-united.css" rel="stylesheet" />  
	<link href="/packages/wizard/css/pace.css" rel="stylesheet" />  
<!--	<link href="/packages/wizard/css/gsdk-base.css" rel="stylesheet" />  -->
    
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://diy.ingeniousdigital.com/packages/bootstrap/js/bootstrap.min.js"></script>
    <script src="/packages/jslibs/jquery-scrollto.js"></script>
    <script src="/packages/jslibs/loader.js"></script>
	<link rel="stylesheet" href="/packages/wizard/css/site.css" />
	
<script src="/packages/jslibs/jquery.maskedinput.js" type="text/javascript"></script>	
<script type="text/javascript" src="//www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
  </head>
  <body>

  
  <!-- navigation -->
    <nav class="navbar navbar-fixed-top navbar-inverse" role="navigation" style="display:none">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">DIYSolutions</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="/">New Client</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!-- /.nav-collapse -->
      </div><!-- /.container -->
    </nav><!-- /.navbar -->

    <div style="height: 55px;padding:8px 0px 10px 0px;text-align:center;background-color:#000;">
	    <img src="/packages/static/logo-2.png">
    </div>

    

    <!-- Container -->
    <div class="container"> 
 		
    	<div class="row">
    		<div class="col-sm-10 col-sm-offset-1">
    			<?php if (!isset($hide_greeting) || !$hide_greeting) { ?>
    			
	    		<h4 style="text-align:center;color: red;text-transform:uppercase;font-size:20px;font-weight:bold;">Congratulations On Taking The 1st Step And Choosing DIY Student Loan Services To Get
	    		Your Lower Federal Student Loan Re-Payment Options.<br/> Lets Get Started</h4>    		
	    		<?php } ?>
	    		
	    		<?php if (!isset($hide_returning_customer) || !$hide_returning_customer): ?>    		
	    		<center>
	    		<input type="checkbox" id="returning_check_box" name="returning_check_box" {{ Session::get('ShowReturnPanel') ? 'checked="checked"' : '' }}> <label for="returning_check_box" style="font-size:18px;color:blue;"><u><b>Returning Customer?</b></u></label></center>
	    		<?php endif; ?>
    	    </div>
    	</div>

       	
		<div class="returning_client_container">
		
		       <?php
		       if (!Request::Ajax())
		       {
		       	?>
		       	<div id="returning_client" class="{{ Session::get('ShowReturnPanel') ? '' : 'hidden' }}">
		       	@include('returningclient')
		       	</div>       	
		      <?php } ?>
		      
		      
		</div>    	
       <div class="form-content">
       		<?=$content?>
	     </div>
         <div class="mask hidden"><div id="intro-loader"></div></div>
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

    				//jQuery('*[data-step-number="step3"]').ScrollTo();
    				jQuery('.wizard-content',beforeelemet).slideUp(function(){ jQuery(element).ScrollTo(); });

    			}
    			//    			jQuery(beforeelemet).scrollUp();
    		}
    	}

    }
    <?php
    if (!Request::Ajax())
    {
    	?>
    	jQuery('#returning_check_box').on('change',function(){
    		var checked = jQuery(this).is(':checked');

    		if (checked)
    		{
    			jQuery('#returning_client').removeClass('hidden');
    		}
    		else
    		jQuery('#returning_client').addClass('hidden');

    	});
    	jQuery('.form-content').on('submit', 'form.ajaxcheck', function(e){
    		e.preventDefault();
    		form = this;
    		// submit the form, we've checked the box, so !
    		// we need to post the form:
    		var formfields = jQuery(form).serialize();

    		var data = '';

    		//get the container.
    		var parentNode = jQuery(form).parents('.wizard-container2');

    		// Find if they have any alerts and remove them.
    		jQuery('.alert', parentNode).remove();


    		// Show a Loader.
    		var ajaxLoading = new ajaxLoader(parentNode, {classOveride: 'blue-loader', bgColor: '#000', opacity: '0.6'});




    		Pace.track(function(){
    			jQuery.post(jQuery(form).attr('action'), formfields, function(data){
    				var html = jQuery(data);
    				var content = jQuery('.form-content .stepdef', html);
    				var step_number = jQuery('.stepdef',html).data('step-number');
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
    			}).always( function(){
    				ajaxLoading.remove();
    			});


    			jQuery('.form-control', form).each(function(){ jQuery(this).addClass('disabled',true); });
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
    	
    	
    	function LoadStep(stepurl)
    	{
    		Pace.track(function(){
    			jQuery.get(stepurl, function(data){
    				var html = jQuery(data);
    				var content = jQuery('.form-content .stepdef', html);
    				var step_number = jQuery('.stepdef',html).data('step-number');
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
    			});  
    		}); 		
    	}
    	
    	<?php } ?>


    	jQuery('.form-content').on('click','.mywizardsteptext', function(){
    		// toggle the content

    		var parentNode = jQuery(this).parents('.stepdef');

    		jQuery('.wizard-content',parentNode).toggle();
    		prevElem.data('hidden', jQuery('.wizard-content',parentNode).is(':visible'));


    	});
    	
    	
    </script>
	
    
    
    </body>
</html>