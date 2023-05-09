<?php

use SecretServer\Database\Database;

require __DIR__ . '/../vendor/autoload.php';

try {
  $db = Database::getConnection();

  dump($db->isConnected());
} catch (\Throwable $th) {
  dump($th->getMessage());
}
