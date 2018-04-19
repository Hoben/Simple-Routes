<?php
namespace Hoben\SimpleRoutes;

class Route
{
    private $_url;
    private $_isStatic;
    private $_static;
    private $_controller;
    private $_action;
    private $_method;

    /**
     * Get the value of _url
     */
    public function get_url()
    {
        return $this->_url;
    }

    /**
     * Set the value of _url
     *
     * @return  self
     */
    public function set_url($_url)
    {
        $this->_url = $_url;

        return $this;
    }

    /**
     * Get the value of _isStatic
     */
    public function get_isStatic()
    {
        return $this->_isStatic;
    }

    /**
     * Set the value of _isStatic
     *
     * @return  self
     */
    public function set_isStatic($_isStatic)
    {
        $this->_isStatic = $_isStatic;

        return $this;
    }

    /**
     * Get the value of _controller
     */
    public function get_controller()
    {
        return $this->_controller;
    }

    /**
     * Set the value of _controller
     *
     * @return  self
     */
    public function set_controller($_controller)
    {
        $this->_controller = $_controller;

        return $this;
    }

    /**
     * Get the value of _action
     */
    public function get_action()
    {
        return $this->_action;
    }

    /**
     * Set the value of _action
     *
     * @return  self
     */
    public function set_action($_action)
    {
        $this->_action = $_action;

        return $this;
    }

    /**
     * Get the value of _method
     */
    public function get_method()
    {
        return $this->_method;
    }

    /**
     * Set the value of _method
     *
     * @return  self
     */
    public function set_method($_method)
    {
        $this->_method = $_method;

        return $this;
    }

    /**
     * Get the value of _static
     */
    public function get_static()
    {
        return $this->_static;
    }

    /**
     * Set the value of _static
     *
     * @return  self
     */
    public function set_static($_static)
    {
        $this->_static = $_static;

        return $this;
    }

    public function __construct($url, $controller, $action, $method, $static, $isStatic)
    {
        if ($isStatic) {
            $this->_isStatic = $isStatic;
            $this->_url = $url;
            $this->_static = $static;
        } else {
            $this->_isStatic = !$isStatic;
            $this->_url = $url;
            $this->_controller = $controller;
            $this->_action = $action;
            $this->_url = $url;
            $this->_method = $method;
        }
    }

    public function getRegex()
    {
        return preg_replace_callback("/:(\w+)/", array(&$this, 'substituteFilter'), $this->_url);
    }
    private function substituteFilter($matches)
    {
        if (isset($matches[1]) && isset($this->_filters[$matches[1]])) {
            return $this->_filters[$matches[1]];
        }
        return "([\w-]+)";
    }

    public static function validateYamlRoute($yamlRoute, $basePath, $controllersPath)
    {
        if (!isset($yamlRoute['url'])) {
            return false;
        }

        if (!isset($yamlRoute['controller'])
            && !isset($yamlRoute['static'])) {
            return false;
        }

        if (isset($yamlRoute['controller'])
            && isset($yamlRoute['static'])) {
            return false;
        }

        if (isset($yamlRoute['controller'])
            && !isset($yamlRoute['action'])) {
            return false;
        }

        if (isset($yamlRoute['method'])
            && (!in_array(strtoupper($yamlRoute['method']),
                array('GET', 'POST', 'PUT', 'DELETE')))) {
            return false;
        }

        if (substr($yamlRoute['url'], -1) !== '/') {
            $yamlRoute['url'] .= '/';
        }

        if (!isset($yamlRoute['static'])) {
            $isStatic = false;
            $yamlRoute['static'] = '';
        } else {
            $isStatic = true;
        }

        if (!isset($yamlRoute['method'])) {
            $yamlRoute['method'] = '';
        }

        return new Route(
            $basePath . $yamlRoute['url'],
            $basePath . $controllersPath . $yamlRoute['controller'],
            $yamlRoute['action'],
            $yamlRoute['method'],
            $basePath . $yamlRoute['static'],
            $isStatic
        );

    }

}
