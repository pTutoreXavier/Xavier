<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \App\Models\User;
class HomeController extends Controller{
	public function index($request, $response){
	    $level = $request->getAttribute('level');
        return $this->view->render($response, "home.twig", ['level' => $level]);
	}
}