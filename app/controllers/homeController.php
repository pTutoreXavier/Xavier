<?php
namespace App\Controllers;
use \Slim\Views\Twig as View;
use \App\Models\User as User;
class HomeController extends Controller{
	public function index($request, $response){
		$user = User::find(1);
		var_dump($user);
	}
}