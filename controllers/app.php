<?php

$subapp = $app['controllers_factory'];

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

return $subapp;
	