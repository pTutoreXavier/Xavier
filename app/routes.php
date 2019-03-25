<?php
$app->get("/dictionary", "DictionaryController:index")->setName("dictionary");
$app->get("/dictionary/export", "DictionaryController:viewExport");
$app->get("/dictionary/export/{format}", "DictionaryController:export");
$app->get("/dictionary/{id}[/]", "DictionaryController:getById");
$app->get("/dictionary/new/{type}", "DictionaryController:new");
$app->post("/dictionary/new/{type}", "DictionaryController:create");

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

$app->get("/home", "HomeController:index")->setName('home');

//Creation de compte
$app->group('', function () {
    //Creation de compte
    $this->get("/auth/signup", "AuthController:getSignUp")->setName("auth.signup");
    $this->post("/auth/signup", "AuthController:postSignUp");

    //Connection au compte
    $this->get("/auth/signin", "AuthController:getSignIn")->setName("auth.signin");
    $this->post("/auth/signin", "AuthController:postSignIn");
})->add(new CsrfViewMiddleware($container))
    ->add($container->csrf)
    ->add(new GuestMiddleware($container));

$app->group('', function () {
    //Deconnection du compte
    $this->get("/auth/signout", "AuthController:getSignOut")->setName("auth.signout");
})->add(new AuthMiddleware($container));