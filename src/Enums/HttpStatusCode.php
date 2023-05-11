<?php

declare(strict_types=1);

namespace SecretServer\Enums;

enum HttpStatusCode: int
{
  case OK = 200;
  case CREATED = 201;
  case BAD_REQUEST = 400;
  case NOT_FOUND = 404;
  case NOT_ACCEPTABLE = 406;
}
