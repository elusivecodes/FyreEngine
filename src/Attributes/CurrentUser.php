<?php
declare(strict_types=1);

namespace Fyre\Engine\Attributes;

use Attribute;
use Fyre\Auth\Auth;
use Fyre\Container\Container;
use Fyre\Container\ContextualAttribute;
use Fyre\Entity\Entity;

#[Attribute(Attribute::TARGET_PARAMETER)]
class CurrentUser extends ContextualAttribute
{
    /**
     * Get the current user.
     *
     * @param Container $container The Container.
     * @return Entity|null The current user.
     */
    public function resolve(Container $container): Entity|null
    {
        return $container->use(Auth::class)->user();
    }
}
