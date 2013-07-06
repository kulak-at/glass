<?php

namespace Glass;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ProjectServiceProvider implements ServiceProviderInterface {
	public function register(Application $app)
    {
	   
	   Db::setConfig($app['config']['db']);
	   $app->db = new DbProvider();
	   
    }
    
    public function boot(Application $app)
    {
        
    }
}