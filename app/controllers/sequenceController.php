<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \App\Models\Video as Video;
use \App\Models\Sequence as Sequence;
use \App\Models\Dictionnaire as Dictionnaire;
class VideoController extends Controller{
	public function index($request, $response){
		$video = Video::find(1);
		$seq = Sequence::find(1);
		$split = explode(";", $seq["pseudocode"]);
		$tab = array();

		for($i = 0; $i< count($split); $i++){
			$dico = Dictionnaire::find($split[$i]);
			array_push($tab,$dico["libelle"]);
		}
		return $this->view->render($response, "video/sequence.twig", array("video" => $video, "seq" => $seq , "tab" => $tab));
	}
}