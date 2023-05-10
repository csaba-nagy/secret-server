<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Http;

use SecretServer\Enums\AllowedHttpMethods;

class Request
{
  private string $uri;
  private string $method;
  private string $httpMethod;
  private ?array $payload;
  private array $params = [];
  private string $acceptHeader;

  public function __construct()
  {
    $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $this->httpMethod = strtolower($_SERVER['REQUEST_METHOD']);

    $this->method = match ($this->httpMethod) {
      AllowedHttpMethods::get->value => 'get',
      AllowedHttpMethods::post->value => 'create',
      default => 'index'
    };

    $this->payload = $this->httpMethod === AllowedHttpMethods::post->value
      ? json_decode(file_get_contents('php://input'), true)
      : null;

    $this->acceptHeader = $_SERVER['HTTP_ACCEPT'];
  }

  /**
   *
   * @return string
   */
  public function getUri(): string
  {
    return $this->uri;
  }

  /**
   *
   * @return string
   */
  public function getMethod(): string
  {
    return $this->method;
  }

  /**
   *
   * @return null|array
   */
  public function getPayload(): ?array
  {
    return $this->payload;
  }

  /**
   *
   * @return bool
   */
  public function hasPayload(): bool
  {
    return $this->payload !== null;
  }

  /**
   *
   * @return array
   */
  public function getExplodedUri(): array
  {
    return explode('/', substr($this->uri, 1));
  }

  /**
   *
   * @param array $params
   * @return void
   */
  public function setParams(array $params): void
  {
    $this->params = $params;
  }

  /**
   *
   * @return array
   */
  public function getParams(): array
  {
    return $this->params;
  }

  /**
   *
   * @return string
   */
  public function getAcceptHeader(): string
  {
    return $this->acceptHeader;
  }
}
