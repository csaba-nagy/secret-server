<?php

use SecretServer\Api\v1\Models\SecretModel;
use SecretServer\Database\Database;

require __DIR__ . '/../vendor/autoload.php';

try {
  $model = new SecretModel();
  dump($model->get('abcde12345'));
} catch (\Throwable $th) {
  dump($th->getMessage());
}
