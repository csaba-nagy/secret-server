<?php

use SecretServer\Api\v1\Repositories\SecretRepository;

require __DIR__ . '/../vendor/autoload.php';

try {
  $repository = new SecretRepository();
  dump($repository->get('abcde12345'));
} catch (\Throwable $th) {
  dump($th->getMessage());
}
