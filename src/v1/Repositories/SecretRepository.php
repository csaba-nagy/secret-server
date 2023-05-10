<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Repositories;

use SecretServer\Api\v1\Abstracts\BaseRepository;
use SecretServer\Api\v1\Models\SecretModel;

class SecretRepository extends BaseRepository
{
  /**
   * @return void
   */
  public function __construct()
  {
    parent::__construct(new SecretModel());
  }

  /**
   * @param array $payload
   * @return array
   */
  public function create(array $payload): array
  {
    return $this->model->create($payload);
  }

  /**
   * @param string $hash
   * @return null|array
   */
  public function get(string $hash): ?array
  {
    return $this->model->get($hash);
  }
}
