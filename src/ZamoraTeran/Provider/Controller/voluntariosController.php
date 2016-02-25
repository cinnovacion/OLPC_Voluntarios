<?php

namespace ZamoraTeran\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Validator\constraints as Assert;

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

		$controllers
		->get('/',array($this,'voluntarios'))
		->method('GET|POST')
		->bind('voluntarios.overview');

		$controllers
		->get('/{id}/', array($this, 'detail'))
		->assert('id', '\d+')
		->bind('voluntarios.detail');

		$controllers
		->get('/{id}/edit', array($this, 'edit'))
		->assert('id', '\d+')
		->bind('voluntarios.editVoluntario');

		$controllers
		->get('/neuvo',array($this,'newVoluntario'))
		->method('GET|POST')
		->bind('voluntarios.nuevoVoluntario');

		return $controllers;

	}

	/**
	 * Volunteer Overview
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function voluntarios(Application $app) {
		$busquedaform = $app['form.factory']->createNamed('busquedaform', 'form')
		->add('Busqueda', 'text', array('required' => false));

		$busquedaform->handleRequest($app['request']);

		
		if($busquedaform->isValid()){
			$data = $busquedaform->getData();
			$voluntarios = $app['db.persona']->findAllByString($data['Busqueda']);
		}else{
			$voluntarios = $app['db.persona']->findAll();
		}

		$data = array(
			'page' => 'voluntarios',
			'voluntarios' => $voluntarios,
			'ahoras' => $app['db.trabajar']->getHoursOfWork(),
			'busquedaform' => $busquedaform->createView()
			);
		// Inject data into the template which will show 'm all
		return $app['twig']->render('voluntarios/voluntarios.twig',$data);

	}

	/**
	 * Volunteer Detail
	 * @param Application $app An Application instance
	 * @param int $id ID of the tweet (URL Param)
	 * @return string A blob of HTML
	 */
	public function detail(Application $app, $id) {
		$data = array(
			'page' => 'voluntarios',
			'voluntario' => $app['db.persona']->find($id),
			'trabaja' => $app['db.trabajar']->findByPersona($id),
			'trabajaPara' => $app['db.trabajar']->findTotalHoursByPersona($id)
			);
		// Build and return the HTML representing the tweet
		return $app['twig']->render('voluntarios/voluntario.twig',$data);
	}

	/**
	 * Volunteer Edit
	 * @param Application $app An Application instance
	 * @param int $id ID of the tweet (URL Param)
	 * @return string A blob of HTML
	 */
	public function edit(Application $app, $id) {
		$data = array(
			'page' => 'voluntarios'
			);
			// Build and return the HTML representing the tweet
		return $app['twig']->render('voluntarios/edit.twig',$data);
	}

	/**
	 * Volunteer Edit
	 * @param Application $app An Application instance
	 * @param int $id ID of the tweet (URL Param)
	 * @return string A blob of HTML
	 */
	public function newVoluntario(Application $app) {		
		$nuevoform = $app['form.factory']->createNamed('nuevoform')
		->add('Nombre', 'text', array(
			'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
			))
		->add('NoDeCedula', 'text', array(
			'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
			))
		->add('DireccionDeResidencia', 'text', array(
			'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
			))
		->add('Telefono', 'text', array(
			'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
			))
		->add('CorreoElectronico', 'email', array(
			'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5)))
			))
		->add('InstitucionAcademica', 'text', array(
			'constraints' => array(new Assert\NotBlank())
			))
		->add('CarreraCurso', 'text', array(
			'constraints' => array(new Assert\NotBlank())
			))
		->add('Nivel', 'text', array(
			'constraints' => array(new Assert\NotBlank())
			));

		$nuevoform->handleRequest($app['request']);

		if($nuevoform->isValid()){
			$data = $nuevoform->getData();
			var_dump($data);
		}else{
			var_dump('niet geslaagd');
		}
		$data = array(
			'page' => 'voluntarios',
			'nuevoform' => $nuevoform->createView()
			);
			// Build and return the HTML
		return $app['twig']->render('voluntarios/formulario.twig',$data);
	}

	
}