<?php
session_start();

require __DIR__."/../vendor/autoload.php";

$conf = parse_ini_file(__DIR__."/../app/conf/conf.ini");

$app = new \Slim\App([
	"settings" => [
		"displayErrorDetails" => true,
		"db" => [
			"driver" => $conf["driver"],
			"host" => $conf["host"],
			"database" => $conf["database"],
			"username" => $conf["username"],
			"password" => $conf["password"],
			"charset" => $conf["charset"],
			"collation" => $conf["collation"],
			"prefix" => $conf["prefix"]
		]
	]
]);
$container = $app->getContainer();
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container["settings"]["db"]);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$container["db"] = function($container) use ($capsule){
	return $capsule;
};
$container["view"] = function($container){
	$view = new \Slim\Views\Twig(__DIR__."/../ressources/views", [
		"cache" => false,
	]);
	$view->addExtension(new \Slim\Views\TwigExtension(
		$container->router,
		$container->request->getUri()
	));
	return $view;
};
$container["HomeController"] = function($container){
	return new \App\Controllers\HomeController($container);
};
$container["AuthController"] = function($container){
	return new \App\Controllers\Auth\AuthController($container);
};
$container["VideoController"] = function($container){
	return new \App\Controllers\VideoController($container);
};
require __DIR__."/../app/routes.php";