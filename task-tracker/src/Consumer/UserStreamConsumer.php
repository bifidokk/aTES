<?php

namespace Task\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class UserStreamConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        dump($msg);
    }
}
