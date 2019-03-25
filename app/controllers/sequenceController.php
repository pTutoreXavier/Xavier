<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \App\Models\Video as Video;
use \App\Models\Sequence as Sequence;
use \App\Models\Commentaire as Commentaire;

class SequenceController extends Controller{
	public function index($request, $response){
		$_SESSION["idUser"] = 1;
		$idVideo = $request->getAttribute('route')->getArgument('idVideo');
		$idSequence = $request->getAttribute('route')->getArgument('idSequence');

		$video = Video::find($idVideo);

		$seq = Sequence::where('id', '=', $idSequence)->where('idVideo', '=', $idVideo)->first();

		$message = Commentaire::where('idUser', '=', $_SESSION["idUser"])->where('idSequence','=',$idSequence)->first();

		return $this->view->render($response, "video/sequence.twig", array("video" => $video, "seq" => $seq, "message" => $message, 'idVideo' => $idVideo, 'idSequence' => $idSequence));
	}

	public function commentaire($request, $response){
		$c = new Commentaire();
		$c->idUser = $_SESSION["idUser"];
		$c->idSequence = $_POST['idSequence'];
		$c->commentaire = $_POST["message"];

		$c->save();

		header('Location: sequence/'.$_POST['idVideo'].'/'.$_POST['idSequence']);
		exit();
	}
}