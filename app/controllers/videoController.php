<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \App\Models\Video as Video;
use \App\Models\Sequence as Sequence;
use \App\Models\Dictionnaire as Dictionnaire;

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
			$seq->idUser = $_SESSION['idUser'];
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
}