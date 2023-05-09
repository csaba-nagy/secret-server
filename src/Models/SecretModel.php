<?php

declare(strict_types=1);

namespace SecretServer\Models;

use SecretServer\Database\Database;
use SecretServer\Models\Abstractions\BaseModel;

class SecretModel extends BaseModel
{
  public function __construct()
  {
    parent::__construct(Database::getConnection());
  }

  public function create(array $payload): array
  {
    return [];
  }

  public function getByHash(string $hash): ?array
  {
    return null;
  }
}
