<?php
$app->get("/home", "HomeController:index");
$app->get("/auth/signup", "AuthController:getSignUp")->setName("auth.signup");
$app->get("/sequence/{idVideo}/{idSequence}","SequenceController:index");
$app->post("/commenter","SequenceController:commentaire");
$app->get("/video/{idVideo}","VideoController:index");
$app->post("/video","VideoController:createSequence");