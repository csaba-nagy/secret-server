<?php

use SecretServer\Models\SecretModel;
use Ramsey\Uuid\Uuid;

require __DIR__ . '/../vendor/autoload.php';

try {
  $model = new SecretModel();

  // Uuid::uuid4() returns with a 36 characters long string.
  // I think for this task the 10 characters long hash is enough,
  // and it takes up less space in the database
  dump($model->create([
    'hash' => substr(Uuid::uuid4()->toString(), 0, 10),
    'secret' => 'secret text',
    'expiresAfter' => 1,
    'expireAfterViews' => 1
  ]));

  // dump($model->getByHash(''));
} catch (\Throwable $th) {
  dump($th->getMessage());
}
