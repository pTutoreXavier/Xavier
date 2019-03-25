<?php
$app->get("/home", "HomeController:index");
$app->get("/dictionary", "DictionaryController:index")->setName("dictionary");
$app->get("/dictionary/export", "DictionaryController:viewExport");
$app->get("/dictionary/export/{format}", "DictionaryController:export");
$app->get("/dictionary/{id}[/]", "DictionaryController:getById");
$app->get("/dictionary/new/{type}", "DictionaryController:new");
$app->post("/dictionary/new/{type}", "DictionaryController:create");