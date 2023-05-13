<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Controllers;

use SecretServer\Api\v1\Abstracts\BaseController;
use SecretServer\Api\v1\Http\{Request, Response};
use SecretServer\Enums\HttpStatusCode;

class ErrorController extends BaseController
{
    public function index(Request $request): Response
    {
        $body = 'NOT_FOUND';

        return new Response($request->getAcceptHeader(), HttpStatusCode::NOT_FOUND, $body);
    }
}
