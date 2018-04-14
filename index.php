<?php

require_once "./vendor/autoload.php";

use Hoben\SimpleRoutes\Router;

$router = new Router();
/*
$route->add('/products', 'ProductController', 'getProducts', 'GET');
$route->add('/product', 'ProductController', 'getProduct', 'GET');
$route->add('/product', 'ProductController', 'addProduct', 'POST');
$route->add('/product', 'ProductController', 'updateProduct', 'PUT');
$route->add('/product', 'ProductController', 'deleteProduct', 'DELETE');*/
$router->add('/', 'indexController', 'index5', 'PUT');
$router->match();
