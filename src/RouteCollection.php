<?php
namespace Hoben\SimpleRoutes;

class RouteCollection extends \SplObjectStorage
{
    /**
     * Fetch all routers stored on this collection of router
     *
     * @return array
     */
    public function all()
    {
        $_array = array();
        foreach ($this as $object) {
            $_array[] = $object;
        }
        return $_array;
    }
}
