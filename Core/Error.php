<?php

namespace Core;

class Error
{
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 0) {  // to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    public static function exceptionHandler($exception)
    {
        static $supported_codes = [404];

        // If error code isn't supported by your app,
        // convert it into 500 (generic server error)
        $code = $exception->getCode();

        if (!in_array($code, $supported_codes)) {
            $code = 500;
        }

        http_response_code($code);

        if (\App\Config::SHOW_ERRORS) {
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
            return;
        }

        $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.log';
        ini_set('error_log', $log);

        $message = "Uncaught exception: '" . get_class($exception) . "'";
        $message .= " with message '" . $exception->getMessage() . "'";
        $message .= "\nStack trace: " . $exception->getTraceAsString();
        $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();

        error_log($message, \App\Config::LOG_TO_FILE ? 0 : 4);

        View::renderTemplate("$code.html.twig");
    }
}
