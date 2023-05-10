<?php

namespace SecretServer\Api\v1\Controllers;

use SecretServer\Api\v1\Abstracts\BaseController;
use SecretServer\Api\v1\Repositories\SecretRepository;

class SecretController extends BaseController
{
  public function __construct()
  {
    parent::__construct(new SecretRepository());
  }

  /**
   *
   * @param array $payload
   * @return array
   */
  public function create(array $payload): array
  {
    return $this->repository->create($payload);
  }

  /**
   *
   * @param string $hash
   * @return null|array
   */
  public function get(string $hash): ?array
  {
    return $this->repository->get($hash);
  }
}
