<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Http;

use SecretServer\Api\v1\Contracts\ControllerInterface;
use SecretServer\Api\v1\Controllers\ErrorController;
use SecretServer\Api\v1\Exceptions\RouteNotFoundException;
use SecretServer\Api\v1\Http\Request;

final class Router
{
    private ControllerInterface $controller;

    public function __construct(private Request $request)
    {
        $path = $request->getExplodedUri();

        match (count($path)) {
            1 => [$version] = $path,
            2 => [$version, $segment] = $path,
            3 => [$version, $segment, $param] = $path,
            default => throw new RouteNotFoundException()
        };

        $this->controller = $this->createController($version, $segment ?? 'index');

        $this->request->setParams(['param' => $param ?? null]);
    }

    /**
     *
     * @return Response
     */
    public function resolve()
    {
        return $this->controller->{$this->request->getMethod()}($this->request);
    }

    /**
     * Dinamically creates a controller and call the required method
     * according to the request path and request method
     *
     * @param  string $controllerName
     * @return ControllerInterface
     * @throws RouteNotFoundException
     */
    private function createController(string | null $apiVersion, string $controllerName): ControllerInterface
    {
        $prefix = ucfirst($controllerName);
        $controller = "SecretServer\Api\\{$apiVersion}\Controllers\\{$prefix}Controller";

        if (class_exists($controller)) {
            $class = new $controller();

            if (method_exists($class, $this->request->getMethod())) {
                return $class;
            }
        }

        return new ErrorController();
    }
}
