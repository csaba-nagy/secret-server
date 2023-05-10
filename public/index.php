<?php

use SecretServer\Api\v1\Http\Router;
use SecretServer\Api\v1\Http\Request;

require __DIR__ . '/../vendor/autoload.php';

try {
  $router = new Router(new Request());
  dump($router->resolve());
} catch (\Throwable $th) {
  dump($th->getMessage());
}
