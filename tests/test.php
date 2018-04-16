<?php

namespace Hoben\SimpleRoutes;

require_once '../vendor/autoload.php';

use Hoben\SimpleRoutes\Router;

$router = new Router();
$router->setBasePath('simple-routes/tests');
$router->setControllersPath('controllers/');
$router->match();
