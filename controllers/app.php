<?php

require_once '../project/google-api-php-client/src/Google_Client.php';

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

$subapp->post('/create',function() use ($app) {
	$name = $app['request']->get('listname');
	$dbLista = new Glass\Db\Lista();
	try {
		$new_timeline_item = new Google_TimelineItem();
		
		$html = '<link rel="stylesheet" href"https://mirror-api-playground.appspot.com/assets/css/base_style.css" />
			<article class="photo">
			 <img src="' . $app['config']['base_url'] .'Black-N-Red-Notebook-Bleedthrough.JPG" width="100%" height="100%">
				  <div class="photo-overlay"></div>
					 <section>
					  <p class="text-auto-size">' . $name . '</p>
					</section>
			</article>"';
		$new_timeline_item->setHtml($html);
	
		$addedItem = $app->mirror->timeline->insert($new_timeline_item);
		
		$cardId = $addedItem->getId();
		
		$newid = $dbLista->addList($name, $cardId);
		return $app->redirect('/list/' . $newid);
	}
	catch(\Exception $e) {
		$return = array(
			'status' => 1,
			'message' => 'Error: ' . $e->getMessage()
		);
		return $app->json($return);
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
	$type = $app['request']->get('type');
	$dbElement = new Glass\Db\Element();
	$dbList = new Glass\Db\Lista();
	try {
		$newid = $dbElement->addElement($listId, $name, $descr, $qty);
		$list = $dbList->getList($listId);
		
		$card = $app->mirror->timeline->get($list['card_id']);
		
		$pages = $card->getHtmlPages();
		
		
		if(!$pages)
			$pages = array();
		
		$pages[] = '<article>
			<h2>' . $name . '</h2>
			<h3>' . $descr . '</h3>
			<h4>Count: ' . $qty . '</h4>
		</article>';
		$card->setHtmlPages($pages);
		var_dump($card);
		$app->mirror->timeline->update($list['card_id'],$card);
		
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
	