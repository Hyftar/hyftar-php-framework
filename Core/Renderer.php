<?php

namespace Core;

interface iRenderer
{
    public function render($view, $args = [], $contentType = 'text/html');
    public function renderTemplate($template, $args = [], $contentType = 'text/html');
    public function renderJSON($json);
}

class Renderer implements iRenderer
{
    /**
     * Render a view file (.php or .html)
     */
    public function render($view, $args = [], $contentType = 'text/html')
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (!is_readable($file)) {
            throw new \Exception("$file not found");
        }

        header("Content-Type: $contentType");
        header("Content-Length: " . filesize($file));

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            return;
        }

        require $file;
    }

    /**
     * Render a view template using Twig
     */
    public function renderTemplate($template, $args = [], $contentType = 'text/html')
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

    public function renderJSON($json)
    {
        $output = json_encode($json, JSON_UNESCAPED_UNICODE);
        header("Content-Type: application/json");
        header("Content-Length: " . strlen($output));

        if ($_SERVER['REQUEST_METHOD'] == 'HEAD') {
            return;
        }

        echo $output;
    }
}
