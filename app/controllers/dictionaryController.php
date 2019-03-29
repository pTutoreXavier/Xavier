<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \Respect\Validation\Validator as v;
use \App\Models\Dictionnaire;
use \App\Models\Sequence;
use \App\Models\Commentaire;

class DictionaryController extends Controller{
	//page d'accueil du dictionnaire
	public function index($request, $response){
		$methods = Dictionnaire::where("type", "method")->get();
		$objects = Dictionnaire::where("type", "objet")->get();
		return $this->view->render($response, "dictionary/dictionary.twig", ["elements" => ["Objets" => $objects, "Fonctions" => $methods]]);
	}

	//récupère un élément et redirige vers la page de modification,suppression ou la page de détails
	public function getById($request, $response, $args){
		$element = Dictionnaire::find($args["id"]);
		//page de modification
		if($request->getParam("action") !== null && $request->getParam("action") == "edit"){
			$response = $this->view->render($response, "dictionary/edit.twig", ["element" => $element, "parametres" => explode("; ", $element->parametre)]);
		}
		//suppression
		elseif($request->getParam("action") !== null && $request->getParam("action") == "delete"){
			$response = $this->delete($request, $response, $args);
		}
		//details de l'élément
		else{
			$sequences = Sequence::where("pseudocode", "like", $element->id.";%")->orWhere("pseudocode", "like", "%;".$element->id.";%")->orWhere("pseudocode", "like", "%;".$element->id)->get();
			foreach($sequences as $sequence){
				$pseudocode = explode(";", $sequence->pseudocode);
				$s = "";
				for($i = 0; $i < count($pseudocode); $i++){
					$e = Dictionnaire::where("id", "=", $pseudocode[$i])->first();
					if($i > 2){
						$s .= ", ";
					}
					$s .= $e->libelle;
					if($i == 0){
						$s .=  ".";
					}
					if($i == 1){
						$s .= "(";
					}
				}
				$s .= ")";
				$sequence->pseudocode = $s;
				$sequence->commentaires = Commentaire::select("commentaire")->where("idSequence", $sequence->id)->get();
			}
			$response = $this->view->render($response, "dictionary/details.twig", ["element" => $element, "sequences" => $sequences]);
		}		
		return $response;
	}

	//page de création d'un élément
	public function new($request, $response){
		return $this->view->render($response, "dictionary/new.twig", ["type" => $request->getParam("type")]);
	}

	//enregistrement du nouvel élément
	public function create($request, $response, $args){
		$params = $request->getParams();
		if($params["type"] == "method"){
	    	foreach($params as $key => $value) {
	    		if($key == "type" || $key == "libelle" || substr($key, 0, 9) == "parametre"){
					$validation = $this->validator->validate($request, [
				        $key=> v::alpha()
				    ]);
				}	    		
	    	}	    	
	    }
	    else{
	    	$validation = $this->validator->validate($request, [
		        'libelle' => v::notEmpty()->alpha()
		    ]);
	    }
        if($validation->failed()){
            return $response->withRedirect($this->router->pathFor('dictionary.create'));
        }
		$element = new Dictionnaire;
		$element->type = $params["type"];
		$element->libelle = $params["libelle"];
		foreach($params as $key => $value){
			if(substr($key, 0, 9) == "parametre"){
				if($element->parametre === null){
					$element->parametre = $value;
				}
				else{
					$element->parametre .= "; ".$value;
				}
			}
		}
		$element->save();
		return $response->withRedirect($this->router->pathFor('dictionary'));
	}

	//modification de l'élément
	public function update($request, $response, $args){
		$params = $request->getParams();
		$element = Dictionnaire::find($args["id"]);
		if($element->type = "method"){
	    	foreach($params as $key => $value) {
	    		if($value != ""){
	    			if($key == "type" || $key == "libelle" || substr($key, 0, 9) == "parametre"){
						$validation = $this->validator->validate($request, [
					        $key => v::alpha()
					    ]);
					}	
	    		}    		
	    	}	    	
	    }
	    else{
	    	$validation = $this->validator->validate($request, [
		        'libelle' => v::notEmpty()->alpha()
		    ]);
	    }
        if($validation->failed()){
            return $response->withRedirect($this->router->pathFor('dictionary.details', ["id" => $args["id"]]));
        }
        $element->libelle = $params["libelle"];
        $element->parametre = "";
		foreach($params as $key => $value){
			if(substr($key, 0, 9) == "parametre"){				
				if($value != ""){
					if($element->parametre == ""){
					$element->parametre .= $value;
					}
					else{
						$element->parametre .= "; ".$value;
					}
				}
			}
		}
		$element->save();
	    return $response->withRedirect($this->router->pathFor("dictionary.details", ["id" => $args["id"]]));
	}

	//suppression de l'élément
	public function delete($request, $response, $args){
        $element = Dictionnaire::find($args["id"]);
	    $element->delete();
	    return $response->withRedirect($this->router->pathFor("dictionary"));
	}

	//page pour choisir dans quel format exporter les données
	public function viewExport($request, $response, $args){
		return $this->view->render($response, "dictionary/export.twig", ["formats" => ["xml", "csv", "json"]]);
	}

	//création des données et du fichier qui sera téléchargé
	public function export($request, $response, $args){
		$format = $args["format"];
		if(in_array($format, ["xml", "csv", "json"])){
			$data = [];
			$sequences = Sequence::select(["id", "pseudocode"])->get();
			foreach($sequences as $sequence){
				$pseudocode = explode(";", $sequence->pseudocode);
				$s = "";
				for($i = 0; $i < count($pseudocode); $i++){
					if($i > 2){
						$s .= ", ";
					}
					$s .= $pseudocode[$i];
					if($i == 0){
						$s .=  ".";
					}
					if($i == 1){
						$s .= "(";
					}
				}
				$s .= ")";
				$commentaires = Commentaire::where("idSequence", "=", $sequence->id)->get();
				$data[$s] = [];
				foreach ($commentaires as $commentaire){
					array_push($data[$s], $commentaire->commentaire);
				}
			}
			$name = "dictionnaire_".$format."_".date("d-m-Y");
			$path = "../ressources/";
			$this->$format($data, $name, $path);
			// désactive la mise en cache
			header("Cache-Control: no-cache, must-revalidate");
			header("Cache-Control: post-check=0,pre-check=0");
			header("Cache-Control: max-age=0");
			header("Pragma: no-cache");
			header("Expires: 0");			 
			// force le téléchargement du fichier avec un beau nom
			header("Content-Type: application/force-download");
			header('Content-Disposition: attachment; filename="'.$name.'.'.$format.'"');
			readfile($path.$name.'.'.$format);
			unlink($path.$name.'.'.$format);
		}
	}

	//création d'un fichier xml à partir des données
	private function xml($data, $name, $path){
		$xml = new \XMLWriter();
		$xml->openMemory();
		$xml->startDocument('2.0', 'utf-8');
		$xml->startElement("data");	
		foreach($data as $key => $value){
			$xml->startElement("sequence");			
			$xml->writeAttribute("pseudocode", $key);
			foreach ($value as $commentaire){
				$xml->writeElement('commentaire', $commentaire);
			}
			$xml->endElement();
		}
		$xml->endElement();
		$xml->endDocument();
		$file = fopen($path.$name.".xml", "w+");
		fwrite($file, $xml->flush());
		fclose($file);
	}

	//création d'un fichier csv à partir des données
	private function csv($data, $name, $path){
		$file = fopen($path.$name.".csv", "w+");
		fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($file, ["sequence", "commentaires"], ";");
		foreach($data as $key => $value){
			for($i = 0; $i < count($value); $i++){
				if($i == 0){
					fputcsv($file, [$key, $value[$i]], ";");
				}
				else{
					fputcsv($file, ["", $value[$i]], ";");
				}
			}
		}
		fclose($file);
	}

	//création d'un fichier json à partir des données
	private function json($data, $name, $path){
		$file = fopen($path.$name.".json", "w+");
		fwrite($file, json_encode($data));
		fclose($file);
	}
}