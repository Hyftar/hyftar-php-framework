<?php

namespace Core;

class Route
{
    protected $params = [];
    protected $pattern = '';
    protected $method = '';

    public function __construct($pattern, $params, $method)
    {
        $this->pattern = $pattern;
        $this->params = $params;
        $this->method = $method;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function getMethod()
    {
        return $this->method;
    }
}
