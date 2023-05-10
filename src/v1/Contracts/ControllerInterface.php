<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Contracts;

use SecretServer\Api\v1\Http\Request;

interface ControllerInterface
{
  /**
   *
   * @return string
   */
  public function index(): string;

  /**
   *
   * @param Request $request
   * @return null | array
   */
  public function get(Request $request): ?array;

  /**
   *
   * @param Request $request
   * @return array
   */
  public function create(Request $request): array;
}
