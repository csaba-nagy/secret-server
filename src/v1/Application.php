<?php

declare(strict_types=1);

namespace SecretServer\Api\v1;

use SecretServer\Api\v1\Http\Request;
use SecretServer\Api\v1\Http\Router;

final class Application
{
    public static function run(): void
    {
        try {
            $router = new Router(new Request());

            echo $router
                ->resolve()
                ->send();
        } catch (\Throwable $th) {
            // TODO: add logger
            echo $th->getMessage();
        }
    }
}
