<?php

require_once('../Router.php');

$router = new Router();

$router->get('/', HomeController::class, 'index');

$router->dispatch();

?>