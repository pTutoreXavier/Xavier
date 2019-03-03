<?php

namespace App\auth;

use App\models\User;

class Auth
{

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


}