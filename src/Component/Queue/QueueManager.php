<?php

namespace App\Component\Queue;

use App\Component\Container\ClosureInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

final class QueueManager implements ClosureInterface
{
    private AMQPChannel $channel;

    public function __construct(
        private readonly AMQPStreamConnection $connection,
        private readonly string $queueName
    ) {
        $this->channel = $this->connection->channel();
        $this->channel->queue_declare($queueName);
    }

    public function getChannel(): AMQPChannel
    {
        return $this->channel;
    }

    public function close(): void
    {
        $this->channel->close();
        $this->connection->close();
    }
}