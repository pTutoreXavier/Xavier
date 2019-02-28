<?php
$app->get("/home", "HomeController:index");
$app->get("/auth/signup", "AuthController:getSignUp")->setName("auth.signup");
$app->get("/profil", "ProfilController:index")->setName("profil");
$app->get("/profil/updatePass", "ProfilController:updatePass")->setName("updatePass");
$app->post("/profil/checkPass", "ProfilController:checkPass")->setName("checkPass");
$app->get("/profil/updateMail", "ProfilController:updateMail")->setName("updateMail");
$app->post("/profil/checkMail", "ProfilController:checkMail")->setName("checkMail");