<?php

namespace SecretServer\Api\v1\Controllers;

use SecretServer\Api\v1\Abstracts\BaseController;
use SecretServer\Api\v1\Http\Request;
use SecretServer\Api\v1\Repositories\SecretRepository;

class SecretController extends BaseController
{
  public function __construct()
  {
    parent::__construct(new SecretRepository());
  }

  public function index(): string
  {
    return 'These aren\'t the secrets you\'re looking for.';
  }

  public function create(Request $request): array
  {
    return $this->repository->create($request->getPayload());
  }

  public function get(Request $request): ?array
  {
    return $this->repository->get($request->getParams()['param']);
  }
}
