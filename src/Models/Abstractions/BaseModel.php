<?php


declare(strict_types=1);

namespace SecretServer\Models\Abstractions;

use SecretServer\Contracts\ModelInterface;
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
}
