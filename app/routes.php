<?php

use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;

$app->get("/dictionary", "DictionaryController:index")->setName("dictionary");
$app->get("/dictionary/export", "DictionaryController:viewExport")->setName("dictionary.export");
$app->get("/dictionary/new", "DictionaryController:new")->setName("dictionary.new");
$app->get("/dictionary/{id}", "DictionaryController:getById")->setName("dictionary.details");
$app->post("/dictionary", "DictionaryController:create")->setName("dictionary.create");

$app->get("/profil", "ProfilController:index")->setName("profil");
$app->get("/profil/updatePass", "ProfilController:updatePass")->setName("updatePass");
$app->post("/profil/checkPass", "ProfilController:checkPass")->setName("checkPass");
$app->get("/profil/updateMail", "ProfilController:updateMail")->setName("updateMail");
$app->post("/profil/checkMail", "ProfilController:checkMail")->setName("checkMail");
$app->get("/profil/updateProfilPicture", "ProfilController:updateProfilPicture")->setName("updateProfilPicture");
$app->post("/profil/checkProfilPicture", "ProfilController:checkProfilPicture")->setName("checkProfilPicture");
$app->post("/profil/checkProfilPictureUpload", "ProfilController:checkProfilPictureUpload")->setName("checkProfilPictureUpload");

$app->get("/sequence/{idVideo}/{idSequence}","SequenceController:index");
$app->post("/commenter","SequenceController:commentaire");
$app->get("/video/{idVideo}","VideoController:index");
$app->post("/video","VideoController:createSequence");
$app->get("/object/{recherche}","VideoController:getObject");
$app->get("/method/{recherche}","VideoController:getMethod");

$app->get("/home", "HomeController:index")->setName('home');

//Creation de compte
$app->group('', function () {
    //Creation de compte
    $this->get("/auth/signup", "AuthController:getSignUp")->setName("auth.signup");
    $this->post("/auth/signup", "AuthController:postSignUp");

    //Connection au compte
    $this->get("/auth/signin", "AuthController:getSignIn")->setName("auth.signin");
    $this->post("/auth/signin", "AuthController:postSignIn");
})->add(new \App\Middleware\CsrfViewMiddleware($container))
    ->add($container->csrf)
    ->add(new GuestMiddleware($container));

$app->group('', function () {
    //Deconnection du compte
    $this->get("/auth/signout", "AuthController:getSignOut")->setName("auth.signout");
})->add(new AuthMiddleware($container));