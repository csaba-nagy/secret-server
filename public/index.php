<?php

use SecretServer\Api\v1\Http\Request;

require __DIR__ . '/../vendor/autoload.php';

try {
  $request = new Request();
  dump($request);
} catch (\Throwable $th) {
  dump($th->getMessage());
}
