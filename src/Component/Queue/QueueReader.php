<?php

namespace App\Component\Queue;

use PhpAmqpLib\Channel\AMQPChannel;

final class QueueReader
{
    public function __construct(
        private readonly AMQPChannel $channel,
        private string $queueName,
    ) {}

    /**
     * @param callable(): mixed $callback
     */
    public function consume(callable $callback): void
    {
        $this->channel->basic_consume(queue: $this->queueName, callback: $callback);
    }
}