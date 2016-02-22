<?php

namespace ZamoraTeran\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class voluntariosController implements ControllerProviderInterface {

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

		$controllers
		->get('/voluntarios/',array($this,'voluntarios'))
		->bind('overview');

		$controllers
		->get('/voluntarios/{id}/', array($this, 'detail'))
		->assert('id', '\d+')
		->bind('detail');

		$controllers
		->get('/voluntarios/{id}/edit', array($this, 'edit'))
		->assert('id', '\d+')
		->bind('editVoluntario');

		$controllers
		->get('/voluntarios/neuvo',array($this,'newVoluntario'))
		->bind('nuevoVoluntario');
		return $controllers;

	}


	/**
	 * Home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function home(Application $app) {
		// Inject data into the template which will show 'm all
		return $app['twig']->render('home.twig');

	}

	/**
	 * Volunteer Overview
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function voluntarios(Application $app) {
		// Inject data into the template which will show 'm all
		return $app['twig']->render('voluntarios.twig');

	}

	/**
	 * Volunteer Detail
	 * @param Application $app An Application instance
	 * @param int $id ID of the tweet (URL Param)
	 * @return string A blob of HTML
	 */
	public function detail(Application $app, $id) {
		// Build and return the HTML representing the tweet
		return $app['twig']->render('voluntario.twig');
	}

	/**
	 * Volunteer Edit
	 * @param Application $app An Application instance
	 * @param int $id ID of the tweet (URL Param)
	 * @return string A blob of HTML
	 */
	public function edit(Application $app, $id) {
			// Build and return the HTML representing the tweet
		return $app['twig']->render('edit.twig');
	}

	/**
	 * Volunteer Edit
	 * @param Application $app An Application instance
	 * @param int $id ID of the tweet (URL Param)
	 * @return string A blob of HTML
	 */
	public function newVoluntario(Application $app) {
			// Build and return the HTML representing the tweet
		return $app['twig']->render('edit.twig');
	}
}