<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \App\Models\User as User;
class ProfilController extends Controller{
	public function index($request, $response){
		$user = User::find(1);/* A remplacer par l'id  dans la session*/
		return $this->view->render($response, 'profil/profil.twig', array(
			"id" => $user->id, 
			"nom" => $user->nom,
			"prenom" => $user->prenom,
			"mail" => $user->mail,
			"mdp" => $user->mdp,
			"level" => $user->level));
	}
	public function updatePass($request, $response){
		$user = User::find(1);/* A remplacer par l'id  dans la session*/
		if ($response) {
			var_dump($request);
		}
		return $this->view->render($response, 'profil/profilUpdatePass.twig', array(
			"id" => $user->id, 
			"nom" => $user->nom,
			"prenom" => $user->prenom,
			"mail" => $user->mail,
			"mdp" => $user->mdp,
			"level" => $user->level));
	}
	public function updateMail($request, $response){
		$user = User::find(1);/* A remplacer par l'id  dans la session*/
		return $this->view->render($response, 'profil/profilUpdate.twig', array(
			"id" => $user->id, 
			"nom" => $user->nom,
			"prenom" => $user->prenom,
			"mail" => $user->mail,
			"mdp" => $user->mdp,
			"level" => $user->level));
	}
	public function check($request, $response){
		var_dump($_POST);
		if ($_POST['newPass'] != $_POST['newPass2']) {
			return $response->withRedirect($this->router->pathFor('profilUpdatePass'));
		}
		else{
			return $response->withRedirect($this->router->pathFor('profil'));
		}

	}
}