<?php

namespace App\Controllers\Auth;

use \App\Controllers\Controller;
use \App\Models\User;
use \Respect\Validation\Validator as v;

class AuthController extends Controller{

    public function getSignUp($request, $response){
		return $this->view->render($response, "auth/signup.twig");
	}

	public function postSignUp($request, $response)
    {
        $validation = $this->validator->validate($request, [
            'nom' => v::notEmpty()->alpha(),
            'prenom' => v::noWhitespace()->notEmpty()->alpha(),
            'mail' => v::noWhitespace()->notEmpty()->email()->mailAvailable(),
            'mdp' => v::noWhitespace()->notEmpty(),
            'mdpConf' => v::equals($_POST['mdp']),
        ]);

        if($validation->failed()){
            return $response->withRedirect($this->router->pathFor('auth.signup'));
        }

        $user = User::create([
            'nom' => $request->getParam('nom'),
            'prenom' => $request->getParam('prenom'),
            'mail' => $request->getParam('mail'),
            'mdp' => password_hash($request->getParam('mdp'),PASSWORD_DEFAULT),
            'level' => 500,
        ]);

        return $response->withRedirect($this->router->pathFor('home'));
	}
}