<?php declare(strict_types=1);


namespace Swoft\Amqp;

use Swoft\Bean\BeanFactory;
use Swoft\Connection\Pool\AbstractPool;
use Swoft\Connection\Pool\Contract\ConnectionInterface;
use Swoft\Amqp\AmqpConnect;

/**
 * Class Pool
 *
 * @since 2.0
 *
 */
class Pool extends AbstractPool
{
    /**
     * Default pool
     */
    const DEFAULT_POOL = 'amqp.pool';

    /**
     *
     * @var AmqpConnect
     */
    protected $amqpConnect;

    /**
     * @return ConnectionInterface
     */
    public function createConnection(): ConnectionInterface
    {
        $this->amqpConnect = BeanFactory::getBean(AmqpConnect::class);
        return $this->amqpConnect->createConnection($this);
    }

    /**
     * @return AmqpConnect
     */
    public function getAmqpConnection(): AmqpConnect
    {
        return $this->amqpConnect;
    }


}
