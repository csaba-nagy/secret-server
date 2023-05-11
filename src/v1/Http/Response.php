<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Http;

use SecretServer\Api\v1\Contracts\ResponseInterface;
use SecretServer\Enums\HttpStatusCode;

// NOTE: The application supports only the json format for now.
// But, if another response format is needed, it can be easily adapted.
class Response implements ResponseInterface
{
  public function __construct(
    private string $acceptHeader,
    private HttpStatusCode $statusCode,
    private array | string $body
  ) {
  }

  /**
   * Sends the right response format according to the Accept header
   * If it is not properly set, it sends 406 Not acceptable
   * @return string
   */
  public function send(): string
  {
    return match (true) {
      preg_match('/application\/json/', $this->acceptHeader) => $this->sendJSON(),
      default => $this->reject()
    };
  }

  private function sendJSON(): string
  {
    http_response_code($this->statusCode->value);

    return json_encode($this->body, JSON_PRETTY_PRINT);
  }

  /**
   * Rejects the request with 406 Not Acceptable
   * @return void
   */
  private function reject(): void
  {
    http_response_code(HttpStatusCode::NOT_ACCEPTABLE->value);
  }
}
