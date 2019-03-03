<?php
$app->get("/home", "HomeController:index")->setName('home');

//Creation de compte
$app->get("/auth/signup", "AuthController:getSignUp")->setName("auth.signup");
$app->post("/auth/signup", "AuthController:postSignUp");
//Connection au compte
$app->get("/auth/signin", "AuthController:getSignIn")->setName("auth.signin");
$app->post("/auth/signin", "AuthController:postSignIn");
//Deconnection du compte
$app->get("/auth/signout", "AuthController:getSignOut")->setName("auth.signout");