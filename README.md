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
* Symfony/yaml >= 2.8.38

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
  action: showTest
  method: POST

"default":
  controller: defaultController
  action: show404
```
```php5
$router = new Router();
$router->setBasePath('my-app/'); // Example: for www.localhost/my-app
$router->setControllersPath('src/controllers/'); // Example: for controllers in www.localhost/my-app/src/controllers
$router->setConfigPath('yaml-file-path/routes.yaml') // The yaml file path
$router->match();
```
### Adding routes with PHP code
```php5
use Hoben\SimpleRoutes\Router;

$router = new Router();

$route->add('/products', 'ProductController', 'getProducts', 'GET');
$route->add('/product', 'ProductController', 'getProduct', 'GET');
$route->add('/product', 'ProductController', 'addProduct', 'POST');
$route->add('/product', 'ProductController', 'updateProduct', 'PUT');

$router->setBasePath('my-app/'); // Example: for www.localhost/my-app
$router->setControllersPath('src/controllers/'); // Example: for controllers in www.localhost/my-app/src/controllers
$router->match();
```

### For beginner: server configuration

Create a simple .htaccess file on your root directory if you're using Apache with mod_rewrite enabled.

```apache
Options +FollowSymLinks
RewriteEngine On
RewriteRule ^(.*)$ index.php [NC,L]
```

If you're using nginx, setup your server section as following:

```nginx
server {
	listen 80;
	server_name mydevsite.dev;
	root /var/www/mydevsite/public;

	index index.php;

	location / {
		try_files $uri $uri/ /index.php?$query_string;
	}

	location ~ \.php$ {
		fastcgi_split_path_info ^(.+\.php)(/.+)$;
		# NOTE: You should have "cgi.fix_pathinfo = 0;" in php.ini

		# With php5-fpm:
		fastcgi_pass unix:/var/run/php5-fpm.sock;
		fastcgi_index index.php;
		include fastcgi.conf;
		fastcgi_intercept_errors on;
	}
}
```

License
--------
The MIT License (MIT). Please see [License File](https://github.com/hoben/simple-routes/master/LICENSE.md) for more information.

