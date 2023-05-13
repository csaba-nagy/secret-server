<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Controllers;

use SecretServer\Api\v1\Abstracts\BaseController;
use SecretServer\Api\v1\Http\{Request, Response};
use SecretServer\Enums\HttpStatusCode;

class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request): Response
    {
        $body = 'Secret Server';

        return new Response($request->getAcceptHeader(), HttpStatusCode::OK, $body);
    }
}
