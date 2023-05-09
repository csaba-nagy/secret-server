<?php

declare(strict_types=1);

namespace SecretServer\Contracts;

interface ModelInterface
{
  public function create(array $payload): array;
}
