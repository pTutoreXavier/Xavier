<?php

use Respect\Validation\Validator as v;


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

$container['validator'] = function($container){
    return new \App\validation\Validator;
};


// CONTROLLERS
$container["HomeController"] = function($container){
	return new \App\Controllers\HomeController($container);
};
$container["AuthController"] = function($container){
	return new \App\Controllers\Auth\AuthController($container);
};

// SECURITE : CSRF
$container['csrf'] = function ($container) {
    return new \Slim\Csrf\Guard;
};

// CONNECTION
$container['auth'] = function ($container) {
    return new \App\Auth\Auth;
};

// MIDDLEWARES
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));

$app->add($container->csrf);


//REGLES DE VALIDATION
v::with('App\\Validation\\Rules\\');

require __DIR__."/../app/routes.php";