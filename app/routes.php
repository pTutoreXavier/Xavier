<?php
$app->get("/home", "HomeController:index");
$app->get("/auth/signup", "AuthController:getSignUp")->setName("auth.signup");
$app->get("/dictionary", "DictionaryController:index")->setName("dictionary");
$app->get("/dictionary/{id}", "DictionaryController:getById");
$app->get("/dictionary/new/{type}", "DictionaryController:new");
$app->post("/dictionary/new/{type}/send", "DictionaryController:create");