<?php

// Bootstrap
require __DIR__ . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app->error(function (\Exception $e, $code) use ($app) {
	if ($code == 404) {
		return $app['twig']->render('errors/404.twig', array('error' => $e->getMessage()));
	} else {
		return 'Something went wrong! // ' . $e->getMessage();
	}
});

$app->get('/', function(Silex\Application $app) {
	return $app->redirect($app['request']->getBaseUrl() . '/voluntarios');
});

// Mount our Controllers
$app->mount('/voluntarios/', new ZamoraTeran\Provider\Controller\voluntarios\voluntariosController());