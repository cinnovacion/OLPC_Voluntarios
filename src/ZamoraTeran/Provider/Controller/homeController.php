<?php

namespace ZamoraTeran\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class homeController implements ControllerProviderInterface {

	/**
	 * Returns routes to connect to the given application.
	 * @param Application $app An Application instance
	 * @return ControllerCollection A ControllerCollection instance
	 */
	public function connect(Application $app) {

		//@note $app['controllers_factory'] is a factory that returns a new instance of ControllerCollection when used.
		//@see http://silex.sensiolabs.org/doc/organizing_controllers.html
		$controllers = $app['controllers_factory'];

		// Bind sub-routes
		$controllers
		->get('/', array($this, 'home'))
		->bind('home');

		return $controllers;

	}


	/**
	 * Home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function home(Application $app) {
		$data = array(
			'page' => 'home'
			);

		// Inject data into the template which will show 'm all
		return $app['twig']->render('home.twig',$data);

	}


	
}