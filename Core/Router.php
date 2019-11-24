<?php

namespace Core;

class Router
{

    protected $routes = [];
    protected $params = [];

    public function add($route, $params = [], $method = 'GET')
    {
        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);

        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);

        // Convert variables with custom regular expressions e.g. {id:\d+}
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);

        // Add start and end delimiters, and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[] = new Route($route, $params, $method);
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * Match the route to the routes in the routing table, setting the $params
     * property if a route is found.
     */
    public function match($url, $method)
    {
        foreach ($this->routes as $route) {
            $params = $route->getParams();
            $pattern = $route->getPattern();

            if (preg_match($pattern, $url, $matches) &&
                strtoupper($route->getMethod()) == strtoupper($method)) {
                // Get named capture group values
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function dispatch($url, $method)
    {
        $url = $this->removeQueryStringVariables($url);

        if (!$this->match($url, $method))  {
            throw new \Exception('No route matched.', 404);
        }

        $controller = $this->params['controller'];
        $controller = $this->convertToStudlyCaps($controller);
        $controller = $this->getNamespace() . $controller;

        if (!class_exists($controller)) {
            throw new \Exception("Controller class $controller not found");
        }

        $controller_object = new $controller($this->params);

        $action = $this->params['action'];
        $action = $this->convertToCamelCase($action);

        if (preg_match('/action$/i', $action) != 0) {
            throw new \Exception(
                "Method $action in controller $controller
                cannot be called directly - remove the
                Action suffix to call this method"
            );
        }

        $controller_object->$action();
    }

    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }

    protected function getNamespace()
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }
}
