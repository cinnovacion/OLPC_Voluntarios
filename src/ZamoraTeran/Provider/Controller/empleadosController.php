<?php

namespace ZamoraTeran\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Validator\constraints as Assert;

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
		->method('GET|POST')
		->bind('empleados.overview');

		$controllers
		->get('/{id}/delete', array($this, 'delete'))
		->assert('id', '\d+')
		->bind('empleados.delete');

		$controllers
		->get('/{id}/', array($this, 'details'))
		->assert('id', '\d+')
		->method('GET|POST')
		->bind('empleados.details');

		$controllers
		->get('/nuevo',array($this, 'newEmpleado'))
		->method('GET|POST')
		->bind('empleados.nuevo');
		return $controllers;
	}


	/**
	 * overview of empleados
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function overview(Application $app) {
		//checking if the user is loged in
		if($app['session']->get('user') == null /**|| empty($app['session']->get('user'))**/){
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		}elseif ($app['session']->get('user')['nombre'] == 'logger') {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		}

		

		$data = array(
			
			'empleados' => $app['db.admins']->getEmpleados(),
			'page' => 'empleados',
			'session' => $app['session']->get('user')
			);
		// Inject data into the template which will show 'm all
		return $app['twig']->render('empleados/overview.twig',$data);

	}

	/**
	 * overview of empleados
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function details(Application $app,$id) {
		//checking if the user is loged in
		if($app['session']->get('user') == null /**|| empty($app['session']->get('user'))**/){
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		}elseif ($app['session']->get('user')['nombre'] == 'logger') {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		}

		$empleado = $app['db.admins']->getEmpleadoById($id);

		$contrasenaform = $app['form.factory']->createNamed('contrasenaform', 'form')
		->add('Nombre', 'text', array(
			'constraints' => array(new Assert\NotBlank()),
			'data' => $empleado['Nombre']
			))
		->add('repeatpassword', 'password')
		->add('newpassword', 'password');

		$contrasenaform->handleRequest($app['request']);

		if($contrasenaform->isValid()){
			$data = $contrasenaform->getData();
			if($data['repeatpassword'] == null && $data['newpassword'] == null){
				$admin = array('Nombre' => $data['Nombre']);
				$app['db.admins']->update($admin, array('idAdmins' => $id));
				return $app->redirect($app['url_generator']->generate('empleados.overview'));
			}elseif($data['repeatpassword'] != $data['newpassword']){
				$loginform->get('contrasena')->addError(new \Symfony\Component\Form\FormError('El contrasena no es correcto'));
			}else{
				include('\..\..\..\Classes\Encrypt.php');
				$encrypt = new \Encrypt();
				$encrypted = $encrypt->encryptPassword($data['newpassword']);
				$admin = array('Nombre' => $data['Nombre'],'contraseña' => $encrypted['salt'].$encrypted['password'] );
				$app['db.admins']->update($admin, array('idAdmins' => $id));
				return $app->redirect($app['url_generator']->generate('empleados.overview'));
			}
		}
		$data = array(
			'contrasenaform' => $contrasenaform->createView(),
			'empleado' => $empleado,
			'page' => 'empleados',
			'todo' => 'edit',
			'session' => $app['session']->get('user')
			);
		// Inject data into the template which will show 'm all
		return $app['twig']->render('empleados/formulario.twig',$data);

	}


	/**
	 * overview of empleados
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function delete(Application $app,$id) {
		if($app['session']->get('user') == null /**|| empty($app['session']->get('user'))**/){
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		}elseif ($app['session']->get('user')['nombre'] == 'logger') {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		}
		$app['db.admins']->delete(array('idAdmins' => $id));
		return $app->redirect($app['url_generator']->generate('empleados.overview'));
	}

	/**
	 * overview of empleados
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function newEmpleado(Application $app) {
		$contrasenaform = $app['form.factory']->createNamed('contrasenaform', 'form')
		->add('Nombre', 'text', array(
			'constraints' => array(new Assert\NotBlank())
			))
		->add('repeatpassword', 'password', array(
			'constraints' => array(new Assert\NotBlank())
			))
		->add('newpassword', 'password', array(
			'constraints' => array(new Assert\NotBlank())
			));

		$contrasenaform->handleRequest($app['request']);

		if($contrasenaform->isValid()){
			$data = $contrasenaform->getData();
			if($data['newpassword'] == $data['repeatpassword']){
				$app['db.admins']->insert(array(
					'Nombre'       => $data['Nombre'],
					'contraseña' => $data['newpassword']
					));
				return $app->redirect($app['url_generator']->generate('empleados.overview'));
			}
		}
		$data = array(
			'contrasenaform' => $contrasenaform->createView(),
			'page' => 'empleados',
			'todo' => 'nuevo',
			'session' => $app['session']->get('user')
			);
		// Inject data into the template which will show 'm all
		return $app['twig']->render('empleados/formulario.twig',$data);

	}


	
}