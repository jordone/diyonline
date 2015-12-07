<?php
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */

### Require our defines.php (this sets up all of th variables for us).
if (file_exists(dirname(__FILE__).'/defines.php'))
require_once dirname(__FILE__).'/defines.php';

if (file_exists(dirname(__FILE__).'/defines.default.php'))
require_once 'defines.default.php';

### FIX BUG WITH REMOTE_ADDR
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && $_SERVER['REMOTE_ADDR'] == '72.52.202.16')
    $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_FORWARDED_FOR'];


$site = $_SERVER['HTTP_HOST'];

//$_SERVER['DEBUG_MODE'] = true;

if (!defined("LEADTRACK_DOMAIN_KEY"))
{
	switch (strtolower($site))
	{
		/**
     * DEV APP
     */
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


		default:
			define('LEADTRACK_DOMAIN_KEY', 'NotSet_'.$site.'');
			break;

	}
}
/**
 * @param bool|false $nl2br - if true converts New lines to <br/>
 * @return string
 */
function get_disclosure_text($nl2br=false) 
{
    $file = @file_get_contents(dirname(__FILE__).'/disclosure.txt');

    if ($nl2br) return nl2br($file);

    return $file;
}
function get_privacypolicy_text($nl2br=false)
{
    $file = @file_get_contents(dirname(__FILE__).'/privacy_policy.txt');

    if ($nl2br) return nl2br($file);

    return $file;
}

function verify_office_password($office_password)
{
	$password_file = dirname(__FILE__) .'/'.basename(strtolower(str_replace('www.','',$_SERVER['HTTP_HOST'])).'.office_passwords.txt');
	$current_day_3 = strtolower( date(  'D' ));
	$current_day = strtolower( date( 'l' ));

	if (!file_exists($password_file))
	{
		$password_file = 'default.office_passwords.txt';
	}

	$passwords = file($password_file);

	foreach ($passwords as $password)
	{
		$password = trim($password);
		if (strlen($password) <= 3) continue;

		// check if we're split by a colon.
		if (  ($day_length = strpos($password, ':')) !== false )
		{
			$pass_day = strtolower(  substr($password, 0, $day_length) );
			$pass = substr($password, $day_length+1);

			if ( (strlen($pass_day) == 3 && $pass_day != $current_day_3) && ($pass_day != $current_day))
			continue;



			$password = $pass;
		}
		if ($password == $office_password)
		return true;
	}

	return false;
}

if (is_dir(dirname(__FILE__).'/../laravel'))
$laravel_path = dirname(__FILE__).'/../laravel';

else
{
	$laravel_path = "/home/diysls/public_html/diysoftware/laravel";
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
if (!defined('LARAVEL_START'))
require_once $laravel_path.'/bootstrap/autoload.php';

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

if (!isset($app))
$app = require_once $laravel_path.'/bootstrap/start.php';

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

