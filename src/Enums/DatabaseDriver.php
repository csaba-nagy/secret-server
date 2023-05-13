<?php

declare(strict_types=1);

namespace SecretServer\Enums;

/**
 * Contains the supported database drivers
 */
enum DatabaseDriver: string
{
    case MYSQL = 'mysql';
}
