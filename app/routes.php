<?php
$app->get("/home", "HomeController:index");

$app->get("/dictionary", "DictionaryController:index")->setName("dictionary");
$app->get("/dictionary/export", "DictionaryController:viewExport");
$app->get("/dictionary/export/{format}", "DictionaryController:export");
$app->get("/dictionary/{id}[/]", "DictionaryController:getById");
$app->get("/dictionary/new/{type}", "DictionaryController:new");
$app->post("/dictionary/new/{type}", "DictionaryController:create");

$app->get("/auth/signup", "AuthController:getSignUp")->setName("auth.signup");
$app->get("/profil", "ProfilController:index")->setName("profil");
$app->get("/profil/updatePass", "ProfilController:updatePass")->setName("updatePass");
$app->post("/profil/checkPass", "ProfilController:checkPass")->setName("checkPass");
$app->get("/profil/updateMail", "ProfilController:updateMail")->setName("updateMail");
$app->post("/profil/checkMail", "ProfilController:checkMail")->setName("checkMail");
$app->get("/profil/updateProfilPicture", "ProfilController:updateProfilPicture")->setName("updateProfilPicture");
$app->post("/profil/checkProfilPicture", "ProfilController:checkProfilPicture")->setName("checkProfilPicture");
$app->post("/profil/checkProfilPictureUpload", "ProfilController:checkProfilPictureUpload")->setName("checkProfilPictureUpload");