<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Contracts;

interface ValidatorInterface
{
  /**
   *
   * @return array|bool
   */
  public function validate(): array | bool;
}
