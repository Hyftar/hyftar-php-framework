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

        header("Content-Type: $contentType");

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            header("Content-Length: " . filesize($file));
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

        header("Content-Type: $contentType");

        $output = $twig->render($template, $args);

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            header("Content-Length: " . strlen($output));
            return;
        }

        echo $output;
    }
}
