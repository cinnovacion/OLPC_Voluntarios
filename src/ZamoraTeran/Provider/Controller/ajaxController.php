<?php

namespace ZamoraTeran\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class ajaxController implements ControllerProviderInterface {

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
		->get('/getVolunteer', array($this, 'getVolunteer'))
		->method('GET|POST')
		->bind('ajax.getVolunteer');

		$controllers
		->get('/logVolunteer',array($this,'logVolunteer'))
		->method('GET|POST')
		->bind('ajax.logVolunteer');

		$controllers
		->get('/getListaSemana',array($this,'listaSemana'))
		->method('GET|POST')
		->bind('ajax.listaSemana');

		return $controllers;

	}

	public function listaSemana(Application $app) {
		if(isset($_POST['action'])){
			echo json_encode(array(
				'1' => $app['db.trabajar']->findVolunteersOnDate(date('Y-m-d', strtotime('-'.(1-date('w')).' days'))),
				'2' => $app['db.trabajar']->findVolunteersOnDate(date('Y-m-d', strtotime('+'.(2-date('w')).' days'))),
				'3' => $app['db.trabajar']->findVolunteersOnDate(date('Y-m-d', strtotime('+'.(3-date('w')).' days'))),
				'4' => $app['db.trabajar']->findVolunteersOnDate(date('Y-m-d', strtotime('+'.(4-date('w')).' days'))),
				'5' => $app['db.trabajar']->findVolunteersOnDate(date('Y-m-d', strtotime('+'.(5-date('w')).' days')))
				));
		}
		// Inject data into the template which will show 'm all
		return $app['twig']->render('Ajax/dump.twig');
	}


	public function getVolunteer(Application $app) {
		if(isset($_POST['action'])){
			echo json_encode($app['db.persona']->getPersonByCedula(json_decode($_POST['action'], true)['cedula']));
		}
		// Inject data into the template which will show 'm all
		return $app['twig']->render('Ajax/dump.twig');
	}	

	public function logVolunteer(Application $app) {
		if(isset($_POST['action'])){
			$lastInput = $app['db.trabajar']->getLastInput(json_decode($_POST['action'], true)['idPersona']);

			if($lastInput['dia'] == date("Y-m-d")){

					if($lastInput['horaFinal'] == null){
						$lastInput['horaFinal'] = date("Y-m-d H:i:s");
						$lastInput['tiempo'] = round((
							strtotime($lastInput['horaFinal'])-
							strtotime($lastInput['horaInicio'])
							)/(3600), 2);
						$app['db.trabajar']->update($lastInput,array('idTrabajar' => $lastInput['idTrabajar']));
						echo json_encode($lastInput);
					}else{
						$trabaja = array(
							'Persona_idPersona' => $lastInput['Persona_idPersona'],
							'horaInicio' => date("Y-m-d H:i:s"),
							'dia' => date("Y-m-d")
							);
						$app['db.trabajar']->insert($trabaja);
						echo json_encode($trabaja);
					}

				}else{

					$trabaja = array(
						'Persona_idPersona' => $lastInput['Persona_idPersona'],
						'horaInicio' => date("Y-m-d H:i:s"),
						'dia' => date("Y-m-d")
						);
					$app['db.trabajar']->insert($trabaja);
						echo json_encode($trabaja);

				}
/**
			$lastInput = $app['db.trabajar']->getLastInput($personaId);

				if($lastInput['dia'] == date("Y-m-d")){

					if($lastInput['horaFinal'] == null){
						$lastInput['horaFinal'] = date("Y-m-d H:i:s");
						$lastInput['tiempo'] = round((
							strtotime($lastInput['horaFinal'])-
							strtotime($lastInput['horaInicio'])
							)/(3600), 2);
						$app['db.trabajar']->update($lastInput,array('idTrabajar' => $lastInput['idTrabajar']));
					}else{
						$trabaja = array(
							'Persona_idPersona' => $personaId,
							'horaInicio' => date("Y-m-d H:i:s"),
							'dia' => date("Y-m-d")
							);
						$app['db.trabajar']->insert($trabaja);
					}

				}else{

					$trabaja = array(
						'Persona_idPersona' => $personaId,
						'horaInicio' => date("Y-m-d H:i:s"),
						'dia' => date("Y-m-d")
						);
					$app['db.trabajar']->insert($trabaja);

				}**/
		}
		// Inject data into the template which will show 'm all
		return $app['twig']->render('Ajax/dump.twig');
	}
}