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
		->method('GET|POST')
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
		//checking if the user is loged in
		if($app['session']->get('user') == null || empty($app['session']->get('user'))){
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		}elseif ($app['session']->get('user')['nombre'] == 'logger') {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		}

		//pagination
		require_once '\..\..\..\Classes\Pagination.php';
		$numItems = $app['db.persona']->count()['count'];
		$numItemsPerPage = 15;
		$curpage = isset($_GET['p']) ? $_GET['p'] : 1;
		$numPages = ceil($numItems / $numItemsPerPage);
		$pagination =  generatePaginationSequence($curpage,$numPages);


		//Make the searchform
		$busquedaform = $app['form.factory']->createNamed('busquedaform', 'form')
		->add('Busqueda', 'text', array('required' => false));

		$busquedaform->handleRequest($app['request']);

		if($busquedaform->isValid()){
			$data = $busquedaform->getData();
			$voluntarios = $app['db.persona']->findAllByString($data['Busqueda'],$curpage,$numItemsPerPage);
		}else{
			$voluntarios = $app['db.persona']->findAll($curpage,$numItemsPerPage);
		}

		$data = array(
			'page' => 'voluntarios',
			'voluntarios' => $voluntarios,
			'ahoras' => $app['db.trabajar']->getHoursOfWork(),
			'busquedaform' => $busquedaform->createView(),
			'pagination' => $pagination,
			'curPage' => $curpage,
			'numPages' => $numPages,
			'baseUrl' => $app['url_generator']->generate('voluntarios.overview'),
			'numItems' => $numItems,
			'session' => $app['session']->get('user')
			);
		// Inject data into the template which will show 'm all
		return $app['twig']->render('voluntarios/voluntarios.twig',$data);

	}

	/**
	 * Volunteer Detail
	 * @param Application $app An Application instance
	 * @param int $id ID of the volunteer (URL Param)
	 * @return string A blob of HTML
	 */
	public function detail(Application $app, $id) {
		//checking if the user is loged in
		if($app['session']->get('user') == null || empty($app['session']->get('user'))){
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		}elseif ($app['session']->get('user')['nombre'] == 'logger') {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		
		}
		require_once '\..\..\..\Classes\Pagination.php';
		$numItems = $app['db.trabajar']->countById($id)['count'];
		$numItemsPerPage = 10;
		$curpage = isset($_GET['p']) ? $_GET['p'] : 1;
		$numPages = ceil($numItems / $numItemsPerPage);
		$pagination =  generatePaginationSequence($curpage,$numPages);

		$data = array(
			'page' => 'voluntarios',
			'voluntario' => $app['db.persona']->find($id),
			'trabaja' => $app['db.trabajar']->findByPersona($id,$curpage,$numItemsPerPage),
			'trabajaPara' => $app['db.trabajar']->findTotalHoursByPersona($id),
			'disponibilidad' => $app['db.disponibilidad']->getDisponibilidad($id),
			'pagination' => $pagination,
			'curPage' => $curpage,
			'numPages' => $numPages,
			'baseUrl' => $app['url_generator']->generate('voluntarios.detail',array('id' => $id)),
			'numItems' => $numItems,
			'session' => $app['session']->get('user')
			);
		// build and return the html
		return $app['twig']->render('voluntarios/voluntario.twig',$data);
	}

	/**
	 * Volunteer Edit
	 * @param Application $app An Application instance
	 * @param int $id ID of the tweet (URL Param)
	 * @return string A blob of HTML
	 */
	public function edit(Application $app, $id) {
		//checking if the user is loged in
		if($app['session']->get('user') == null || empty($app['session']->get('user'))){
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		}elseif ($app['session']->get('user')['nombre'] == 'logger') {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		
		}
		$persona = $app['db.persona']->find($id);
		$disponibilidad  = $app['db.disponibilidad']->getDisponibilidad($id);
		//making of the form
		$nuevoform = $app['form.factory']->createNamed('nuevoform')
		->add('Nombre', 'text', array(
			'constraints' => array(new Assert\NotBlank(),new Assert\Length(array('min' => 5))),
			'data' => $persona['Nombre']
			))
		->add('NoDeCedula', 'text', array(
			'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
			'data' => $persona['NoDeCedula']
			))
		->add('DireccionDeResidencia', 'text', array(
			'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
			'data' => $persona['DireccionDeResidencia']
			))
		->add('Telefono', 'text', array(
			'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
			'data' => $persona['Telefono']
			))
		->add('CorreoElectronico', 'email', array(
			'constraints' => array(new Assert\NotBlank(), new Assert\Length(array('min' => 5))),
			'data' => $persona['CorreoElectronico']
			))
		->add('InstitucionAcademica', 'text', array(
			'constraints' => array(new Assert\NotBlank()),
			'data' => $persona['InstitucionAcademica']
			))
		->add('CarreraCurso', 'text', array(
			'constraints' => array(new Assert\NotBlank()),
			'data' => $persona['CarreraCurso']
			))
		->add('Nivel', 'text', array(
			'constraints' => array(new Assert\NotBlank()),
			'data' => $persona['Nivel']
			))
		->add('Area', 'choice', array(
            'choices' => array(
            	1 => 'Comunicación', 
            	2 => 'Mercadeo',
            	3=>'Área Educativa',
            	4=>'Programa de Voluntariado',
            	5=>'Monitoreo y Evaluación',
            	6=>'Técnico'),'data' => $persona['Area']))
		->add('DiaInicio','text', array(
			'data' => $persona['DiaInicio']
			))
		->add('DiaFinal','text', array(
			'data' => $persona['DiaFinal']
			));

		$stack = array();
		foreach ($disponibilidad as $disp) {
			if($disp['dia'] == 'lunes'){
				$nuevoform
				->add('trabajoLunes', 'checkbox', array('required' => false,'data' => true))
				->add('fromLunes','text',array(
					'required' => false,
					'data' => $disp['horaInicio']))
				->add('toLunes','text',array(
					'required' => false,
					'data' => $disp['horaFinal']));
				array_push($stack, "lunes");
			}
			elseif ($disp['dia'] == 'martes') {
				$nuevoform
				->add('trabajoMartes', 'checkbox', array('required' => false, 'data' => true))
				->add('fromMartes','text',array(
					'required' => false,
					'data' => $disp['horaInicio']))
				->add('toMartes','text',array(
					'required' => false,
					'data' => $disp['horaFinal']));
				array_push($stack, "martes");
			}
			elseif ($disp['dia'] == 'miercoles') {
				$nuevoform
				->add('trabajoMiercoles', 'checkbox', array('required' => false, 'data' => true))
				->add('fromMiercoles','text',array(
					'required' => false,
					'data' => $disp['horaInicio']))
				->add('toMiercoles','text',array(
					'required' => false,
					'data' => $disp['horaFinal']));
				array_push($stack, "miercoles");
			}
			elseif ($disp['dia'] == 'jueves') {
				$nuevoform
				->add('trabajoJueves', 'checkbox', array('required' => false, 'data' => true))
				->add('fromJueves','text',array(
					'required' => false,
					'data' => $disp['horaInicio']))
				->add('toJueves','text',array(
					'required' => false,
					'data' => $disp['horaFinal']));
				array_push($stack, "jueves");
			}
			elseif ($disp['dia'] == 'viernes') {
				$nuevoform
				->add('trabajoViernes', 'checkbox', array('required' => false, 'data' => true))
				->add('fromViernes','text',array(
					'required' => false,
					'data' => $disp['horaInicio']))
				->add('toViernes','text',array(
					'required' => false,
					'data' => $disp['horaFinal']));
				array_push($stack, "viernes");
			}
		}

		if (!(in_array("lunes", $stack))) {
			$nuevoform
			->add('trabajoLunes', 'checkbox', array('required' => false))
			->add('fromLunes','text',array('required' => false))
			->add('toLunes','text',array('required' => false));
		}
		if (!(in_array("martes", $stack))) {
			$nuevoform
			->add('trabajoMartes', 'checkbox', array('required' => false))
			->add('fromMartes','text',array('required' => false))
			->add('toMartes','text',array('required' => false));
		}
		if (!(in_array("miercoles", $stack))) {
			$nuevoform
			->add('trabajoMiercoles', 'checkbox', array('required' => false))
			->add('fromMiercoles','text',array('required' => false))
			->add('toMiercoles','text',array('required' => false));
		}
		if (!(in_array("jueves", $stack))) {
			$nuevoform
			->add('trabajoJueves', 'checkbox', array('required' => false))
			->add('fromJueves','text',array('required' => false))
			->add('toJueves','text',array('required' => false));
		}
		if (!(in_array("viernes", $stack))) {
			$nuevoform->add('trabajoViernes', 'checkbox', array('required' => false))
			->add('fromViernes','text',array('required' => false))
			->add('toViernes','text',array('required' => false));
		}

		$nuevoform->handleRequest($app['request']);

		if($nuevoform->isValid()){
			$data = $nuevoform->getData();
			//Save a person to the database
			$persona =  array(
				'Nombre' => $data['Nombre'], 
				'NoDeCedula' => $data['NoDeCedula'], 
				'DireccionDeResidencia' => $data['DireccionDeResidencia'], 
				'Telefono' => $data['Telefono'], 
				'CorreoElectronico' => $data['CorreoElectronico'], 
				'InstitucionAcademica' => $data['InstitucionAcademica'], 
				'CarreraCurso' => $data['CarreraCurso'], 
				'Nivel' => $data['Nivel'], 
				'DiaInicio' => $data['DiaInicio'], 
				'DiaFinal' => $data['DiaFinal'],
				'Area' => $data['Area']
				);
			$app['db.persona']->update($persona, array('idPersona' => $id));

			//get the days on which he can work and save it to the database
			$app['db.disponibilidad']->delete(array('Persona_idPersona' => $id));
			if($data['trabajoLunes']){
				$dia = array(
					'Persona_idPersona' => $id,
					'horaInicio' => $data['fromLunes'],
					'horaFinal' => $data['toLunes'],
					'dia' => 'lunes');
				$app['db.disponibilidad']->insert($dia);
			}
			if($data['trabajoMartes']){
				$dia = array(
					'Persona_idPersona' => $id,
					'horaInicio' => $data['fromMartes'],
					'horaFinal' => $data['toMartes'],
					'dia' => 'martes');
				$app['db.disponibilidad']->insert($dia);
			}
			if($data['trabajoMiercoles']){
				$dia = array(
					'Persona_idPersona' => $id,
					'horaInicio' => $data['fromMiercoles'],
					'horaFinal' => $data['toMiercoles'],
					'dia' => 'miercoles');
				$app['db.disponibilidad']->insert($dia);
			}
			if($data['trabajoJueves']){
				$dia = array(
					'Persona_idPersona' => $id,
					'horaInicio' => $data['fromJueves'],
					'horaFinal' => $data['toJueves'],
					'dia' => 'jueves');
				$app['db.disponibilidad']->insert($dia);
			}
			if($data['trabajoViernes']){
				$dia = array(
					'Persona_idPersona' => $id,
					'horaInicio' => $data['fromViernes'],
					'horaFinal' => $data['toViernes'],
					'dia' => 'viernes');
				$app['db.disponibilidad']->insert($dia);
			}

			return $app->redirect($app['url_generator']->generate('voluntarios.detail',array('id' => $id)));
			die();
		}
		$data = array(
			'page' => 'voluntarios',
			'formulario' => 'edit',
			'editpath' => $app['url_generator']->generate('voluntarios.editVoluntario',array('id' => $id)),
			'detailpath' => $app['url_generator']->generate('voluntarios.detail',array('id' => $id)),
			'nuevoform' => $nuevoform->createView(),
			'session' => $app['session']->get('user')
			);
			// Build and return the HTML
		return $app['twig']->render('voluntarios/formulario.twig',$data);
	}


	/**
	 * Volunteer new
	 * @param Application $app An Application instance
	 * @return string A blob of HTML
	 */
	public function newVoluntario(Application $app) {
		//checking if the user is loged in	
		if($app['session']->get('user') == null || empty($app['session']->get('user'))){
			return $app->redirect($app['url_generator']->generate('login'));
			die();
		}elseif ($app['session']->get('user')['nombre'] == 'logger') {
			return $app->redirect($app['url_generator']->generate('logout'));
			die();
		
		}	
		//making of the form
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
			))
		->add('Area', 'choice', array(
            'choices' => array(
            	1 => 'Comunicación', 
            	2 => 'Mercadeo',
            	3=>'Área Educativa',
            	4=>'Programa de Voluntariado',
            	5=>'Monitoreo y Evaluación',
            	6=>'Técnico')))
		->add('DiaInicio','text')
		->add('DiaFinal','text')
		->add('trabajoLunes', 'checkbox',array('required' => false))
		->add('trabajoMartes', 'checkbox',array('required' => false))
		->add('trabajoMiercoles', 'checkbox',array('required' => false))
		->add('trabajoJueves', 'checkbox',array('required' => false))
		->add('trabajoViernes', 'checkbox',array('required' => false))
		->add('fromLunes','text',array('required' => false))
		->add('toLunes','text',array('required' => false))
		->add('fromLunes','text',array('required' => false))
		->add('toLunes','text',array('required' => false))
		->add('fromMartes','text',array('required' => false))
		->add('toMartes','text',array('required' => false))
		->add('fromMiercoles','text',array('required' => false))
		->add('toMiercoles','text',array('required' => false))
		->add('fromJueves','text',array('required' => false))
		->add('toJueves','text',array('required' => false))
		->add('fromViernes','text',array('required' => false))
		->add('toViernes','text',array('required' => false));

		$nuevoform->handleRequest($app['request']);

		if($nuevoform->isValid()){
			$data = $nuevoform->getData();

			//Save a person to the database
			$persona =  array(
				'Nombre' => $data['Nombre'], 
				'NoDeCedula' => $data['NoDeCedula'], 
				'DireccionDeResidencia' => $data['DireccionDeResidencia'], 
				'Telefono' => $data['Telefono'], 
				'CorreoElectronico' => $data['CorreoElectronico'], 
				'InstitucionAcademica' => $data['InstitucionAcademica'], 
				'CarreraCurso' => $data['CarreraCurso'], 
				'Nivel' => $data['Nivel'], 
				'DiaInicio' => $data['DiaInicio'], 
				'DiaFinal' => $data['DiaFinal'],
				'Area' => $data['Area']
				);
			$app['db.persona']->insert($persona);

			//Get the id of the person
			$id = $app['db.persona']->getIdByCedula($data['NoDeCedula'])['id'];

			//get the days on which he can work and save it to the database
			if($data['trabajoLunes']){
				$dia = array(
					'Persona_idPersona' => $id,
					'horaInicio' => $data['fromLunes'],
					'horaFinal' => $data['toLunes'],
					'dia' => 'lunes');
				$app['db.disponibilidad']->insert($dia);
			}
			if($data['trabajoMartes']){
				$dia = array(
					'Persona_idPersona' => $id,
					'horaInicio' => $data['fromMartes'],
					'horaFinal' => $data['toMartes'],
					'dia' => 'martes');
				$app['db.disponibilidad']->insert($dia);
			}
			if($data['trabajoMiercoles']){
				$dia = array(
					'Persona_idPersona' => $id,
					'horaInicio' => $data['fromMiercoles'],
					'horaFinal' => $data['toMiercoles'],
					'dia' => 'miercoles');
				$app['db.disponibilidad']->insert($dia);
			}
			if($data['trabajoJueves']){
				$dia = array(
					'Persona_idPersona' => $id,
					'horaInicio' => $data['fromJueves'],
					'horaFinal' => $data['toJueves'],
					'dia' => 'jueves');
				$app['db.disponibilidad']->insert($dia);
			}
			if($data['trabajoViernes']){
				$dia = array(
					'Persona_idPersona' => $id,
					'horaInicio' => $data['fromViernes'],
					'horaFinal' => $data['toViernes'],
					'dia' => 'viernes');
				$app['db.disponibilidad']->insert($dia);
			}
			return $app->redirect($app['url_generator']->generate('voluntarios.detail',array('id' => $id)));
		}
		$data = array(
			'page' => 'voluntarios',
			'formulario' => 'new',
			'nuevoform' => $nuevoform->createView(),
			'session' => $app['session']->get('user')
			);
			// Build and return the HTML
		return $app['twig']->render('voluntarios/formulario.twig',$data);
	}
}