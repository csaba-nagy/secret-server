<?php

namespace SecretServer\Api\v1\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
  protected $message = '404 NOT_FOUND';
}
