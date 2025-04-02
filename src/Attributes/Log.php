<?php
declare(strict_types=1);

namespace Fyre\Engine\Attributes;

use Attribute;
use Fyre\Container\Container;
use Fyre\Container\ContextualAttribute;
use Fyre\Log\Logger;
use Fyre\Log\LogManager;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Log extends ContextualAttribute
{
    protected string $key;

    /**
     * New Log constructor.
     *
     * @param string $key The key.
     */
    public function __construct(string $key = LogManager::DEFAULT)
    {
        $this->key = $key;
    }

    /**
     * Load a shared handler instance.
     *
     * @param Container $container The Container.
     * @return Logger The handler.
     */
    public function resolve(Container $container): Logger
    {
        return $container->use(LogManager::class)->use($this->key);
    }
}
