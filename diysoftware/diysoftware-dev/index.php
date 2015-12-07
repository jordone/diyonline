<?php
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

$site = $_SERVER['HTTP_HOST'];
switch (strtolower($site))
{
	case 'app2.diy-sls.com':
		define('LEADTRACK_DOMAIN_KEY', 'DEVELOPEMENT_SITE_APP2');
		break;
	case 'diy.ingeniousdigital.com':
	case 'www.diy.ingeniousdigital.com':
		define('LEADTRACK_DOMAIN_KEY', 'DEVELOPEMENT_SITE_IGD');
		break;
	case 'app.diy-sls.com':
	case 'www.app.diy-sls.com':
		define('LEADTRACK_DOMAIN_KEY', 'MainSite');
		break;
		// code named Aged
		// 
	case 'app.diystudentloanservices.com':
	case 'www.app.diystudentloanservices.com':	
		define('LEADTRACK_DOMAIN_KEY', 'Aged');
		
		break;
	case 'app.diystudentloansonline.com':
	case 'www.app.diystudentloansonline.com':
		define('LEADTRACK_DOMAIN_KEY', 'PPC');
		break;
       
        case 'app.diyuga.com':
	case 'www.app.diyuga.com':
		define('LEADTRACK_DOMAIN_KEY', 'UGA');
		break;
		
	case 'app.diytamu.com':
	case 'www.app.diytamu.com':
		define('LEADTRACK_DOMAIN_KEY', 'TAMU');
		break;
		
	default:
		define('LEADTRACK_DOMAIN_KEY', 'NotSet_'.$site.'');
		break;
		
}
/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require __DIR__.'/../laravel/bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let's turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight these users.
|
*/

$app = require_once __DIR__.'/../laravel/bootstrap/start.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can simply call the run method,
| which will execute the request and send the response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have whipped up for them.
|
*/

$app->run();