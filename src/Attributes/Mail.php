<?php
declare(strict_types=1);

namespace Fyre\Engine\Attributes;

use Attribute;
use Fyre\Container\Container;
use Fyre\Container\ContextualAttribute;
use Fyre\Mail\Mailer;
use Fyre\Mail\MailManager;

#[Attribute(Attribute::TARGET_PARAMETER)]
class Mail extends ContextualAttribute
{
    protected string $key;

    /**
     * New Mail constructor.
     *
     * @param string $key The key.
     */
    public function __construct(string $key = MailManager::DEFAULT)
    {
        $this->key = $key;
    }

    /**
     * Load a shared handler instance.
     *
     * @param Container $container The Container.
     * @return Mailer The handler.
     */
    public function resolve(Container $container): Mailer
    {
        return $container->use(MailManager::class)->use($this->key);
    }
}
