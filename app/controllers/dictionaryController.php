<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \App\Models\Dictionary;
class DictionaryController extends Controller{
	public function index($request, $response){
		$methods = Dictionary::where("type", "methode")->get();
		$objects = Dictionary::where("type", "objet")->get();
		return $this->view->render($response, "dictionary.twig", array("methods" => $methods, "objects" => $objects));
	}

	public function getById($request, $response){
		
	}
}