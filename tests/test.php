<?php

namespace Hoben\SimpleRoutes;

require_once '../vendor/autoload.php';

use Hoben\SimpleRoutes\Router;

$router = new Router();
$router->loadFromFile('routes.yml');
$router->match();
