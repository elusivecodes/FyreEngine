<?php
declare(strict_types=1);

namespace Fyre\Engine\Attributes;

use Attribute;
use Fyre\Cache\CacheManager;
use Fyre\Cache\Cacher;
use Fyre\Container\Container;
use Fyre\Container\ContextualAttribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Cache extends ContextualAttribute
{
    protected string $key;

    /**
     * New Cache constructor.
     *
     * @param string $key The key.
     */
    public function __construct(string $key = CacheManager::DEFAULT)
    {
        $this->key = $key;
    }

    /**
     * Load a shared handler instance.
     *
     * @param Container $container The Container.
     * @return Cacher The handler.
     */
    public function resolve(Container $container): Cacher
    {
        return $container->use(CacheManager::class)->use($this->key);
    }
}
