<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Abstracts;

use Exception;
use SecretServer\Api\v1\Contracts\ControllerInterface;
use SecretServer\Api\v1\Http\Request;

abstract class BaseController implements ControllerInterface
{
  public function __construct(protected BaseRepository $repository)
  {
  }

  abstract public function index(): string;

  public function create(Request $request): array
  {
    throw new Exception('Not implemented');
  }

  public function get(Request $request): ?array
  {
    throw new Exception('Not implemented');
  }

}
