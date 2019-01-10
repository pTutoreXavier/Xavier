<?php

namespace App\Controllers\Auth;

use \App\Controllers\Controller;
use \App\Models\User;

class AuthController extends Controller{
	public function getSignUp($request, $response){
		return $this->view->render($response, "auth/signup.twig");
	}

	public function postSignUp($request, $response)
    {
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