<?php

use App\Component\Container\ContainerInterface;
use App\Repository\MariadbRepository;
use App\Repository\ClickhouseRepository;

require_once dirname(__DIR__) . '/config/bootstrap.php';

/**@var ContainerInterface $container */

$mariadbData = $container->get(MariadbRepository::class)?->groupByHourMinute();
$clickhouseData = $container->get(ClickhouseRepository::class)?->groupByHourMinute();
?>

<style>
    * {font-size: 18px;font-family: 'Courier New', Courier, monospace;}
    table {width: 100%;cursor: pointer;}
    thead {font-weight: bold;background-color: #009688;color: #fff;}
    td, th {padding: 0.4rem;}
    tbody tr:hover {background-color: #93c7c2;color: #fff;}
</style>

<h2>MariaDB</h2>
<table>
    <thead>
        <tr>
            <th>Rows count</th>
            <th>Hour:Minute</th>
            <th>Average content size</th>
            <th>First in group</th>
            <th>Last in group</th>
        </tr>
    </thead>
    <tbody>
        <!-- Mysql -->
        <?php foreach ($mariadbData as $value): ?>
           <tr>
                <td><?=$value['total_rows'] ?></td>
                <td><?=$value['hour_min'] ?></td>
                <td><?=$value['avg_length'] ?></td>
                <td><?=$value['first'] ?></td>
                <td><?=$value['last'] ?></td>
           </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<h2>ClickHouse</h2>
<table>
    <thead>
        <tr>
            <th>Rows count</th>
            <th>Hour:Minute</th>
            <th>Average content size</th>
            <th>First in group</th>
            <th>Last in group</th>
        </tr>
    </thead>
    <tbody>
        <!-- Clickhouse -->
        <?php foreach ($clickhouseData as $value): ?>
           <tr>
                <td><?=@$value['total_rows'] ?></td>
                <td><?=@$value['hour_min'] ?></td>
                <td><?=@$value['avg_length'] ?></td>
                <td><?=@$value['first'] ?></td>
                <td><?=@$value['last'] ?></td>
           </tr>
        <?php endforeach; ?>
    </tbody>
</table>