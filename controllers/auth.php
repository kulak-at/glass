<?php

$subapp = $app['controllers_factory'];

$subapp->get('/',function() use ($app) {
	if($app['request']->get('code')) {
		$c = $app->google_client;
		$client = $c();
		$client->authenticate();
		$access_token = $client->getAccessToken();
		
		
		$client = $c();
		$client->setAccessToken($access_token);
		$idService = new Google_Oauth2Service(($client));
		
		$user = $idService->userinfo->get();
		$app['session']->set('user',$user);
		$app['session']->set('access_token',$client->getAccessToken());
		return $app->redirect('/');
		
	}
	$c = $app->google_client;
	$client = $c();
	return $app->redirect($client->createAuthUrl());
});

$subapp->get('/de',function() use ($app) {
	$app['session']->remove('user');
	return $app->redirect('/');
});

return $subapp;