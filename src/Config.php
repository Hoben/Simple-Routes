<?php
namespace Hoben\SimpleRoutes;

use Symfony\Component\Yaml\Yaml;

class Config
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
        return $routes;
    }
}
