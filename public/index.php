<?php

use SecretServer\Api\v1\Controllers\SecretController;

require __DIR__ . '/../vendor/autoload.php';

try {
  $controller = new SecretController();
  dump($controller->get('abcde12345'));
} catch (\Throwable $th) {
  dump($th->getMessage());
}
