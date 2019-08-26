<?php declare(strict_types=1);


namespace Swoft\Amqp\Contract;

use AMQPExchange;
use AMQPQueue;

/**
 * Class ConnectionInterface
 *
 * @since 2.0
 */
interface ConnectionInterface
{
    /**
     * Create client
     */
    public function createClient(): void;

    /**
     * @param string $queueName
     * @param string $exchangeName
     * @param string $routeKey
     * @param array $argument 设置延迟队列
     *  [
     *   'x-dead-letter-exchange' => 'delay_exchange',
     *   'x-dead-letter-routing-key' => 'delay_route',
     *   'x-message-ttl' => 60000
     *  ]
     * @return AMQPQueue
     */
    public function setQueue(string $queueName,string $exchangeName,string $routeKey,array $argument):AMQPQueue;

    /**
     * @param string $exchangeName
     * @return AMQPExchange
     */
    public function setExchange(string $exchangeName):AMQPExchange;
}
