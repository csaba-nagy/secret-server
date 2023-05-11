<?php

namespace SecretServer\Api\v1\Controllers;

use SecretServer\Api\v1\Abstracts\BaseController;
use SecretServer\Api\v1\Http\{Request, Response};
use SecretServer\Api\v1\Repositories\SecretRepository;
use SecretServer\Enums\HttpStatusCode;

class SecretController extends BaseController
{
  public function __construct()
  {
    parent::__construct(new SecretRepository());
  }

  public function index(Request $request): Response
  {
    $body = 'These aren\'t the secrets you\'re looking for.';

    return new Response($request->getAcceptHeader(), HttpStatusCode::OK, $body);
  }

  public function create(Request $request): Response
  {
    $body = $this->repository->create($request->getPayload());

    return new Response($request->getAcceptHeader(), HttpStatusCode::CREATED, $body);
  }

  public function get(Request $request): Response
  {
    $body = $this->repository->get($request->getParams()['param']);
    $status = is_null($body) ? HttpStatusCode::NOT_FOUND : HttpStatusCode::OK;

    // TODO: Make a createResponse method instead
    return new Response(
      $request->getAcceptHeader(),
      $status,
      $body ?? 'NOT_FOUND'
    );
  }
}
