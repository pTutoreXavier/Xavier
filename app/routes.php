<?php
$app->get("/home", "HomeController:index");
$app->get("/auth/signup", "AuthController:getSignUp")->setName("auth.signup");