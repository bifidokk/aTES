<?php

namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class UserConsumer implements ConsumerInterface
{

    public function execute(AMQPMessage $msg)
    {
        dump($msg);
    }
}
