<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \App\Models\Video as Video;
use \App\Models\Sequence as Sequence;
use \App\Models\Dictionnaire as Dictionnaire;
use \App\Models\User as User;

class VideoController extends Controller{
	public function index($request, $response){
		$idVideo = $request->getAttribute('route')->getArgument('idVideo');
		$_SESSION["idUser"] = 1;
		$video = Video::find($idVideo);

		$sequences = Sequence::where('idVideo', '=', $idVideo)->get();

		return $this->view->render($response, "video/video.twig", array("video" => $video, 'idVideo' => $idVideo, 'lesSequences' => $sequences));
	}

	public function createSequence($request, $response){

		var_dump($_POST);


		/*for ($i=0; $i < count($tabStart); $i++) { 
			$seq = new Sequence();
			$seq->idVideo = $_POST['idVideo'];
			$seq->debut = $tabStart[$i];
			$seq->fin = $tabFinish[$i];
			$seq->idUser = $_SESSION['user'];
			$seq->pseudocode = $objet[$i].";".$method[$i].";".$params[$i];
			echo $seq;
			$seq->save();
		}

		header('Location: video/'.$_POST['idVideo']);
		exit();*/
	}

	public function getObjet($request, $response){
		$array = array();

		$objet = Dictionnaire::where("type", "=", "objet")->where("libelle", "like", $request->getAttribute('route')->getArgument('recherche')."%")->get();

		for ($i=0; $i < count($objet); $i++) { 	
			array_push($array, $objet[$i]["libelle"]);
		}

		return json_encode($array);
	}

	public function getMethod($request, $response){
		$array = array();

		$objet = Dictionnaire::where("type", "=", "method")->where("libelle", "like", $request->getAttribute('route')->getArgument('recherche')."%")->get();

		for ($i=0; $i < count($objet); $i++) { 	
			array_push($array, $objet[$i]["libelle"]);
		}

		return json_encode($array);
	}

	public function upload($request, $response){
		return $this->view->render($response, "video/upload.twig");
	}

	public function getVideos($request, $response){
		return $this->view->render($response, "video/lesVideos.twig");
	}

	public function getVideosSearcher($request, $response){
		$array = array();

		$video = Video::where("idChercheur","=", $_SESSION["user"])->get();

		for ($i=0; $i < count($video); $i++) { 	
			array_push($array, $video[$i]);
		}

		return json_encode($array);
	}

	public function getAllVideos($request, $response){
		$array = array();

		$video = Video::get();

		for ($i=0; $i < count($video); $i++) { 	
			array_push($array, $video[$i]);
		}

		return json_encode($array);
	}

	public function getSearcher($request, $response){
		$array = array();
		$recherche = $request->getAttribute('route')->getArgument('recherche');
		$maj = strtoupper($recherche);

		$searcher = User::where("nom","like", $recherche."%")->orWhere("nom","like", $maj."%")->where("level","=","779")->get();

		for ($i=0; $i < count($searcher); $i++) { 	
			array_push($array, $searcher[$i]["nom"]);
		}

		return json_encode($array);
	}

	public function getVideoOfSearcher($request, $response){
		$array = array();
		$recherche = $request->getAttribute('route')->getArgument('recherche');

		$user = User::where("nom","=",$recherche)->get("id");

		$video = Video::where("idChercheur", "=", $user[0]->id)->get();

		for ($i=0; $i < count($video); $i++) { 	
			array_push($array, $video[$i]);
		}

		return json_encode($array);		
	}

	public function param($request, $response){
		$recherche = $request->getAttribute('route')->getArgument('recherche');

		$param = Dictionnaire::select("parametre")->where("type","=","method")->where("libelle","=",$recherche)->first();

		if($param != null){
			if($param->parametre == "objet"){
				$result = 2;
			}
			else{
				$result = 1;
			}
		}
		else{
			$result = 0;
		}	

		return json_encode($result);
	}

	public function inMethod($request, $response){

		$recherche = $request->getAttribute('route')->getArgument('recherche');

		$method = Dictionnaire::where("type","=","method")->where("libelle","=",$recherche)->first();

		if($method != null){
			$result = 1;
		}
		else{
			$result = 0;
		}
		return json_encode($result);

	}

	public function inObjet($request, $response){

		$recherche = $request->getAttribute('route')->getArgument('recherche');

		$objet = Dictionnaire::where("type","=","objet")->where("libelle","=",$recherche)->first();

		if($objet != null){
			$result = 1;
		}
		else{
			$result = 0;
		}
		return json_encode($result);

	}
}