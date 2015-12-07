<?php

/*
|--------------------------------------------------------------------------
| Register The Laravel Class Loader
|--------------------------------------------------------------------------
|
| In addition to using Composer, you may use the Laravel class loader to
| load your controllers and models. This is useful for keeping all of
| your classes in the "global" namespace without Composer updating.
|
*/

ClassLoader::addDirectories(array(

	app_path().'/commands',
	app_path().'/controllers',
	app_path().'/models',
	app_path().'/database/seeds',
	app_path().'/classes',

));

Form::macro('stateSelect', function($name = "state", $selected = null, $options = array()) {
    $states = array(
        '' => "",
        'AL' => "Alabama",
        'AK' => "Alaska",
        'AZ' => "Arizona",
        'AR' => "Arkansas",
        'CA' => "California",
        'CO' => "Colorado",
        'CT' => "Connecticut",
        'DE' => "Delaware",
        'DC' => "District Of Columbia",
        'FL' => "Florida",
        'GA' => "Georgia",
        'HI' => "Hawaii",
        'ID' => "Idaho",
        'IL' => "Illinois",
        'IN' => "Indiana",
        'IA' => "Iowa",
        'KS' => "Kansas",
        'KY' => "Kentucky",
        'LA' => "Louisiana",
        'ME' => "Maine",
        'MD' => "Maryland",
        'MA' => "Massachusetts",
        'MI' => "Michigan",
        'MN' => "Minnesota",
        'MS' => "Mississippi",
        'MO' => "Missouri",
        'MT' => "Montana",
        'NE' => "Nebraska",
        'NV' => "Nevada",
        'NH' => "New Hampshire",
        'NJ' => "New Jersey",
        'NM' => "New Mexico",
        'NY' => "New York",
        'NC' => "North Carolina",
        'ND' => "North Dakota",
        'OH' => "Ohio",
        'OK' => "Oklahoma",
        'OR' => "Oregon",
        'PA' => "Pennsylvania",
        'RI' => "Rhode Island",
        'SC' => "South Carolina",
        'SD' => "South Dakota",
        'TN' => "Tennessee",
        'TX' => "Texas",
        'UT' => "Utah",
        'VT' => "Vermont",
        'VA' => "Virginia",
        'WA' => "Washington",
        'WV' => "West Virginia",
        'WI' => "Wisconsin",
        'WY' => "Wyoming"
    );

    return Form::select($name, $states, $selected, $options);
});

/*
|--------------------------------------------------------------------------
| Application Error Logger
|--------------------------------------------------------------------------
|
| Here we will configure the error logger setup for the application which
| is built on top of the wonderful Monolog library. By default we will
| build a basic log file setup which creates a single file for logs.
|
*/

Log::useFiles(storage_path().'/logs/laravel.log');

/*
|--------------------------------------------------------------------------
| Application Error Handler
|--------------------------------------------------------------------------
|
| Here you may handle any errors that occur in your application, including
| logging them or displaying custom views for specific errors. You may
| even register several error handlers to handle different types of
| exceptions. If nothing is returned, the default error view is
| shown, which includes a detailed stack trace during debug.
|
*/

App::error(function(Exception $exception, $code)
{
	Log::error($exception);
	
	$error_logs_dir = storage_path().'/logs';
	 
	// send the error to me and pascal
	$error = md5($exception->getMessage() . ' 2554');

	$error_file = $error_logs_dir.'/error_'.$error.'.log';
	
	if (!file_exists($error_file))
	{
		
		file_put_contents($error_file, $exception->getMessage());
		
		$fileNumber = Session::get('fileNumber');

		$error_txt = "File #: ".$fileNumber."\n";	
		$error_txt .= "File: ".$exception->getFile().":".$exception->getLine()."\n\n";
			
		$error_txt .= nl2br($exception->getMessage());
		
		# PRZ
		@mail('paulzimm@gmail.com', 'Error occured: '.$exception->getFile(), $error_txt, "From: error@".$_SERVER['HTTP_HOST']);
	}
	
	
	
});

App::missing(function($exception)
{
	if (Request::Ajax())
		$layout = \View::make('layouts.ajax');
	else
		$layout = \View::make('layouts.master');

	$layout->content = \View::make('error404');

	return Response::make($layout, 404);

});

/*
|--------------------------------------------------------------------------
| Maintenance Mode Handler
|--------------------------------------------------------------------------
|
| The "down" Artisan command gives you the ability to put an application
| into maintenance mode. Here, you will define what is displayed back
| to the user if maintenance mode is in effect for the application.
|
*/

App::down(function()
{
	return Response::make("Be right back!", 503);
});

/*
|--------------------------------------------------------------------------
| Require The Filters File
|--------------------------------------------------------------------------
|
| Next we will load the filters file for the application. This gives us
| a nice separate location to store our route and application filter
| definitions instead of putting them all in the main routes file.
|
*/

require app_path().'/filters.php';
require app_path().'/filters.php';
