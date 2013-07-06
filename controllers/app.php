<?php

$subapp = $app['controllers_factory'];

$subapp->get('/',function() use ($app) {
	return $app->view_name = 'list';
});

$subapp->post('/create',function() use ($app) {
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

        $app->view->id = $id;
        
        $db = new Glass\Db\Lista();
        
        $list = $db->getList($id);
        
        $app->view->name = $list["list_name"];
        $app->view->elements = $db->getListElements($id);
    
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
	