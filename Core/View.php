<?php

namespace Core;

class View
{

    /**
     * Render a view file (.php or .html)
     */
    public static function render($view, $contentType = 'text/html', $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        header("Content-Type: $contentType");
        header("Content-Length: " . filesize($file));

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            return;
        }

        if (!is_readable($file)) {
            throw new \Exception("$file not found");
        }

        require $file;
    }

    /**
     * Render a view template using Twig
     */
    public static function renderTemplate($template, $contentType = 'text/html', $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig_Environment($loader);
        }

        $output = $twig->render($template, $args);

        header("Content-Type: $contentType");
        header("Content-Length: " . strlen($output));

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            return;
        }

        echo $output;
    }

    public static function renderJSON($json)
    {
        $output = json_encode($json);
        header("Content-Type: application/json");
        header("Content-Length: " . strlen($output));

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            return;
        }

        echo $output;
    }
}
