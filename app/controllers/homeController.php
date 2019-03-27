<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \App\Models\User;
class HomeController extends Controller{

	public function index($request, $response){
        return $this->view->render($response, "home.twig");
	}

    public function mentions($request, $response){
        return $this->view->render($response, "mentions.twig");
    }

    public function technos($request, $response){
        return $this->view->render($response, "technos.twig");
    }
}