<?php
use App\Middleware\AuthMiddleware;
use App\Middleware\GuestMiddleware;
use App\Middleware\SearcherMiddleware;
use App\Middleware\UserMiddleware;

$app->group('', function () {
    $app->get("/dictionary", "DictionaryController:index")->setName("dictionary");
	$app->get("/dictionary/export", "DictionaryController:viewExport")->setName("dictionary.export");
	$app->get("/dictionary/export/{format}", "DictionaryController:export")->setName("dictionary.format");
	$app->get("/dictionary/new", "DictionaryController:new")->setName("dictionary.new");
	$app->get("/dictionary/{id}", "DictionaryController:getById")->setName("dictionary.details");
	$app->post("/dictionary", "DictionaryController:create")->setName("dictionary.create");
	$app->put("/dictionary/{id}", "DictionaryController:update")->setName("dictionary.edit");
	$app->delete("/dictionary/{id}", "DictionaryController:delete")->setName("dictionary.delete");
})->add(new SearcherMiddleware($container));

$app->group('', function () {

})->add(new UserMiddleware($container));

$app->get("/sequence/{idVideo}/{idSequence}","SequenceController:index");
$app->post("/commenter","SequenceController:commentaire");
$app->get("/video/{idVideo}","VideoController:index");
$app->post("/video","VideoController:createSequence");
$app->get("/object/{recherche}","VideoController:getObject");
$app->get("/method/{recherche}","VideoController:getMethod");

$app->group('', function () {
    $this->get("/auth/signup", "AuthController:getSignUp")->setName("auth.signup");
    $this->post("/auth/signup", "AuthController:postSignUp");
    $this->get("/auth/signin", "AuthController:getSignIn")->setName("auth.signin");
    $this->post("/auth/signin", "AuthController:postSignIn");
})->add(new \App\Middleware\CsrfViewMiddleware($container))
    ->add($container->csrf)
    ->add(new GuestMiddleware($container));

$app->group('', function () {
    $this->get("/auth/signout", "AuthController:getSignOut")->setName("auth.signout");

    $this->get("/profil", "ProfilController:index")->setName("profil");
    $this->get("/profil/updatePass", "ProfilController:updatePass")->setName("updatePass");
    $this->post("/profil/checkPass", "ProfilController:checkPass")->setName("checkPass");
    $this->get("/profil/updateMail", "ProfilController:updateMail")->setName("updateMail");
    $this->post("/profil/checkMail", "ProfilController:checkMail")->setName("checkMail");
    $this->get("/profil/updateProfilPicture", "ProfilController:updateProfilPicture")->setName("updateProfilPicture");
    $this->post("/profil/checkProfilPicture", "ProfilController:checkProfilPicture")->setName("checkProfilPicture");
    $this->post("/profil/checkProfilPictureUpload", "ProfilController:checkProfilPictureUpload")->setName("checkProfilPictureUpload");

})->add(new AuthMiddleware($container));

$app->get("/home[/]", "HomeController:index")->setName('home');
$app->get('/home/mentions[/]', "HomeController:mentions")->setName('home.mentions');
$app->get('/home/technos[/]', "HomeController:technos")->setName('home.technos');