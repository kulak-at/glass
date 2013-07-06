<?php

namespace Glass;

//require_once 'mirror-client.php';
require_once __DIR__ . '/../google-api-php-client/src/Google_Client.php';
require_once __DIR__.'/../google-api-php-client/src/contrib/Google_Oauth2Service.php';
require_once __DIR__.'/../google-api-php-client/src/contrib/Google_MirrorService.php';

use Silex\Application;
use Silex\ServiceProviderInterface;

class ProjectServiceProvider implements ServiceProviderInterface {

	public function register(Application $app) {

		Db::setConfig($app['config']['db']);
		$app->db = new DbProvider();
		
		$gconf = $app['config']['google'];
		
		$app->google_client = function() use ($gconf,$app) {

			$client = new \Google_Client();
			$client->setUseObjects(true);
			$client->setApplicationName('ProductList');

			$client->setClientId($gconf['id']);
			$client->setClientSecret($gconf['secret']);
			$client->setDeveloperKey($gconf['key']);
			$client->setRedirectUri($app['config']['base_url'] . "/auth");

			$client->setScopes(array(
	//    'https://www.googleapis.com/auth/glass.timeline',
	//    'https://www.googleapis.com/auth/glass.location',
		'https://www.googleapis.com/auth/userinfo.email',
		'https://www.googleapis.com/auth/userinfo.profile'));
			return $client;
		};
		
	}
    
    public function boot(Application $app)
    {
        
    }
}