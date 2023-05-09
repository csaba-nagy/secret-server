<?php

declare(strict_types=1);

namespace SecretServer\Database;

final class Database
{
  private static ?DatabaseConnector $connection;

  /**
   *
   * @return DatabaseConnector
   */
  public static function getConnection()
  {
    return self::$connection
      ??= new DatabaseConnector();
  }
}
