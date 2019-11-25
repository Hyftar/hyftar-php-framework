<?php

namespace Core;

abstract class Controller
{
    /**
     * Parameters from the matched route
     */
    protected $route_params = [];

    public function __construct($route_params)
    {
        $this->route_params = $route_params;
    }

    /**
     * Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action';

        if (!method_exists($this, $method)) {
            throw new \Exception(
                "Method $method not found in controller " . get_class($this)
            );
        }

        if ($this->before() === false)
            return;

        call_user_func_array([$this, $method], $args);
        $this->after();
    }

    /**
     * Before filter - called before an action method.
     */
    protected function before()
    {
    }

    /**
     * After filter - called after an action method.
     */
    protected function after()
    {
    }
}
