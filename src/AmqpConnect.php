<?php declare(strict_types=1);


namespace Swoft\Amqp;

use Swoft\Amqp\Connection\AmqpConnection;
use Swoft\Bean\BeanFactory;
use Swoft\Amqp\Pool;
use Swoft\Amqp\Connection\Connection;

/**
 * Class AmqpConnect
 *
 * @since 2.0
 *
 */
class AmqpConnect
{
    /**
     * @var string
     */
    private $host = '172.20.0.15';

    /**
     * @var string
     */
    private $vhost = '/';

    /**
     * @var string
     */
    private $port = '5672';

    /**
     * @var string
     */
    private $login = 'guest';

    /**
     * @var string
     */
    private $password = 'guest';

    private $route = '';

    private $changeName = '';

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getVhost(): string
    {
        return $this->vhost;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $route
     */
    public function setRoute(string $route): void
    {
        $this->route = $route;
    }

    /**
     * @param string $changeName
     */
    public function setChangeName(string $changeName): void
    {
        $this->changeName = $changeName;
    }

    public function createConnection(Pool $pool): Connection
    {
        $connect =  BeanFactory::getBean(AmqpConnection::class);
        $connect->initialize($pool,$this);
        $connect->create();
        return $connect;
    }

}
