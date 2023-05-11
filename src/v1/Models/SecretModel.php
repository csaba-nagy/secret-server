<?php

declare(strict_types=1);

namespace SecretServer\Api\v1\Models;

use DateTimeImmutable;
use InvalidArgumentException;
use PDOException;
use SecretServer\Api\v1\Abstracts\BaseModel;
use SecretServer\Database\Database;

class SecretModel extends BaseModel
{
  private $defaultViews = 5;
  private $defaultExpirationInMinutes = 5;

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

    $this->setExpiration($lastInsertedId);

    return $this->get($payload['hash']);
  }

  /**
   *
   * @param string $hash
   * @return null|array
   * @throws InvalidArgumentException
   * @throws PDOException
   */
  public function get(string $hash): ?array
  {
    $query = <<<SQL
            select
              secrets.hash as hash,
              secrets.secret as secretText,
              secrets.created_at as createdAt,
              secret_expirations.expires_at as expiresAt,
              secret_expirations.remaining_views as remainingViews
            from
              secrets
              join secret_expirations on 1=1
                and secrets.id=secret_expirations.secret_id
            where 1=1
              and secrets.hash=:hash
            SQL;

    $secret = $this->database->fetch($query, ['hash' => $hash]);

    if (empty($secret) || $this->isExpired($secret)) {
      return null;
    }

    return $secret;
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

    $expiration = $minutes ?? $this->defaultExpirationInMinutes;

    $this->database->fetch($query, [
      'secret_id' => $id,
      'expires_at' => $expiration,
      'remaining_views' => $remainingViews ?? $this->defaultViews
    ]);
  }

  /**
   * Checks that the fetched secret is expired
   * @param array $fetchedSecret
   * @return bool
   */
  private function isExpired(array $fetchedSecret): bool
  {
    $remainingViews = $fetchedSecret[0]['remainingViews'];
    $expiresAt = $fetchedSecret[0]['expiresAt'];

    $now = new DateTimeImmutable();

    return $remainingViews === 0
      || $expiresAt < $now->format('Y-m-d H:i:s');
  }
}
