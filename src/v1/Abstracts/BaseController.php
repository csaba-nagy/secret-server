<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Abstracts;

use Exception;
use SecretServer\Api\v1\Contracts\ControllerInterface;
use SecretServer\Api\v1\Http\Request;

abstract class BaseController implements ControllerInterface
{
  public function __construct(protected ?BaseRepository $repository = null)
  {
  }

  abstract public function index(): string;

  /**
   *
   * @param Request $request
   * @return string|array
   * @throws Exception
   */
  public function read(Request $request): string | array
  {
    return empty($request->getParams())
      ? $this->index()
      : $this->get($request);
  }

  public function create(Request $request): array
  {
    throw new Exception('Not implemented');
  }

  public function get(Request $request): ?array
  {
    throw new Exception('Not implemented');
  }

}
