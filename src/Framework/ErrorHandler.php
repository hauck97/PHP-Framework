<?php

declare(strict_types=1);

namespace Framework;

use ErrorException;
use Framework\Exceptions\PageNotFoundException;
use Throwable;

class ErrorHandler
{
    public static function handleError
    (
            int $errorNumber,
            string $errorString,
            string $errorFile,
            int $errorLine
      ): bool
    {

        throw new ErrorException($errorString, 0, $errorNumber, $errorFile, $errorLine);

    }

    public static function handleException ( Throwable $exception):void
    {
        if($exception instanceof PageNotFoundException) {

            http_response_code(404);
            $errorTemplate = "404.php";

        } else {

            http_response_code(500);
            $errorTemplate = "500.php";
        }

        if ($_ENV['SHOW_ERRORS']) {

            ini_set("display_errors", "on");

        } else {

            ini_set("display_errors", "off");
            require "./../views/{$errorTemplate}";

        }

        throw $exception;

    }
}