<?php

$subapp = $app['controllers_factory'];

$subapp->get('/',function() use ($app) {
	return $app->view_name = 'list';
});

$subapp->get('/create/{name}',function($name) use ($app) {
	
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

return $subapp;
	