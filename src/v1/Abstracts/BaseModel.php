<?php


declare(strict_types=1);

namespace SecretServer\Api\v1\Abstracts;

use SecretServer\Api\v1\Contracts\ModelInterface;
use SecretServer\Database\DatabaseConnector;

abstract class BaseModel implements ModelInterface
{

  /**
   *
   * @param DatabaseConnector $database
   * @return void
   */
  public function __construct(protected DatabaseConnector $database)
  {
  }

  /**
   *
   * @param array $payload
   * @return array
   */
  abstract public function create(array $payload): array;

  /**
   * @param string $arg
   * @return null|array
   */
  abstract public function get(string $arg): ?array;
}
