<?php

namespace App\Component\Queue;

use PhpAmqpLib\Message\AMQPMessage;

final class QueueMessageFactory
{
    /**
     * @param array<string,mixed> $options
     */
    public function create(array $options): AMQPMessage
    {
        return new AMQPMessage(...$options);
    }
}