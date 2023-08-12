<?php

use App\Component\Container\Container;
use App\Component\Database\Clickhouse;
use App\Component\Database\Mariadb;
use App\Component\Queue\QueueManager;
use App\Component\Queue\QueueMessageFactory;
use App\Component\Queue\QueueReader;
use App\Component\Queue\QueueWriter;
use App\Repository\ClickhouseRepository;
use App\Repository\MariadbRepository;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once dirname(__DIR__) . '/vendor/autoload.php';


$container = new Container([
    QueueManager::class => function () {
        return new QueueManager(queueName: 'statistic', connection: new AMQPStreamConnection(
            host: 'rabbitmq',
            port: 5672,
            user: getenv('RABBITMQ_DEFAULT_USER'),
            password: getenv('RABBITMQ_DEFAULT_PASS'),
        ));
    },

    AMQPChannel::class => function (QueueManager $manager) {
        return $manager->getChannel();
    },

    QueueWriter::class => function (AMQPChannel $channel) {
        return new QueueWriter($channel, new QueueMessageFactory(), 'statistic');
    },

    QueueReader::class => function (AMQPChannel $channel) {
        return new QueueReader($channel, 'statistic');
    },

    Mariadb::class => function () {
        return new Mariadb(new PDO(
            sprintf('mysql:host=mariadb;dbname=%s', getenv('MARIADB_DATABASE')),
            getenv('MARIADB_USER'),
            getenv('MARIADB_PASSWORD'),
        ));
    },

    Clickhouse::class => function () {
        return new Clickhouse('http://clickhouse:8123');
    },

    MariadbRepository::class => function (Mariadb $db) {
        return new MariadbRepository($db);
    },

    ClickhouseRepository::class => function (Clickhouse $db) {
        return new ClickhouseRepository($db);
    },

]);
