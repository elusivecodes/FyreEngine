<?php
declare(strict_types=1);

namespace Fyre\Engine\Attributes;

use Attribute;
use Fyre\Container\Container;
use Fyre\Container\ContextualAttribute;
use Fyre\Encryption\Encrypter;
use Fyre\Encryption\EncryptionManager;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Encryption extends ContextualAttribute
{
    protected string $key;

    /**
     * New Encryption constructor.
     *
     * @param string $key The key.
     */
    public function __construct(string $key = EncryptionManager::DEFAULT)
    {
        $this->key = $key;
    }

    /**
     * Load a shared handler instance.
     *
     * @param Container $container The Container.
     * @return Encrypter The handler.
     */
    public function resolve(Container $container): Encrypter
    {
        return $container->use(EncryptionManager::class)->use($this->key);
    }
}
