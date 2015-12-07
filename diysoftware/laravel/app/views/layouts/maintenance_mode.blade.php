<?php defined('WEBSITE_STYLE_VERSION') or define('WEBSITE_STYLE_VERSION', md5_file('style.css')); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo ( defined("WEBSITE_TITLE") ? WEBSITE_TITLE : "DIY STUDENT LOAN SERVICES" ); ?></title>
    <script src="{{ URL::to('packages/jslibs/pace.js') }}"></script>
    <!-- Bootstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
<!--    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" rel="stylesheet">-->
    
<!--	<link href="{{ URL::to('packages/wizard/css/bootstrap-united.css') }}" rel="stylesheet" />  -->
	<link href="{{ URL::to('packages/wizard/css/pace.css?v=4') }}" rel="stylesheet" />
    <link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.css" rel="stylesheet">
    <link href="{{ URL::to('packages/wizard/css/bootstrap.min-flat.css') }}" rel="stylesheet">
    
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
	<link rel="stylesheet" href="{{ URL::to('packages/wizard/css/site.css?v=2') }}" />

	<link href="{{ URL::to('style.css?v='.WEBSITE_STYLE_VERSION) }}" rel="stylesheet" />  

	
<!--	Date Picker Stylesheets-->
	<link href="{{ URL::to('packages/datepicker/css/bootstrap-datepicker3.min.css?v=1') }}" rel="stylesheet" />	
    <script src="{{ URL::to('packages/datepicker/js/bootstrap-datepicker.min.js?v=1') }}"></script>
    <script src="{{ URL::to('packages/jslibs/jquery.number.js?v=1') }}"></script>
   
    
<script src="{{ URL::to('packages/jslibs/jquery.maskedinput.js') }}" type="text/javascript"></script>	
<script type="text/javascript" src="//www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
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

    <div style="padding:8px 0px 10px 0px;text-align:center;" id="logo_container"><center>
    <?php
    if (defined('WEBSITE_LOGO')) {
    	?>
	    <img src="{{ URL::to(WEBSITE_LOGO) }}" class="img-responsive">
    	<?php
    } else { 
    	?>
	    <img src="{{ URL::to('packages/static/logo.png') }}">
		<?php
    }
    ?>	    </center>
    </div>

    

    <!-- Container -->
    <div class="container"> 
 		
    	<div class="row">
    		<div class="col-sm-10 col-sm-offset-1">
    		
    		
    		@include('maintenance_step')
    		
    		
    		</div>
    	</div>
   </div>
 </body>
 </html>
 