<?php

declare(strict_types=1);

namespace SecretServer\Enums;

enum AllowedHttpMethods: string
{
    case get = 'get';
    case post = 'post';
}
