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
			"dateNaissance" => $user->dateNaissance,
			"mdp" => $user->mdp,
			"level" => $user->level));
	}
	public function updatePass($request, $response){
		$user = User::find(1);/* A remplacer par l'id  dans la session*/
		if (isset($_SESSION['errorPass'])) {
			echo $_SESSION['errorPass'];
		}
		session_destroy();
		return $this->view->render($response, 'profil/profilUpdatePass.twig', array(
			"id" => $user->id, 
			"nom" => $user->nom,
			"prenom" => $user->prenom,
			"mail" => $user->mail,
			"dateNaissance" => $user->dateNaissance,
			"mdp" => $user->mdp,
			"level" => $user->level));
	}
	public function updateMail($request, $response){
		$user = User::find(1);/* A remplacer par l'id  dans la session*/
		if (isset($_SESSION['errorMail'])) {
			echo $_SESSION['errorMail'];
		}
		session_destroy();
		return $this->view->render($response, 'profil/profilUpdateMail.twig', array(
			"id" => $user->id, 
			"nom" => $user->nom,
			"prenom" => $user->prenom,
			"mail" => $user->mail,
			"dateNaissance" => $user->dateNaissance,
			"mdp" => $user->mdp,
			"level" => $user->level));
	}
	public function checkPass($request, $response){
		if ($_POST['newPass'] != $_POST['newPass2']) {
			$_SESSION['errorPass'] = 'Les mot de passe saisie ne correspondent pas';
			return $response->withRedirect($this->router->pathFor('updatePass'));
		}
		elseif (strlen($_POST['newPass']) < 8){
			$_SESSION['errorPass'] = 'Le mot de passe est trop court (8 caractères)';
			return $response->withRedirect($this->router->pathFor('updatePass'));
		}
		elseif ($_POST['newPass'] == $_POST['currentPass']){
			$_SESSION['errorPass'] = 'Le mot de passe ne doit pas être identique a l\'ancien';
			return $response->withRedirect($this->router->pathFor('updatePass'));
		}
		else{ 
			$passHash = password_hash ($_POST['newPass'] , PASSWORD_DEFAULT);
			$user = User::find(1);/* A remplacer par l'id  dans la session*/
			if (password_verify ( $_POST['currentPass'] , $user->mdp )) {
				echo "ok";
				$user->mdp = $passHash;
				$user->save();
				return $response->withRedirect($this->router->pathFor('profil'));
			}
			else
			{
				$_SESSION['errorPass'] = 'L\'ancien mot de passe ne correspond pas avec la base de données.';
				return $response->withRedirect($this->router->pathFor('updatePass'));
			}
		}

	}
	public function checkMail($request, $response){	
		if ($_POST['mail'] != $_POST['mail2']) {
			$_SESSION['errorMail']='diff';
			return $response->withRedirect($this->router->pathFor('updateMail'));
		}
		elseif(!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)){
			$_SESSION['errorMail']='non valide';
			return $response->withRedirect($this->router->pathFor('updateMail'));
		}else{
			$user = User::find(1);/* A remplacer par l'id  dans la session*/
			$user->mail = $_POST['mail'];
			$user->save();
			return $response->withRedirect($this->router->pathFor('profil'));
		}
	}
}