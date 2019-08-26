<?php declare(strict_types=1);


namespace Swoft\Amqp;

use Swoft\Amqp\Connection\Connection;
use Swoft\Amqp\Connection\ConnectionManager;
use Swoft\Amqp\Exception\AmqpException;
use Swoft\Bean\BeanFactory;
use Swoft\Amqp\Pool;
use Throwable;

class Amqp
{

    /**
     * @param string $pool
     * @return Connection
     * @throws AmqpException
     * @throws \Swoft\Connection\Pool\Exception\ConnectionPoolException
     */
    public static function connection(string $pool = Pool::DEFAULT_POOL): Connection
    {
        try {
            /* @var ConnectionManager $conManager */
            $conManager = BeanFactory::getBean(ConnectionManager::class);

            /* @var Pool $amqpPool */
            $amqpPool  = BeanFactory::getBean($pool);
            $connection = $amqpPool->getConnection();

            $connection->setRelease(true);
            $conManager->setConnection($connection);
        } catch (Throwable $e) {
            throw new AmqpException(
                sprintf('Pool error is %s file=%s line=%d', $e->getMessage(), $e->getFile(), $e->getLine())
            );
        }

        // Not instanceof Connection
        if (!$connection instanceof Connection) {
            throw new AmqpException(
                sprintf('%s is not instanceof %s', get_class($connection), Connection::class)
            );
        }
        return $connection;
    }

}
