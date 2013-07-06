<?php

$subapp = $app['controllers_factory'];

$subapp->before(function() use ($app) {
	if(!$app['session']->get('user')) {
		return $app->redirect('/auth');
	}
	$app->view->user = $app['session']->get('user');
	$c = $app->google_client;
	$client = $c();
	$client->setAccessToken($app['session']->get('access_token'));
	$app->mirror = new Google_MirrorService($client);
});

$subapp->get('/',function() use ($app) {
	return $app->view_name = 'list';
});

$subapp->post('/create/',function() use ($app) {
	$name = $app['request']->get('listname');
	$dbLista = new Glass\Db\Lista();
	$cardId = "dupa08";
	try {
		$newid = $dbLista->addList($name, $cardId);
		return $app->redirect('/list/' . $newid);
	}
	catch(\Exception $e) {
		$return = array(
			'status' => 1,
			'message' => 'Error: ' . $e->getMessage()
		);
	}

});

$subapp->get('/list/{id}',function($id) use ($app) {
    
        $app->view->dupa = "dupa";
        $app->view->id = $id;
    
         return $app->view_name = 'list_view';
});

$subapp->post('/list/{listId}/element',function($listId) use ($app) {
	$name = $app['request']->get('prod_name');
	$descr = $app['request']->get('descr');
	$qty = $app['request']->get('quantity');
	$dbElement = new Glass\Db\Element();
	try {
		$newid = $dbElement->addElement($listId, $name, $descr, $qty);
		return $app->redirect('/list/' . $listId);
	}
	catch(\Exception $e) {
		$return = array(
			'status' => 1,
			'message' => 'Error: ' . $e->getMessage()
		);
	}

});

return $subapp;
	