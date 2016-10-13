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
			->get('/{id}/pagina', array($this, 'page'))
			->assert('id', '\d+')
			->bind('print.pagina');

		$controllers
			->get('/{id}/carnet', array($this, 'card'))
			->assert('id', '\d+')
			->bind('print.card');

		$controllers
			->get('/{id}/horas', array($this, 'horas'))
			->assert('id', '\d+')
			->bind('print.horas');

		$controllers
			->get('/listaSemana', array($this,'listaSemana'))
			->bind('print.listaSemana');

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
		if ($app['session']->get('user') == null) {
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		} elseif ($app['session']->get('user') == 0) {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		}
		
		$data = array(
			'voluntario' => $app['db.persona']->find($id),
			'page' 		 => 'noheader'
		);
		// Build and return the HTML
		return $app['twig']->render('voluntarios/printCard.twig',$data);
	}

	/**
	 * Volunteer print out card
	 * @param Application $app An Application instance
	 * @param int $id ID of the user (URL Param)
	 * @return string A blob of HTML
	 */
	public function horas(Application $app, $id) {
		//checking if the user is loged in
		if ($app['session']->get('user') == null) {
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		} elseif ($app['session']->get('user') == 0) {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		}
		
		$data = array(
			'page' 		 => 'voluntarios',
			'id' 		 => $id,
			'voluntario' => $app['db.persona']->find($id),
			'months' 	 => array(
				'01' => 'enero', 
				'02' => 'febrero', 
				'03' => 'marzo', 
				'04' => 'abril', 
				'05' => 'mayo', 
				'06' => 'junio', 
				'07' => 'julio', 
				'08' => 'agosto', 
				'09' => 'septiembre', 
				'10' => 'octubre', 
				'11' => 'noviembre', 
				'12' => 'diciembre'
			)
		);
		// Build and return the HTML
		return $app['twig']->render('voluntarios/printHoras.twig',$data);
	}

	/**
	 * Volunteer print out paper
	 * @param Application $app An Application instance
	 * @param int $id ID of the volunteer (URL Param)
	 * @return string A blob of HTML
	 */
	public function page(Application $app, $id) {
		//checking if the user is loged in
		if ($app['session']->get('user') == null ) {
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		} elseif ($app['session']->get('user') == 0) {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		}
		
		$data = array(
			'voluntario' 	=> $app['db.persona']->find($id),
			'trabajaDe'  	=> $app['db.trabajar']->findStartDateByPersona($id)['dia'],
			'trabajaA' 	 	=> $app['db.trabajar']->findEndDateByPersona($id)['dia'],
			'trabajaPara' 	=> $app['db.trabajar']->findTotalHoursByPersona($id),
			'page' 			=> 'noheader',
			'months' 		=> array(
				'01' => 'enero', 
				'02' => 'febrero', 
				'03' => 'marzo', 
				'04' => 'abril', 
				'05' => 'mayo', 
				'06' => 'junio', 
				'07' => 'julio', 
				'08' => 'agosto', 
				'09' => 'septiembre', 
				'10' => 'octubre', 
				'11' => 'noviembre', 
				'12' => 'diciembre'
			)
		);
		// build and return the html
		return $app['twig']->render('voluntarios/printPaper.twig',$data);
	}

	/**
	 * Summary of the week
	 * @param Application $app An Application instance
	 * @param int $id ID of the user (URL Param)
	 * @return string A blob of HTML
	 */
	public function listaSemana(Application $app) {
		//checking if the user is loged in
		if ($app['session']->get('user') == null) {
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		} elseif ($app['session']->get('user') == 0) {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		}

		$data = array(
			'page' 	 => 'voluntarios',
			'months' => array(
				'01' => 'enero', 
				'02' => 'febrero', 
				'03' => 'marzo', 
				'04' => 'abril', 
				'05' => 'mayo', 
				'06' => 'junio', 
				'07' => 'julio', 
				'08' => 'agosto', 
				'09' => 'septiembre', 
				'10' => 'octubre', 
				'11' => 'noviembre', 
				'12' => 'diciembre'
			),
			'days' => array(
				'1' => 'Lunes ( ' . date('d/m/Y', strtotime('-' . (date('w')-1) . ' days')) . ' )',
				'2' => 'Martes ( ' . date('d/m/Y', strtotime('+' . (2-date('w')) . ' days')) . ' )',
				'3' => 'Miercoles ( ' . date('d/m/Y', strtotime('+' . (3-date('w')) . ' days')) . ' )',
				'4' => 'Jueves ( ' . date('d/m/Y', strtotime('+' . (4-date('w')) . ' days')) . ' )',
				'5' => 'Viernes ( ' . date('d/m/Y', strtotime('+' . (5-date('w')) . ' days')) . ' )'),
			'currentWeek'  => ceil((date("d") - date("w") - 1) / 7) + 1,
			'currentMonth' => date('m'),
			'weekStart'    => date('d/m/Y', strtotime('-'.(date('w')-1).' days')),
			'weekEnd'      => date('d/m/Y', strtotime('+'.(5-date('w')).' days')),
		);
		// Build and return the HTML
		return $app['twig']->render('voluntarios/printListaSemana.twig',$data);
	}
}
