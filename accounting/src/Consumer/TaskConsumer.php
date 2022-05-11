<?php

namespace Accounting\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class TaskConsumer implements ConsumerInterface
{
    public function execute(AMQPMessage $msg)
    {
        $content = json_decode($msg->body, true);
        dump($content);
    }
}
