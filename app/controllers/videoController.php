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
		echo $_POST["capStart"];
		echo "<br/>";
		echo $_POST["capFinish"];
		echo "<br/>";
		echo $_POST["pseudocode"];
		echo "<br/>";
		$tabStart = explode(",",$_POST["capStart"]);
		$tabFinish = explode(",",$_POST["capFinish"]);

		for ($i=0; $i < count($tabStart); $i++) { 
			$seq = new Sequence();
			$seq->idVideo = $_POST['idVideo'];
			$seq->debut = $tabStart[$i];
			$seq->fin = $tabFinish[$i];
			$seq->idUser = $_SESSION['user'];
			//echo $seq;
			//$seq->save();
		}

		//header('Location: video/'.$_POST['idVideo']);
		//exit();
	}

	public function getObject($request, $response){
		$array = array();

		$objet = Dictionnaire::where("type", "=", "object")->where("libelle", "like", $request->getAttribute('route')->getArgument('recherche')."%")->get();

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
			array_push($array, $video[$i]["lien"]);
		}

		return json_encode($array);
	}

	public function getAllVideos($request, $response){
		$array = array();

		$video = Video::get();

		for ($i=0; $i < count($video); $i++) { 	
			array_push($array, $video[$i]["lien"]);
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
			array_push($array, $video[$i]["lien"]);
		}

		return json_encode($array);		
	}
}