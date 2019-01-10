<?php
$app->get("/home", "HomeController:index");
$app->get("/auth/signup", "AuthController:getSignUp")->setName("auth.signup");
$app->get("/dictionary", "DictionaryController:index");
$app->get("/dictionary/{id}", "DictionaryController:getById");