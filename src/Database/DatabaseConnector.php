<?php

declare(strict_types=1);

namespace SecretServer\Database;

use InvalidArgumentException;
use PDO;
use PDOException;
use PDOStatement;
use SecretServer\Enums\DatabaseDriver;

final class DatabaseConnector
{
  private ?PDO $pdo;
  private ?PDOStatement $statement;

  private const PDO_OPTIONS = [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
  ];

  /**
   * @param null|string $dsn
   * @param null|array $options
   * @return void
   * @throws PDOException
   */
  public function __construct(?string $dsn = null, ?array $options = null)
  {
    $this->pdo = new PDO(
      $dsn ?? self::getDsn(DatabaseDriver::MYSQL),
      null,
      null,
      $options ?? self::PDO_OPTIONS
    );
  }

  /**
   * @return bool
   */
  public function isConnected(): bool
  {
    return (bool) $this->pdo;
  }

  /**
   * @param string $query
   * @param null|array $params
   * @return DatabaseConnector
   */
  public function prepare(string $query, ?array $params = null): self
  {
    $this->statement = $this->pdo?->prepare($query) ?: null;

    return empty($params) ? $this : $this->bindValues($params);
  }

  /**
   *
   * @return null|DatabaseConnector
   * @throws PDOException
   */
  public function execute(): ?self
  {
    return $this->statement->execute() ? $this : null;
  }

  /**
   *
   * @return int
   */
  public function rowCount(): int
  {
    return $this->statement?->rowCount() ?? 0;
  }

  /**
   *
   * @return null|array
   * @throws PDOException
   */
  public function fetchAll(): ?array
  {
    if (empty($this->execute()?->rowCount())) {
      return null;
    }

    return $this->statement?->fetchAll() ?: null;
  }

  // TODO: Add proper param description
  /**
   *
   * @param string $query
   * @param null|array $params
   * @return null|array
   * @throws InvalidArgumentException
   * @throws PDOException
   */
  public function fetch(string $query, ?array $params = null): ?array
  {
    return $this->prepare($query, $params)->fetchAll();
  }

  /**
   *
   * @param null|string $name
   * @return int
   */
  public function getLastInsertedId(?string $name = null): int
  {
    return (int) $this->pdo?->lastInsertId($name);
  }

  // TODO: Add a proper param description
  /**
   * @param DatabaseDriver $driver
   * @param array|null $values
   * @return string
   */
  public static function getDsn(DatabaseDriver $driver, array $values = null): string
  {
    $format = '%s:host=%s;port=%d;dbname=%s;user=%s;password=%s;charset=%s';

    $values = [
      $values['host'] ?? $_ENV['MYSQL_HOST'],
      $values['port'] ?? $_ENV['MYSQL_PORT'],
      $values['dbname'] ?? $_ENV['MYSQL_DATABASE'],
      $values['user'] ?? $_ENV['MYSQL_USER'],
      $values['password'] ?? $_ENV['MYSQL_PASSWORD'],
      $values['charset'] ?? $_ENV['MYSQL_CHARSET'],
    ];

    return sprintf($format, $driver->value, ...$values);
  }

  /**
   *
   * @param array $params
   * @return DatabaseConnector
   * @throws InvalidArgumentException
   */
  private function bindValues(array $params): self
  {
    foreach ($params as $key => $value) {
      $args = [":{$key}", $value, $this->getValueType($value)];

      if ($this->statement->bindValue(...$args)) {
        continue;
      }

      throw new InvalidArgumentException('Cannot bind value');
    }

    return $this;
  }

  /**
   *
   * @param mixed $value
   * @return int
   */
  private function getValueType(mixed $value): int
  {
    return match (true) {
      is_null($value) => PDO::PARAM_NULL,
      is_bool($value) => PDO::PARAM_BOOL,
      is_numeric($value) => PDO::PARAM_INT,
      default => PDO::PARAM_STR
    };
  }
}
