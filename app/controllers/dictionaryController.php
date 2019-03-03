<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \App\Models\Dictionary;
use \App\Models\Sequence;
use \App\Models\Commentary;

class DictionaryController extends Controller{
	public function index($request, $response){
		$methods = Dictionary::where("type", "method")->get();
		$objects = Dictionary::where("type", "object")->get();
		return $this->view->render($response, "dictionary/dictionary.twig", array("methods" => $methods, "objects" => $objects));
	}

	public function getById($request, $response, $args){
		$element = Dictionary::find($args["id"]);
		$sequences = Sequence::where("pseudocode", "like", $args["id"].";%")->orWhere("pseudocode", "like", "%;".$args["id"].";%")->orWhere("pseudocode", "like", "%;".$args["id"])->get();
		return $this->view->render($response, "dictionary/details.twig", array("element" => $element, "sequences" => $sequences));
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

	public function viewExport($request, $response, $args){
		return $this->view->render($response, "dictionary/export.twig");
	}

	public function export($request, $response, $args){
		$data = array();
		$sequences = Sequence::select(["id", "pseudocode"])->get();
		foreach($sequences as $sequence){
			$pseudocode = explode(";", $sequence->pseudocode);
			$s = "";
			for($i = 0; $i < count($pseudocode); $i++){
				$element = Dictionary::where("id", "=", $pseudocode[$i])->first();
				if($i > 2){
					$s .= ", ";
				}
				$s .= $element->libelle;
				if($i == 0){
					$s .=  ".";
				}
				if($i == 1){
					$s .= "(";
				}
			}
			$s .= ")";
			$commentaires = Commentary::where("idSequence", "=", $sequence->id)->get();
			$data[$s] = array();
			foreach ($commentaires as $commentaire){
				array_push($data[$s], $commentaire->commentaire);
			}
		}
		$method = $args["format"];
		$name = "test";
		$path = "../ressources/temp/";
		$this->$method($data, $name, $path);
		header('Content-disposition: attachment; filename="'.$name.'.'.$args["format"].'"');
		header('Content-Type: application/force-download');
		header('Content-Transfer-Encoding: binary');
		readfile($path.$name.'.'.$args["format"]);
		unlink($path.$name.'.'.$args["format"]);
	}

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

	private function csv($data, $name, $path){
		$file = fopen($path.$name.".csv", "w+");
		fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
		fputcsv($file, array("sequence", "commentaires"), ";");
		foreach($data as $key => $value){
			for($i = 0; $i < count($value); $i++){
				if($i == 0){
					fputcsv($file, array($key, $value[$i]), ";");
				}
				else{
					fputcsv($file, array("", $value[$i]), ";");
				}
			}
		}
		fclose($file);
	}

	private function json($data, $name, $path){
		$file = fopen($path.$name.".json", "w+");
		fwrite($file, json_encode($data));
		fclose($file);
	}
}