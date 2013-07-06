<?php

// this two lines will save lots of your blood and tears while debugging.
error_reporting(E_ALL);
ini_set('display_errors','On');


// getting enviroment variables
defined('_ENV') ||
	define('_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

$loader = require_once __DIR__ . '/vendor/autoload.php';

// autoloading project specifics.
$loader->add('Glass',__DIR__ . '/project');
$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__ . '/templates'
));
$app->view_name = '';
$app->view = new stdClass();

// registering providers
$app->register(new Silex\Provider\SessionServiceProvider());

// registering configuration
$app->register(new Igorw\Silex\ConfigServiceProvider(__DIR__."/config/config."._ENV.".yml"));

// Increment.pl specific service provider.
$app->register(new Glass\ProjectServiceProvider());



/*** MIDDLEWARES ***/

// middleware for rendering
$app->after(function($req,$res) use ($app) {
	
	if(!$req->isXmlHttpRequest() && $app->view_name){
		$res->setContent($app['twig']->render('views/'.$app->view_name.'.twig', (array)$app->view));
	}
});
//
//$loggedOnly = function() use ($app) {
//	$user = $app['session']->get('user');
//	if ($user === null) {
//		return $app->redirect('/login');
//	}
//	$app->view->logged = true;
//	$app->view->user = $user;
//};
//
//$notLoggedOnly = function() use ($app) {
//	$user = $app['session']->get('user');
//	if ($user !== null) {
//		return $app->redirect('/');
//	}
//	$app->view->logged = false;
//};

/*** APPLICATION ***/

$app->mount('/',include __DIR__ . '/controllers/app.php');
$app->mount('/auth',include __DIR__ . '/controllers/auth.php');

return $app;

