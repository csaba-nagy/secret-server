<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Abstracts;

use Exception;
use SecretServer\Api\v1\Contracts\ControllerInterface;
use SecretServer\Api\v1\Http\{Request, Response};

abstract class BaseController implements ControllerInterface
{
  public function __construct(protected ?BaseRepository $repository = null)
  {
  }

  abstract public function index(Request $request): Response;

  /**
   *
   * @param Request $request
   * @return Response
   * @throws Exception
   */
  public function read(Request $request): Response
  {
    return empty($request->getParams()['param'])
      ? $this->index($request)
      : $this->get($request);
  }

  // TODO: Find a better way to handle false post requests than redirect
  public function create(Request $request): Response
  {
    return $this->index($request);
  }

  /**
   *
   * @param Request $request
   * @return Response
   * @throws Exception
   */
  public function get(Request $request): Response
  {
    throw new Exception('Not implemented');
  }

}
