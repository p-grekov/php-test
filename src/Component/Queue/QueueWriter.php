<?php

namespace App\Component\Queue;

use PhpAmqpLib\Channel\AMQPChannel;

final class QueueWriter
{
    public function __construct(
        private readonly AMQPChannel $channel,
        private readonly QueueMessageFactory $factory,
        private string $queueName,
    ) {}

    public function write(string $message): void
    {
        $message = $this->factory->create(['body' => $message]);

        $this->channel->basic_publish(
            msg: $message,
            routing_key: $this->queueName,
        );
    }
}