<?php
declare(strict_types=1);

namespace Fyre\Engine\Attributes;

use Attribute;
use Fyre\Container\Container;
use Fyre\Container\ContextualAttribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Config extends ContextualAttribute
{
    protected string $key;

    /**
     * New Config constructor.
     *
     * @param string $key The key.
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * Retrieve a value from the config using "dot" notation.
     *
     * @param Container $container The Container.
     * @return mixed The config value.
     */
    public function resolve(Container $container): mixed
    {
        return $container->use(\Fyre\Config\Config::class)->get($this->key);
    }
}
