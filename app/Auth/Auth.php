<?php

namespace App\auth;

use App\models\User;

class Auth
{
    public function user()
    {
        return User::find($_SESSION['user']);
    }

    public function check()
    {
        return isset($_SESSION['user']);
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