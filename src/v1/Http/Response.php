<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Http;

use Exception;
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
     * If it is not properly set, it sends the response in JSON format by default
     *
     * @return string
     */
    public function send(): string
    {
        return match (1) {
            preg_match('/application\/json/', $this->acceptHeader) => $this->sendJSON(),
            default => $this->sendJSON()

            // It's possible to reject a request without Accept header, but this might be too strict
            // default => $this->reject(HttpStatusCode::NOT_ACCEPTABLE, 'INVALID_OR_MISSING_HEADER_PARAMETER')
        };
    }

    public function __toString()
    {
        return $this->send();
    }

    /**
     * Sends a response to the client in JSON format
     *
     * @return string
     */
    private function sendJSON(): string
    {
        header('Content-Type: application/json; charset=utf-8');

        http_response_code($this->statusCode->value);

        return json_encode($this->body, JSON_PRETTY_PRINT);
    }


    /**
     *  Rejects the request
     *
     * @param  HttpStatusCode $statusCode
     * @param  string         $message
     * @return string
     * @throws Exception
     */
    private function reject(HttpStatusCode $statusCode, string $message): string
    {
        header('Content-Type: application/json; charset=utf-8');

        http_response_code($statusCode->value);

        return json_encode($message, JSON_PRETTY_PRINT);
    }
}
