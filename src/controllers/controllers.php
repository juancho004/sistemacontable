<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;




$app->match('/', function () use ( $app ,$product ) {

	return new Response(
		$app['twig']->render( 'product.twig', array() )
	);

})->method('GET|POST');



$app->match('/producto/{action}', function ($action) use ( $app ,$product ) {

	return $app->json($product->crudProduct($action,$_POST['params']));

})->method('GET|POST')->value("action",false);

/*

$app->match('/report', function () use ( $app ,$registerCode ) {

	$isActive = $registerCode->validateSessionActive();

	if( !$isActive->redirect ) {
		return new Response(
			$app['twig']->render( 'login.twig', array() )
		);

	} else {
		$content = $registerCode->getReport();
		return new Response(
			$app['twig']->render( 'report.twig', array( 'report' => $content['content'] ) )
		);
	
	}

	
})->method('GET|POST');



$app->match('/login', function () use ( $app ,$registerCode ) {

	$isActive = $registerCode->validateSession($_POST['user'],$_POST['password']);
	return $app->redirect('./report' );

	
})->method('GET|POST');



$app->match('/export', function () use ( $app ,$registerCode ) {

	$export = $registerCode->getReport($_GET,false);

	$date = "Reporte_".date("Y-m-d-H-i-s");

	header("Content-Type:   application/vnd.ms-excel;");
	header("Content-Disposition: attachment; filename={$date}.xls");  //File name extension was wrong
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	
	echo $export['content'];
	exit;

})->method('GET|POST');
*/