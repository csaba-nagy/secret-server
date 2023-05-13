<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Utils;

use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

final class Hash
{
    // The length of the hash is capped at 10 characters.
    // With this length we can ensure the uniqueness of the hash
    // and it takes less space in the database
    // In a real production app, It cannot be a good practice, but in this project should be enough.
    private static int $length = 10;

    /**
     * Generates a hash string
     *
     * @param  int $length
     * @return string
     * @throws InvalidArgumentException
     */
    public static function generate()
    {
        $uuid = Uuid::uuid4()->toString();

        return substr($uuid, 0, self::$length);
    }
}
