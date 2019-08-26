<?php declare(strict_types=1);


namespace Swoft\Amqp\Connection;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Log\Helper\Log;
use Throwable;

/**
 * Class AmqpConnection
 *
 * @since 2.0
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class AmqpConnection extends Connection
{

    /**
     * Reconnect connection
     */
    public function reconnect(): bool
    {
        try {
            $this->create();
        } catch (Throwable $e) {
            Log::error('Redis reconnect error(%s)', $e->getMessage());
            return false;
        }
        return true;
    }
}
