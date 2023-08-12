<?php

namespace App\Component\Database;

use App\Component\Container\ClosureInterface;
use App\Component\Request\Request;
use LogicException;

final class Clickhouse implements DatabaseInterface, ClosureInterface
{
    private readonly Request $request;

    private ?string $log = null;

    private ?string $stmt = null;

    private array $stmtParams = [];

    public function __construct(private readonly string $host)
    {
        $this->request = new Request();
    }

    public function query(string $sql): self
    {
        $this->request->setUrl(sprintf(
            '%s/?query=%s',
            $this->host,
            rawurlencode($sql)
        ));

        return $this;
    }

    public function prepare(string $sql): self
    {
        $this->stmt = $sql;
        preg_match_all('/\:([a-z]+)/', $sql, $this->stmtParams);

        return $this;
    }

    public function execute(array $params = []): self
    {
        $search = [];
        $replace = [];

        foreach (current($this->stmtParams) as $key => $value) {
            $paramKey = $this->stmtParams[1][$key];
            if (!array_key_exists($paramKey, $params)) {
                throw new LogicException('Parameter does not exists');
            }

            $search[] = $value;
            $replace[] = is_string($params[$paramKey])
                ? sprintf("'%s'", addslashes($params[$paramKey]))
                : $params[$paramKey]
            ;
        }

        $query = trim(str_replace($search, $replace, $this->stmt));
        $this->request->post($this->host, $query);

        return $this;
    }

    public function fetchAll(): array
    {
        $response = $this->request->request();
        $lines = array_map(
            array: explode("\n", rtrim($response->getBody())),
            callback: fn(string $row) => json_decode($row, JSON_OBJECT_AS_ARRAY)
        );

        return $lines;
    }

    public function close(): void
    {
        $this->request?->close();
    }
}