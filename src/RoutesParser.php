<?php
namespace Hoben\SimpleRoutes;

use Symfony\Component\Yaml\Yaml;

class RoutesParser
{
    public static function loadFromFile($yamlFile)
    {
        try {
            $yamlRoutes = Yaml::parse(file_get_contents($yamlFile));
        } catch (\Exception $e) {
            echo 'Message %s' . $e->getMessage();
        }
        Config::parseRoutes($yamlRoutes);
    }

    private static function parseRoutes($yamlRoutes)
    {
        $routes = arary();
        foreach ($yamlRoute as $yamlRoutes) {
            $route = Config::validateRoute($yamlRoute);
            if ($route != false) {
                array_push($routes, $route);
            }
        }
        return $routes;
    }

    private static function validateRoute($yamlRoute)
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

    }
}
