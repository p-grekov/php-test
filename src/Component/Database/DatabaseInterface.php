<?php

namespace App\Component\Database;

interface DatabaseInterface
{
    public function query(string $sql): self;

    public function prepare(string $sql): self;

    /**
     * @param array<string,mixed> $params
     */
    public function execute(array $params = []): self;

    /**
     * @return array<array<string,mixed>>
     */
    public function fetchAll(): array;
}