<?php

declare(strict_types=1);

namespace SecretServer\Models;

use InvalidArgumentException;
use PDOException;
use SecretServer\Database\Database;
use SecretServer\Models\Abstractions\BaseModel;

class SecretModel extends BaseModel
{
  private int $defaultViews = 5;
  private int $defaultExpirationInMinutes = 5;

  public function __construct()
  {
    parent::__construct(Database::getConnection());
  }

  /**
   *
   * @param array $payload
   * @return array
   * @throws InvalidArgumentException
   * @throws PDOException
   */
  public function create(array $payload): array
  {
    // TODO: Would be better to run it in transaction
    $query = <<<SQL
              insert into secrets(hash, secret)
              values(:hash, :secret)
              SQL;

    $this->database->fetch($query, [
      'hash' => $payload['hash'],
      'secret' => $payload['secret']
    ]);

    $lastInsertedId = $this->database->getLastInsertedId();

    $this->setExpiration($lastInsertedId, $payload['expiresAfter'], $payload['expireAfterViews']);

    return $this->getByHash($payload['hash']);
  }

  /**
   *
   * @param string $hash
   * @return null|array
   * @throws InvalidArgumentException
   * @throws PDOException
   */
  public function getByHash(string $hash): ?array
  {
    $query = <<<SQL
            select
              secrets.hash as hash,
              secrets.secret as secretText,
              secrets.created_at as createdAt,
              secret_expirations.expires_at as expiresAt
            from
              secrets
              join secret_expirations on 1=1
                and secrets.id=secret_expirations.secret_id
            where 1=1
              and secrets.hash=:hash
            SQL;
    // TODO: handle expiration after views
    $this->destroyIfExpired();

    return $this->database->fetch($query, ['hash' => $hash]);
  }

  /**
   *
   * @param int $id
   * @param null|int $minutes
   * @param null|int $remainingViews
   * @return void
   * @throws InvalidArgumentException
   * @throws PDOException
   */
  private function setExpiration(int $id, ?int $minutes = null, ?int $remainingViews = null): void
  {
    $query = <<<SQL
              insert into secret_expirations(secret_id, expires_at, remaining_views)
              values(:secret_id, NOW() + INTERVAL :expires_at MINUTE, :remaining_views)
              SQL;

    $this->database->fetch($query, [
      'secret_id' => $id,
      'expires_at' => $minutes ?? $this->defaultExpirationInMinutes,
      'remaining_views' => $remainingViews ?? $this->defaultViews
    ]);
  }

  // Maybe it's not the most performant solution,
  // but I would like to handle the expirations in code, not in the database in this case
  /**
   *
   * @return void
   * @throws InvalidArgumentException
   * @throws PDOException
   */
  private function destroyIfExpired(): void
  {
    $query = <<<SQL
              delete secrets, secret_expirations
                from secrets
                inner join secret_expirations on 1=1
                  and secrets.id=secret_expirations.secret_id
                where secret_expirations.expires_at < NOW()
            SQL;

    $this->database->fetch($query);
  }
}
