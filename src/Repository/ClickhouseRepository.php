<?php

namespace App\Repository;

use App\Component\Database\AbstractRepository;

final class ClickhouseRepository extends AbstractRepository
{
    /**
     * @return array<array<string,mixed>>
     */
    public function groupByHourMinute(): array
    {
        $this->getConnnection()->query('
            select 
                formatDateTime(updated_at, \'%H:%i\') AS hour_min,
                count(*) AS total_rows,
                AVG(length) AS avg_length,
                min(updated_at) AS first,
                max(updated_at) AS last

            FROM docker.statistic
            GROUP BY hour_min
            ORDER BY hour_min ASC
            format JSONStringsEachRow
        ');

        return $this->getConnnection()->fetchAll();
    }

    public function insertStatistic(string $url, int $length): self
    {
        $this->getConnnection()
            ->prepare('
                insert into docker.statistic (url, length)
                values (:url, :length)
            ')
            ->execute(['url' => $url, 'length' => $length]);

        return $this;
    }
}