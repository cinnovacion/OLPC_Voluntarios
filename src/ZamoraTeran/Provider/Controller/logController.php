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
		if($app['session']->get('user') == null /**|| empty($app['session']->get('user'))**/){
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		}elseif ($app['session']->get('user')['nombre'] != 'logger') {
			$app['session']->remove('user');
			$app['session']->set('user', array(
				'id' => 0,
				'nombre' => 'logger'
				));
		}

		$logform = $app['form.factory']->createNamed('loginform', 'form')
		->add('barcode', 'text', array('required' => true));

		/**if($logform->isValid()){
			$data = $logform->getData();
			$personaId= $app['db.persona']->getIdByCedula($data['barcode'])['id'];
			if($personaId == null){
				$logform->get('barcode')->addError(new \Symfony\Component\Form\FormError('El code no existe'));
				$data = array(
					'logform' => $logform->createView(),
					'page' => 'noheader'
					);

				// Inject data into the template which will show 'm all
				return $app['twig']->render('Log/log.twig',$data); 
			}
			try{
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

				}
			}catch(Exception $e){
				$data = array('barcode'=>$data['barcode'] );
				return $app->redirect($app['url_generator']->generate('logger.log',$data));
			}
			return $app->redirect($app['url_generator']->generate('logger.log'));
			die();
		}**/

		$data = array(
			'logform' => $logform->createView(),
			'page' => 'noheader'
			);

		// Inject data into the template which will show 'm all
		return $app['twig']->render('log/log.twig',$data);
	}
}
