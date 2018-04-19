<?php
namespace Hoben\SimpleRoutes;

use Hoben\SimpleRoutes\Route;
use Symfony\Component\Yaml\Yaml;

/**
 *  Router class
 *
 *  Class used for:
 *  - Reading default locations for the base folder, the controllers directory and the yaml routes file.
 *  - Setting routes for all defined pages
 *  - Has two private methods getURL and getMethod to match with the routes defined by user.
 *
 * @author Hobben <hobben@gmail.com>
 */
class Router
{
    private $routes;

    public function __construct()
    {
        $this->routes = array();
    }

    private static function getRequestURL()
    {
        $requestUrl = $_SERVER['REQUEST_URI'];

        // strip GET variables from URL
        if (($pos = strpos($requestUrl, '?')) !== false) {
            $requestUrl = substr($requestUrl, 0, $pos);
        }
        return $requestUrl;
    }

    private static function getRequestMethod()
    {
        $requestMethod = (isset($_POST['_method'])
            && ($_method = strtoupper($_POST['_method']))
            && in_array($_method, array('PUT', 'DELETE')))
        ? $_method : $_SERVER['REQUEST_METHOD'];

        return $requestMethod;
    }

    public function match()
    {
        $this->matchRequest(Router::getRequestURL(),
            Router::getRequestMethod());
    }

    private function matchRequest($requestUrl, $requestMethod)
    {
        $routes = $this->routes;
        foreach ($routes as $route) {
            if ($route->get_url() == 'any') {
                $defaultRoute = $route;
                continue;
            }
            if (($route->get_url() == $requestUrl) && ($route->get_method() == $requestMethod)) {
                $this->matchRoute($route);
            }

        }
        if (!isset($defaultRoute)) {
            return false;
        }
        $this->matchRoute($defaultRoute);
    }

    private function matchRoute($route)
    {
        var_dump($route);
        if ($route->get_isStatic()) {
            include $route->get_static() . '.php';
        } else {
            $controller_name = $route->get_controller();
            $controller = new $controller_name();
            $controller->{$route->get_action()}();
        }
    }

    public function loadFromFolder($yamlFolder)
    {
        try {
            if (is_dir($yamlFolder)) {
                foreach (glob("*.yml") as $yamlFile) {
                    $yamlRoutes = Yaml::parse(file_get_contents($yamlFile));
                    Route::parseYamlRoutes($yamlRoutes);
                }
            }
        } catch (\Exception $e) {
            echo 'Message %s' . $e->getMessage();
        }
    }

    public function loadFromFile($yamlFile)
    {
        try {
            if (file_exists($yamlFile)) {
                $yamlRoutes = Yaml::parse(file_get_contents($yamlFile));
                Router::parseYamlRoutes($yamlRoutes);
            }
        } catch (\Exception $e) {
            echo 'Message %s' . $e->getMessage();
        }

    }

    private function parseYamlRoutes($yamlRoutes)
    {
        $basePath = (!isset($yamlRoutes['basePath'])) ? '' : $yamlRoutes['basePath'];
        $controllersPath = (!isset($yamlRoutes['controllersPath'])) ? 'controllers' : $yamlRoutes['controllersPath'];
        foreach ($yamlRoutes as $yamlRoute) {
            $route = Route::validateYamlRoute($yamlRoute, $basePath, $controllersPath);
            if ($route != false) {
                array_push($this->routes, $route);
            }
        }
        var_dump($this->routes);
        return $route;
    }
}
