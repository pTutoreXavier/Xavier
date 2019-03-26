<?php

namespace App\auth;

use App\models\User;

class Auth
{
    public function user()
    {
        if(isset($_SESSION['user']))
        return User::find($_SESSION['user']);
    }

    public function check()
    {
        if(isset($_SESSION['user']))
        return isset($_SESSION['user']);
    }

    public function checkLevelUser(){
        $user = User::select('level')->where('id','=',$_SESSION['user']);
        if($user->level === 500){
            return true;
        }else{
            return false;
        }
    }
    public function checkLevelSearcher(){
        $user = User::select('level')->where('id','=',$_SESSION['user']);
        if($user->level === 779){
            return true;
        }else{
            return false;
        }
    }

    public function attempt($mail, $mdp)
    {

        $user = User::where('mail',$mail)->first();
        if(!$user) {
            return false;
        }
        if (password_verify($mdp, $user->mdp)) {
            $_SESSION['user'] = $user->id;
            return true;
        }
        return false;

    }

    public function logout ()
    {
        unset($_SESSION['user']);
    }


}