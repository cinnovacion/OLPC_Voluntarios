<?php

namespace ZamoraTeran\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class printController implements ControllerProviderInterface {

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
		->get('/{id}/page', array($this, 'page'))
		->assert('id', '\d+')
		->bind('print.page');

		$controllers
		->get('/{id}/card', array($this, 'card'))
		->assert('id', '\d+')
		->bind('print.card');

		return $controllers;
	}


	
	/**
	 * Volunteer print out card
	 * @param Application $app An Application instance
	 * @param int $id ID of the user (URL Param)
	 * @return string A blob of HTML
	 */
	public function card(Application $app, $id) {
		//checking if the user is loged in
		if($app['session']->get('user') == null || empty($app['session']->get('user'))){
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		}elseif ($app['session']->get('user')['nombre'] == 'logger') {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		}
		
		$data = array(
			'voluntario' => $app['db.persona']->find($id),
			'page' => 'noheader'
			);
		// Build and return the HTML
		return $app['twig']->render('voluntarios/printCard.twig',$data);
	}

	/**
	 * Volunteer print out paper
	 * @param Application $app An Application instance
	 * @param int $id ID of the volunteer (URL Param)
	 * @return string A blob of HTML
	 */
	public function page(Application $app, $id) {
		//checking if the user is loged in
		if($app['session']->get('user') == null || empty($app['session']->get('user'))){
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		}elseif ($app['session']->get('user')['nombre'] == 'logger') {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		}
		
		
		$data = array(
			'voluntario' => $app['db.persona']->find($id),
			'trabajaDe' => $app['db.trabajar']->findStartDateByPersona($id)['dia'],
			'trabajaA' => $app['db.trabajar']->findEndDateByPersona($id)['dia'],
			'trabajaPara' => $app['db.trabajar']->findTotalHoursByPersona($id),
			'page' => 'noheader'
			);
		// build and return the html
		return $app['twig']->render('voluntarios/printPaper.twig',$data);
	}
}