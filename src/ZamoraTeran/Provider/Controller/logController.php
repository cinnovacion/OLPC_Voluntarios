<?php

namespace ZamoraTeran\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class logController implements ControllerProviderInterface {

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
		->get('/', array($this, 'log'))
		->method('GET|POST')
		->bind('logger.log');

		return $controllers;
	}


	/**
	 * overview of empleados
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function log(Application $app) {
		if($app['session']->get('user') == null){
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		}elseif ($app['session']->get('user') == 0) {
			$app['session']->remove('user');
			$app['session']->set('user',0);
		}

		$logform = $app['form.factory']->createNamed('loginform', 'form')
		->add('barcode', 'text', array('required' => true));

		$logform->handleRequest($app['request']);

		if($logform->isValid()){
			$data = $logform->getData();
			$personaId= $app['db.persona']->getIdByNoCedula($data['barcode'])['id'];
			if($personaId == null){
				$logform->get('barcode')->addError(new \Symfony\Component\Form\FormError('El code no existe'));
				$data = array(
					'logform' => $logform->createView(),
					'page' => 'noheader'
					);

		// Inject data into the template which will show 'm all
				return $app['twig']->render('Log/log.twig',$data); 
			}
			$lastInput = $app['db.trabajar']->getLastInput($personaId);
			if($lastInput['dia'] == date("Y-m-d")){
				if($lastInput['horaFinal'] == null){
					$lastInput['horaFinal'] = date('h:i A');
					$lastInput['tiempo'] = $lastInput['horaFinal']-$lastInput['horaInicio'];
					$app['db.trabajar']->update($lastInput,array('idTrabajar' => $lastInput['idTrabajar']));
				}else{
					$trabaja = array(
						'Persona_idPersona' => $personaId,
						'horaInicio' => date('h:i A'),
						'dia' => date("Y-m-d")
						);
					$app['db.trabajar']->insert($trabaja);
				}
			}else{
				$trabaja = array(
					'Persona_idPersona' => $personaId,
					'horaInicio' => date('h:i A'),
					'dia' => date("Y-m-d")
					);
				$app['db.trabajar']->insert($trabaja);
			}
			return $app->redirect($app['url_generator']->generate('logger.log'));
			die();
		}

		$data = array(
			'logform' => $logform->createView(),
			'page' => 'noheader'
			);

		// Inject data into the template which will show 'm all
		return $app['twig']->render('Log/log.twig',$data);
	}
}