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
        $this->matchRequest(Router::getRequestURL,
            Router::getRequestMethod);
    }

    private function matchRequest($requestUrl, $requestMethod)
    {
        $routes = $this->routes;
        foreach ($routes as $yamlRoute) {
            if (!isset($yamlRoute['method'])) {
                include_once $this->controllersPath . $yamlRoute['controller'] . '.php';
                $controller = new $yamlRoute['controller']();
                $controller->{$yamlRoute['action']}();
                return true;
            } elseif (($yamlRoute['method'] == $requestMethod)) {
                include_once $this->controllersPath . $yamlRoute['controller'] . '.php';
                $controller = new $yamlRoute['controller']();
                $controller->{$yamlRoute['action']}();
                return true;
            }
        }
        if (!isset($routes['default'])) {
            return false;
        }

        if (isset($routes['default']['controller']) && isset($routes['default']['action'])) {
            include_once $this->controllersPath . $yamlRoute['controller'] . '.php';
            $controller = new $yamlRoute['controller']();
            $controller->{$yamlRoute['action']}();
        }
    }

    public static function loadFromFile($yamlFile)
    {
        try {
            $yamlRoutes = Yaml::parse(file_get_contents($yamlFile));
        } catch (\Exception $e) {
            echo 'Message %s' . $e->getMessage();
        }
        Route::parseYamlRoutes($yamlRoutes);
    }

    private function parseYamlRoutes($yamlRoutes)
    {
        $basePath = (!isset($yamlRoutes['basePath'])) ? '' : $yamlRoutes['basePath'];
        $controllersPath = (!isset($yamlRoutes['controllersPath'])) ? 'controllers' : $yamlRoutes['controllersPath'];
        foreach ($yamlRoute as $yamlRoutes) {
            $route = Router::validateYamlRoute($yamlRoute, $basePath, $controllersPath);
            if ($route != false) {
                array_push($this->routes, $route);
            }
        }
        return $routes;
    }
}
