<?php

namespace ZamoraTeran\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;

class authController implements ControllerProviderInterface {

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
		->get('/', array($this, 'login'))
		->method('GET|POST')
		->bind('login');

		$controllers
		->get('/logout', array($this, 'logout'))
		->bind('logout');

		return $controllers;

	}


	/**
	 * Home page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function login(Application $app) {
		if($app['session']->get('user') != null /**|| !(empty($app['session']->get('user')))**/){
			return $app->redirect($app['url_generator']->generate('voluntarios.overview'));
			die();
	}	

	
		include('/../../../Classes/Encrypt.php');
		$encrypt = new \Encrypt();
		$loginform = $app['form.factory']->createNamed('loginform', 'form')
		->add('Nombre', 'text', array('required' => true))
		->add('contrasena', 'password', array('required' => true));

		$loginform->handleRequest($app['request']);

		if($loginform->isValid()){
			$data = $loginform->getData();
			
			$admin = $app['db.admins']->getByName($data['Nombre']);
			if($admin == null){
				$loginform->get('Nombre')->addError(new \Symfony\Component\Form\FormError('El nombre no existe'));
			}else{
				if(hash_equals($data['Nombre'], $admin['Nombre'])){
					if($encrypt->controlPassword($data['contrasena'], $admin['contraseña'])){
						session_set_cookie_params(0);	
						session_start();

						$app['session']->set('user', array(
							'id' => $admin['idAdmins'],
							'nombre' => $admin['Nombre']
							));
						return $app->redirect($app['url_generator']->generate('voluntarios.overview'));
					}else{
						$loginform->get('contrasena')->addError(new \Symfony\Component\Form\FormError('El contrasena no es correcto'));
					}
					
				};
			}
			
		}

		$data = array(
			'loginform' => $loginform->createView(),
			'page' => 'home'
			);

		// Inject data into the template which will show 'm all
		return $app['twig']->render('home.twig',$data);

	}

	public function logout(Application $app) {
		$app['session']->remove('user');
		session_unset();
		session_destroy();
		return $app->redirect($app['url_generator']->generate('login'));
	}


	
}
