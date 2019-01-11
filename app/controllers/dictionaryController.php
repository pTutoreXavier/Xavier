<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \App\Models\Dictionary;
class DictionaryController extends Controller{
	public function index($request, $response){
		$methods = Dictionary::where("type", "method")->get();
		$objects = Dictionary::where("type", "object")->get();
		return $this->view->render($response, "dictionary/dictionary.twig", array("methods" => $methods, "objects" => $objects));
	}

	public function getById($request, $response, $args){
		$element = Dictionary::find($args["id"]);
		return $this->view->render($response, "dictionary/details.twig", array("element" => $element));
	}

	public function new($request, $response, $args){
		return $this->view->render($response, "dictionary/new.twig", array("type" => $args["type"]));
	}

	public function create($request, $response, $args){
		$element = new Dictionary();
		$element->type = $args["type"];
		$element->libelle = $request->getParam("name");
		if($args["type"] == "method"){
			$element->parametre = $request->getParam("parameter");
		}
		$element->save();
		return $response->withRedirect($this->router->pathFor('dictionary'));
	}
}