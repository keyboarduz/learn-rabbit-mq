<?php
require_once __DIR__ . '/../vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('task_queue2', false, true, false, false);

$data = implode(' ', array_slice($argv, 1));
if (empty($data)) {
    $data =  "Hello World!";
}

$msg = new AMQPMessage($data, [
    'delivery_model' => AMQPMessage::DELIVERY_MODE_PERSISTENT
]);

$channel->basic_publish($msg, '', 'task_queue2');

echo " [x] Sent " . $data . "\n";

$channel->close();
$connection->close();