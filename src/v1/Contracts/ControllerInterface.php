<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Contracts;

use SecretServer\Api\v1\Http\{Request, Response};

interface ControllerInterface
{
    /**
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request): Response;

    /**
     *
     * @param  Request $request
     * @return Response
     */
    public function get(Request $request): Response;

    /**
     *
     * @param  Request $request
     * @return Response
     */
    public function create(Request $request): Response;
}
