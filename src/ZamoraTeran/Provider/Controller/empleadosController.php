<?php

namespace ZamoraTeran\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class empleadosController implements ControllerProviderInterface {

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
		->get('/', array($this, 'overview'))
		->bind('empleados.overview');

		$controllers
		->get('/new',array($this, 'newEmpleado'))
		->bind('empleados.new');
		return $controllers;
	}


	/**
	 * overview of empleados
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function overview(Application $app) {
		$data = array(
			'page' => 'empleados'
			);
		// Inject data into the template which will show 'm all
		return $app['twig']->render('empleados/overview.twig',$data);

	}

	/**
	 * overview of empleados
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function newEmpleado(Application $app) {
		$data = array(
			'page' => 'empleados'
			);
		// Inject data into the template which will show 'm all
		return $app['twig']->render('empleados/formulario.twig',$data);

	}


	
}