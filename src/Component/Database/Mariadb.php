<?php

namespace App\Component\Database;

use PDO;
use PDOStatement;

final class Mariadb implements DatabaseInterface
{
    private ?PDOStatement $stmt = null;

    public function __construct(private readonly PDO $pdo)
    {}

    public function query(string $sql): self
    {
        $this->stmt = $this->pdo->query($sql);

        return $this;
    }

    public function prepare(string $sql): self
    {
        $this->stmt = $this->pdo->prepare($sql);

        return $this;
    }

    public function execute(array $params = []): self
    {
        $this->stmt->execute($params);

        return $this;
    }

    public function fetchAll(): array
    {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}