<?php

namespace App\Component\Container;

interface ContainerInterface
{
    /**
     * @template T
     * 
     * @param class-string<T> $id
     * @return T
     */
    public function get(string $id): ?object;

    public function has(string $id): bool;
}