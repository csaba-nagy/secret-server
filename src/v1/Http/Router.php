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
    $route = $request->getExplodedUri();

    $router = $route[0] ?? null;
    $param = $route[1] ?? null;

    $this->controller = $this->createController(empty($router) ? 'index' : $router);

    $this->request->setParams(['param' => $param]);
  }

  /**
   *
   * @return mixed
   */
  public function resolve()
  {
    return $this->controller->{$this->request->getMethod()}($this->request);
  }

  /**
   *
   * @param string $controllerName
   * @return ControllerInterface
   * @throws RouteNotFoundException
   */
  private function createController(string $controllerName): ControllerInterface
  {
    $prefix = ucfirst($controllerName);
    $controller = "SecretServer\Api\\v1\Controllers\\{$prefix}Controller";

    if (class_exists($controller)) {
      $class = new $controller();

      if (method_exists($class, $this->request->getMethod())) {
        return $class;
      }
    }

    return new ErrorController();
  }
}
