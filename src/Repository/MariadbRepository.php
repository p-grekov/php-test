<?php

namespace App\Repository;

use App\Component\Database\AbstractRepository;

final class MariadbRepository extends AbstractRepository
{
    /**
     * @return array<array<string,mixed>>
     */
    public function groupByHourMinute(): array
    {
        $this->getConnnection()->query('
            select
                DATE_FORMAT(updated_at, "%H:%i") hour_min,
                count(*) total_rows,
                AVG(length) avg_length,
                min(updated_at) first,
                max(updated_at) last

            from statistic
            group by hour_min
        ');

        return $this->getConnnection()->fetchAll();
    }

    public function insertStatistic(string $url, int $length): self
    {
        $this->getConnnection()
            ->prepare('
                insert into statistic (url, length)
                values (:url, :length)
            ')
            ->execute(['url' => $url, 'length' => $length]);

        return $this;
    }
}