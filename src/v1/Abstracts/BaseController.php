<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Abstracts;

abstract class BaseController
{
  public function __construct(protected BaseRepository $repository)
  {
  }

  /**
   *
   * @param array $payload
   * @return array
   */
  abstract public function create(array $payload): array;

  /**
   *
   * @param string $arg
   * @return null|array
   */
  abstract public function get(string $arg): ?array;
}
