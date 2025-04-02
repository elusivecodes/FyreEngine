<?php
declare(strict_types=1);

namespace Fyre\Engine\Attributes;

use Attribute;
use Fyre\Container\Container;
use Fyre\Container\ContextualAttribute;
use Fyre\DB\Connection;
use Fyre\DB\ConnectionManager;

#[Attribute(Attribute::TARGET_PARAMETER)]
class DB extends ContextualAttribute
{
    protected string $key;

    /**
     * New DB constructor.
     *
     * @param string $key The key.
     */
    public function __construct(string $key = ConnectionManager::DEFAULT)
    {
        $this->key = $key;
    }

    /**
     * Load a shared handler instance.
     *
     * @param Container $container The Container.
     * @return Connection The handler.
     */
    public function resolve(Container $container): Connection
    {
        return $container->use(ConnectionManager::class)->use($this->key);
    }
}
