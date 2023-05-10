<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Contracts;

interface ModelInterface
{
  public function create(array $payload): array;
}
