<?php

$subapp = $app['controllers_factory'];

$subapp->get('/',function() use ($app) {
	return $app->json(array('Hello Glass'));
});

return $subapp;