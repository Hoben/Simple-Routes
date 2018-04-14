Simples PHP Routes
=========================
[![Author](https://img.shields.io/badge/author-hoben-blue.svg)](https://github.com/Hoben)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D5.4-green.svg)](https://github.com/Hoben)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
Simple and easy-to-use router built for beginners and light-weight projects.

Install
--------
Via Composer
``` bash
$ composer require hoben/simple-routes
```

Requirements
--------
* PHP >= 5.4

Features
--------

* Create routes defined in yaml files
* You can also add routes in code with the function add of the class Router.php
* You can filter routes by methods ( GET, POST, PUT ,DELETE,...)

### Adding routes with yaml file (routes.yaml)
```yaml
"Dashboard":
  url: /
  controller: indexController
  action: index
  method: GET

"Test Page":
  url: /
  controller: indexController
  action: index2
  method: POST

"default":
  controller: indexController
  action: index3
```
```php
$router = new Router();
$router->setbasePath('my-app/'); // Example: for www.localhost/my-app
$router->controllersPath('src/controllers/'); // Example: for controllers in www.localhost/my-app/src/controllers
$this->configPath('yaml-file-path/routes.yaml') // The yaml file path
$router->match();
```
### Adding routes with PHP code
```php
use Hoben\SimpleRoutes\Router;

$router = new Router();

$route->add('/products', 'ProductController', 'getProducts', 'GET');
$route->add('/product', 'ProductController', 'getProduct', 'GET');
$route->add('/product', 'ProductController', 'addProduct', 'POST');
$route->add('/product', 'ProductController', 'updateProduct', 'PUT');

$router->setbasePath('my-app/'); // Example: for www.localhost/my-app
$router->controllersPath('src/controllers/'); // Example: for controllers in www.localhost/my-app/src/controllers
$router->match();
```

License
--------
The MIT License (MIT). Please see [License File](https://github.com/hoben/simple-routes/master/LICENSE.md) for more information.

