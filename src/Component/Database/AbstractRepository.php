<?php

namespace App\Component\Database;

abstract class AbstractRepository
{
    public function __construct(
        private readonly DatabaseInterface $db,
    ) {}

    protected function getConnnection(): DatabaseInterface
    {
        return $this->db;
    }
}