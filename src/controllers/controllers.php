<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


$app->match('/registersale', function () use ( $app, $sale, $client, $master ) {

	#_pre(empty($_POST['select_client']),true );
	#_pre(empty(),true );
	#_pre(empty($_POST['totalStock']),true );

	$sale->registerSale($_POST);
	return $app->redirect('./sale' );
	
})->method('GET|POST');

$app->match('/itemSale', function () use ( $app, $sale ) {

	$item = array("item" => $sale->productSale(true,$_POST['number']) );
	return $app->json($item);

})->method('GET|POST');


$app->match('/{view}/{id}', function ($view,$id) use ( $app ,$product, $provider, $stock, $client, $sale, $master ) {

	$array = array();
	$template ="";
	switch ($view) {
		/**
		PRODUCT
		*/
		case 'productList':
			$template 	= 'productList.twig';
			$array 		= array( "table" => $product->crudProduct("read"),"menu" => $master->getMenu() );
		break;

		case 'productEdit':
			$template 	= 'productEdit.twig';
			$array 		= array( "table" => $product->crudProduct("edit",$id) , "id" => $id,"menu" => $master->getMenu() );
		break;

		case 'product':
			$template 	= 'product.twig';
			$array 		= array("menu" => $master->getMenu());
		break;
		/**
		PROVIDER
		*/
		case 'provider':
			$template = 'provider.twig';
			$array = array("menu" => $master->getMenu());
		break;

		case 'providerList':
			$template = 'providerList.twig';
			$array 		= array( "table" => $provider->crudProvider("read"),"menu" => $master->getMenu() );
		break;

		case 'providerEdit':
			$template 	= 'providerEdit.twig';
			$array 		= array( "table" => $provider->crudProvider("edit",$id) , "id" => $id,"menu" => $master->getMenu() );
		break;
		/**
		STOCK
		*/
		case 'stock':
			$template = 'stock.twig';
			$array 		= array( "provider" => $provider->getProvider(), "product" => $product->getProduct(),"menu" => $master->getMenu() );
		break;

		case 'stockList':
			$template = 'stockList.twig';
			$array 		= array( "table" => $stock->crudStock("read"),"menu" => $master->getMenu() );
		break;

		case 'stockEdit':
			$template 	= 'stockEdit.twig';
			$array 		= array( "table" => $stock->crudStock("edit",$id) , "id" => $id,"menu" => $master->getMenu() );
		break;
		/**
		CLIENT
		*/
		case 'client':
			$template = 'client.twig';
			$array 		= array("menu" => $master->getMenu());
		break;

		case 'clientList':
			$template = 'clientList.twig';
			$array 		= array( "table" => $client->crudClient("read"),"menu" => $master->getMenu() );
		break;

		case 'clientEdit':
			$template 	= 'clientEdit.twig';
			$array 		= array( "table" => $client->crudClient("edit",$id) , "id" => $id,"menu" => $master->getMenu() );
		break;
		/**
		SALE
		*/
		case 'sale':
			$template 	= 'sale.twig';
			$array 		= array( "client" => $client->getClient(), "productSale" => $sale->productSale(),"menu" => $master->getMenu() );
		break;
	}

	return new Response(
		$app['twig']->render( $template, $array )
	);

})->method('GET|POST')->value("view","productList")->value("id",false)->bind('view');



$app->match('/crud/{model}/{action}', function ($model,$action) use ( $app ,$product, $provider, $stock, $client ) {

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

		case 'client':
			return $app->json($client->crudClient($action,$_POST['params']));
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