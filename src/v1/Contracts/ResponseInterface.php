<?php

namespace SecretServer\Api\v1\Contracts;

interface ResponseInterface
{
  public function send(): string;
}
