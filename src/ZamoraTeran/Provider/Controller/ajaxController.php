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

		$controllers
		->get('/fillWeeks',array($this,'fillWeeks'))
		->method('GET|POST')
		->bind('ajax.fillWeeks');

		$controllers
		->get('/getListaTrabaja',array($this,'getListaTrabaja'))
		->method('GET|POST')
		->bind('ajax.getListaTrabaja');

		$controllers
		->get('/insertHours',array($this,'insertHours'))
		->method('GET|POST')
		->bind('ajax.insertHours');

		return $controllers;

	}

	//controller for the volunteers who worked in a certain week
	public function listaSemana(Application $app) {
		if(isset($_POST['action'])){
			//get first day of the week from data
			$firstDay = json_decode($_POST['action'], true)['firstday'];
			
			//get all the days of the week in an array
			$days = array(
				'1' => date('d/m/Y',strtotime($firstDay)),
				'2' => date('d/m/Y',strtotime($firstDay. ' +1 day')),
				'3' => date('d/m/Y',strtotime($firstDay. ' +2 day')),
				'4' => date('d/m/Y',strtotime($firstDay. ' +3 day')),
				'5' => date('d/m/Y',strtotime($firstDay. ' +4 day'))
				);
			//get al the volunteers of the week in an array
			//every object in the volunteers array is a new array of all the volunteers of the specific day
			$volunteers = array(
				'1' => $app['db.trabajar']->findVolunteersOnDate(date('Y-m-d',strtotime($firstDay))),
				'2' => $app['db.trabajar']->findVolunteersOnDate(date('Y-m-d',strtotime($firstDay. ' +1 day'))),
				'3' => $app['db.trabajar']->findVolunteersOnDate(date('Y-m-d',strtotime($firstDay. ' +2 day'))),
				'4' => $app['db.trabajar']->findVolunteersOnDate(date('Y-m-d',strtotime($firstDay. ' +3 day'))),
				'5' => $app['db.trabajar']->findVolunteersOnDate(date('Y-m-d',strtotime($firstDay. ' +4 day')))
				);

			//show the data
			echo json_encode(
				array(
					'days' => $days, 
					'volunteers' => $volunteers));

			
		}
		// Inject data into the template which will show 'm all
		return $app['twig']->render('Ajax/dump.twig');
	}

	public function getListaTrabaja(Application $app) {
		if(isset($_POST['action'])){
			$search = json_decode($_POST['action'], true);
			if($search['filter'] == 'year'){
				echo json_encode($app['db.trabajar']->getWorkInYear($search['voluntario'],$search['year']));
			}else if($search['filter'] == 'month'){
				echo json_encode($app['db.trabajar']->getWorkInMonth($search['voluntario'],$search['year'],$search['month']));
			}else if($search['filter'] == 'week'){
				//echo json_encode($app['db.trabajar']->getWorkInMonth($search['voluntario'],$search['year'],$search['month'],$search['week']));
				
				$days = array(
					$app['db.trabajar']->getWorkInDay($search['voluntario'],date('Y-m-d',strtotime($search['week']))),
					$app['db.trabajar']->getWorkInDay($search['voluntario'],date('Y-m-d',strtotime($search['week']. ' +1 day'))),
					$app['db.trabajar']->getWorkInDay($search['voluntario'],date('Y-m-d',strtotime($search['week']. ' +2 day'))),
					$app['db.trabajar']->getWorkInDay($search['voluntario'],date('Y-m-d',strtotime($search['week']. ' +3 day'))),
					$app['db.trabajar']->getWorkInDay($search['voluntario'],date('Y-m-d',strtotime($search['week']. ' +4 day')))
					);

				echo json_encode($days);
			}
			
		}
		// Inject data into the template which will show 'm all
		return $app['twig']->render('Ajax/dump.twig');
	}

	public function insertHours(Application $app) {
		if(isset($_POST['action'])){
			$search = json_decode($_POST['action'], true);
			
			$trabaja = array(
				'Persona_idPersona' => $search['id'],
				'horaInicio' => $search['entrada'],
				'horaFinal' => $search['salida'],
				'dia' => implode('/', array_reverse(explode('/', $search['dia']))),
				'tiempo' => null
				);

			//calculate the working time
			$trabaja['tiempo'] = round((
				strtotime($trabaja['horaFinal'])-
				strtotime($trabaja['horaInicio'])
				)/(3600), 2);


			if(substr($search['entrada'], -2) == "PM"){
				$trabaja['horaInicio'] =  date('h:i:s', strtotime($search['entrada'])+43200);
				$search['entrada'] =  "changed time";
			}

			if(substr($search['salida'], -2) == "PM"){
				$trabaja['horaFinal'] = date('h:i:s', strtotime($search['salida'])+43200);

				$search['salida'] =  "changed time";
			}

			$app['db.trabajar']->insert($trabaja);
			echo json_encode($search);
			
			
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

	public function fillWeeks(Application $app) {
		if(isset($_POST['action'])){
			$weeks = array();
			foreach (json_decode($_POST['action'], true)['mondays'] as $key => $value) {
				array_push($weeks,"Semana:" + $value );
			}
			echo json_encode($weeks);
		}
		// Inject data into the template which will show 'm all
		return $app['twig']->render('Ajax/dump.twig');
	}	

	public function logVolunteer(Application $app) {
		if(isset($_POST['action'])){
			$idPersona = json_decode($_POST['action'], true)['idPersona'];
			$lastInput = $app['db.trabajar']->getLastInput($idPersona);

			//if the lastinput of the user is equal to today
			if($lastInput['dia'] == date("Y-m-d")){

				//if the final hour is null
				if($lastInput['horaFinal'] == null){

					//add final hour (now)
					$lastInput['horaFinal'] = date("Y-m-d H:i:s");

					//calculate the working time
					$lastInput['tiempo'] = round((
						strtotime($lastInput['horaFinal'])-
						strtotime($lastInput['horaInicio'])
						)/(3600), 2);

					//update into database
					$app['db.trabajar']->update($lastInput,array('idTrabajar' => $lastInput['idTrabajar']));
					echo json_encode($lastInput);
				}else{
					//add starting time (now)
					$trabaja = array(
						'Persona_idPersona' => $idPersona,
						'horaInicio' => date("Y-m-d H:i:s"),
						'dia' => date("Y-m-d")
						);

					//insert into database
					$app['db.trabajar']->insert($trabaja);
					echo json_encode($trabaja);
				}

			}else{
				//add starting time (now)
				$trabaja = array(
					'Persona_idPersona' => $idPersona,
					'horaInicio' => date("Y-m-d H:i:s"),
					'dia' => date("Y-m-d")
					);

				//insert into database
				$app['db.trabajar']->insert($trabaja);
				echo json_encode($trabaja);

			}
		}
		// Inject data into the template which will show 'm all
		return $app['twig']->render('Ajax/dump.twig');
	}
}