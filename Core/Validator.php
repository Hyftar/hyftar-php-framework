<?php

namespace Core;

class Validator
{
    protected $validation_functions = [];
    protected $on_invalid_callbacks = [];
    protected $on_valid_callbacks = [];

    public function __construct(...$validation_functions)
    {
        foreach ($validation_functions as $function)
        {
            $this->validation_functions[] = $function;
        }
    }

    public function addValidationFunction(callable $validation_function)
    {
        $this->validation_functions[] = $validation_function;
        return $this;
    }

    public function addOnValidCallback(callable $on_valid)
    {
        $this->on_valid_callbacks[] = $on_valid;
        return $this;
    }

    public function addOnInvalidCallback(callable $on_invalid)
    {
        $this->on_invalid_callbacks[] = $on_invalid;
        return $this;
    }

    public function validate(...$arguments)
    {
        foreach ($this->validation_functions as $function)
        {
            if (call_user_func_array($function, $arguments))
                continue;

            foreach ($this->on_invalid_callbacks as $callback)
            {
                call_user_func_array($callback, $arguments);
            }

            return false;
        }

        foreach ($this->on_valid_callbacks as $callback)
        {
            call_user_func_array($callback, $arguments);
        }

        return true;
    }
}
