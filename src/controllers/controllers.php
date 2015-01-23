<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;



#$app->match('/', function ($view) use ( $app ,$product ) {
	#return $app->redirect($app['url_generator']->generate('view'));
#})->method('GET|POST');


$app->match('/{view}/{id}', function ($view,$id) use ( $app ,$product, $provider, $stock ) {

	$array = array();
	switch ($view) {
		/**

		*/
		case 'productList':
			$template 	= 'productList.twig';
			$array 		= array( "table" => $product->crudProduct("read") );
		break;

		case 'productEdit':
			$template 	= 'productEdit.twig';
			$array 		= array( "table" => $product->crudProduct("edit",$id) , "id" => $id );
		break;

		case 'product':
			$template 	= 'product.twig';
			$array 		= array();
		break;
		/**

		*/
		case 'provider':
			$template = 'provider.twig';
		break;

		case 'providerList':
			$template = 'providerList.twig';
			$array 		= array( "table" => $provider->crudProvider("read") );
		break;

		case 'providerEdit':
			$template 	= 'providerEdit.twig';
			$array 		= array( "table" => $provider->crudProvider("edit",$id) , "id" => $id );
		break;
		/**

		*/
		case 'stock':
			$template = 'stock.twig';
			$array 		= array( "provider" => $provider->getProvider(), "product" => $product->getProduct() );
		break;

		case 'stockList':
			$template = 'stockList.twig';
			$array 		= array( "table" => $stock->crudStock("read") );
		break;

		case 'stockEdit':
			$template 	= 'stockEdit.twig';
			$array 		= array( "table" => $stock->crudStock("edit",$id) , "id" => $id );
		break;
	}

	return new Response(
		$app['twig']->render( $template, $array )
	);

})->method('GET|POST')->value("view","productList")->value("id",false)->bind('view');



$app->match('/crud/{model}/{action}', function ($model,$action) use ( $app ,$product, $provider, $stock ) {

	switch ($model) {
		case 'product':
			return $app->json($product->crudProduct($action,$_POST['params']));
		break;
		
		case 'provider':
			return $app->json($provider->crudProvider($action,$_POST['params']));
		break;

		case 'stock':
			return $app->json($stock->crudStock($action,$_POST['params']));
		break;
	}

})->method('GET|POST')->value("action",false)->value("model",false);

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