<?php

$subapp = $app['controllers_factory'];

$subapp->get('/',function() use ($app) {
	return $app->view_name = 'list';
});

return $subapp;