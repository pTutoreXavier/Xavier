<?php
$app->get("/home", "HomeController:index");
$app->get("/auth/signup", "AuthController:getSignUp")->setName("auth.signup");
$app->get("/profil", "ProfilController:index");
$app->get("/profil/update", "ProfilController:update");
$app->post("/profil/update/check", "ProfilController:check");