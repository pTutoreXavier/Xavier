<?php
$app->get("/home", "HomeController:index");
$app->get("/auth/signup", "AuthController:getSignUp")->setName("auth.signup");
$app->get("/profil", "ProfilController:index")->setName("profil");
$app->get("/profil/update", "ProfilController:update");
$app->get("/profil/updateMail", "ProfilController:updateMail");
$app->get("/profil/updatePass", "ProfilController:updatePass")->setName("profilUpdatePass");
$app->post("/profil/updatePass/check", "ProfilController:check");