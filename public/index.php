<?php

use SecretServer\Api\v1\Http\Router;
use SecretServer\Api\v1\Http\Request;

require __DIR__ . '/../vendor/autoload.php';

try {
  $request = new Request();
  $router = new Router($request);
  dump($router
    ->resolve()
    ->send());
} catch (\Throwable $th) {
  dump($th->getMessage());
}
