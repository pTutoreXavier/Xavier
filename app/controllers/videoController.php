<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \App\Models\Video as Video;
use \App\Models\Sequence as Sequence;

class VideoController extends Controller{
	public function index($request, $response){
		$idVideo = $request->getAttribute('route')->getArgument('idVideo');
		$_SESSION["idUser"] = 1;
		$video = Video::find($idVideo);

		$sequences = Sequence::where('idVideo', '=', $idVideo)->get();

		return $this->view->render($response, "video/video.twig", array("video" => $video, 'idVideo' => $idVideo, 'lesSequences' => $sequences));
	}

	public function createSequence($request, $response){
		$tabStart = explode(",",$_POST["capStart"]);
		$tabFinish = explode(",",$_POST["capFinish"]);
		$today = date("Y-m-d"); 

		for ($i=0; $i < count($tabStart); $i++) { 
			$seq = new Sequence();
			$seq->idVideo = $_POST['idVideo'];
			$seq->date = $today;
			$seq->debut = $tabStart[$i];
			$seq->fin = $tabFinish[$i];
			$seq->idUser = $_SESSION['idUser'];
			echo $seq;
			$seq->save();
		}

		header('Location: video/'.$_POST['idVideo']);
		exit();
	}
}