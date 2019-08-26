<?php declare(strict_types=1);


namespace Swoft\Amqp\Connection;


use Swoft;
use Swoft\Amqp\Amqp;
use Swoft\Amqp\Pool;
use Swoft\Amqp\AmqpConnect;
use Swoft\Bean\BeanFactory;
use Swoft\Bean\Exception\ContainerException;
use Swoft\Connection\Pool\AbstractConnection;
use AMQPConnection;
use AMQPChannel;
use AMQPExchange;
use AMQPQueue;
use Swoft\Amqp\Exception\AmqpException;
use Swoft\Amqp\Contract\ConnectionInterface;

abstract class Connection extends AbstractConnection implements ConnectionInterface
{

    /**
     * @var Amqp
     */
    protected $client;

    /**
     * @var AmqpConnect
     */
    protected $amqp;

    /**
     * @param Pool $pool
     * @param AmqpConnect $amqpConnect
     */
    public function initialize(Pool $pool, AmqpConnect $amqpConnect)
    {
        $this->pool   = $pool;
        $this->amqp   = $amqpConnect;
        $this->lastTime = time();

        $this->id = $this->pool->getConnectionId();
    }

    /**
     * @throws AmqpException
     * @throws \AMQPConnectionException
     */
    public function create(): void
    {
        $this->createClient();
    }

    /**
     * Close connection
     */
    public function close(): void
    {
        $this->client->close();
    }

    /**
     * @throws AmqpException
     * @throws \AMQPConnectionException
     */
    public function createClient(): void
    {
        $config = [
            'host'     => $this->amqp->getHost(),
            'vhost'    => $this->amqp->getVhost(),
            'port'     => $this->amqp->getPort(),
            'login'    => $this->amqp->getLogin(),
            'password' => $this->amqp->getPassword(),
        ];

        $conn = new AMQPConnection($config);
        if($conn->connect()){
            $chan = new AMQPChannel($conn);
        }else{
            throw new AmqpException('Amqp connection fail!');
        }
        $this->client  = $chan;
    }

    /**
     * @param string $exchangeName
     * @return AMQPExchange
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPExchangeException
     */
    public function setExchange(string $exchangeName) : AMQPExchange
    {
        $exchange = new AMQPExchange($this->client);
        $exchange->setFlags(AMQP_DURABLE);//持久化
        $exchange->setName($exchangeName?:'');
        $exchange->setType(AMQP_EX_TYPE_DIRECT); //direct类型
        $exchange->declareExchange();
        return $exchange;
    }

    /**
     * @param string $queueName
     * @param string $exchangeName
     * @param string $routeKey
     * @param array $argument
     * @return AMQPQueue
     * @throws \AMQPChannelException
     * @throws \AMQPConnectionException
     * @throws \AMQPQueueException
     */
    public function setQueue(string $queueName,string $exchangeName,string $routeKey,array $argument=[]) : AMQPQueue
    {
        $queue = new AMQPQueue($this->client);
        $queue->setName($queueName ?:'');
        $queue->setFlags(AMQP_DURABLE);
        if($argument){
            $queue->setArguments($argument);
        }
        $queue->declareQueue();
        $queue->bind($exchangeName, $routeKey);
        return $queue;
    }


    /**
     * @param bool $force
     * @throws ContainerException
     * @throws \ReflectionException
     */
    public function release(bool $force = false): void
    {
        /* @var ConnectionManager $conManager */
        $conManager = BeanFactory::getBean(ConnectionManager::class);
        $conManager->releaseConnection($this->id);

        parent::release($force);
    }

}
