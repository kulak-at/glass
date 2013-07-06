<?php

$subapp = $app['controllers_factory'];

$subapp->get('/',function() use ($app) {
	return 'TODO';
});

return $subapp;