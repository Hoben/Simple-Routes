<?php
namespace Hoben\SimpleRoutes;

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
    /**
     * Creates objet Router with the right params.
     *
     * Used for setting default locations for the base folder, the controllers directory and the yaml routes file.
     * Default locations are:
     *          $this->basePath = '';
     *          $this->controllersPath = 'controllers/';
     *          $this->configPath = './routes.yaml';
     */
    private $_routes = array();
    private $_basePath;
    private $_configPath;
    private $_controllersPath;

    public function __construct($basePath, $controllersPath, $configPath)
    {

    }
    /**
     * Sets the directory for the base folder where the app is installed
     *
     * @param string $basePath The base folder path
     */
    public function setconfigPath($configPath)
    {
        $this->configPath = $configPath;
    }

    /**
     * Sets the directory for the base folder where the app is installed
     *
     * @param string $basePath The base folder path
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * Sets the directory for the controlles path
     *
     * @param string $controllersPath The controllers folder path
     */
    public function setControllersPath($controllersPath)
    {
        $this->controllersPath = $controllersPath;
    }

    /**
     * Sets the directory for the controlles path
     *
     * @return string The URL visited with replacing the base folder of your app
     * to match with the routes defined in your yaml file.
     */
    private function getCurrentRequest()
    {
        $requestMethod = (isset($_POST['_method'])
            && ($_method = strtoupper($_POST['_method']))
            && in_array($_method, array('PUT', 'DELETE')))
        ? $_method : $_SERVER['REQUEST_METHOD'];

        $requestUrl = $_SERVER['REQUEST_URI'];

        // strip GET variables from URL
        if (($pos = strpos($requestUrl, '?')) !== false) {
            $requestUrl = substr($requestUrl, 0, $pos);
        }
        return $this->match($requestUrl, $requestMethod);
    }

    /**
     * Match Method
     *
     * Matches the configuration saved in the yaml file with the current url and the right method
     * If no match was found and the route 'default' is defined, the controller responsable for the 'default'
     * route is called.
     * Example for default declaration in yaml routes.yaml
     * "default":
     *      controller: 404Controller
     *      action: index
     * this will call (new 404Controller())->index()
     */
    public function add($url, $controller, $action, $method)
    {
        $route = array('url' => $url,
            'controller' => $controller,
            'action' => $action,
            'method' => strtoupper($method));
        array_push($this->yamlRoutes, $route);
    }
    /**
     * Match Method
     *
     * Matches the configuration saved in the yaml file with the current url and the right method
     * If no match was found and the route 'default' is defined, the controller responsable for the 'default'
     * route is called.
     * Example for default declaration in yaml routes.yaml
     * "default":
     *      controller: 404Controller
     *      action: index
     * this will call (new 404Controller())->index()
     */
    public function match()
    {
        $this->getCurrentRequest();
    }

    private function matchRequest($requestUrl, $requestMethod)
    {
        $routes = $this->yamlRoutes;
        if (!file_exists($this->configPath)) {
            return false;
        }
        foreach ($routes as $yamlRoute) {
            if (!isset($yamlRoute['url'])) {
                continue;
            }
            if (!isset($yamlRoute['controller']) || !isset($yamlRoute['action'])) {
                continue;
            }
            if ($yamlRoute['url'] != $requestUrl) {
                continue;
            }
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
}
