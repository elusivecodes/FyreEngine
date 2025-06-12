<?php
declare(strict_types=1);

namespace Fyre\Engine\Attributes;

use Attribute;
use Fyre\Container\Container;
use Fyre\Container\ContextualAttribute;
use Fyre\ORM\Model;
use Fyre\ORM\ModelRegistry;

#[Attribute(Attribute::TARGET_PARAMETER)]
class ORM extends ContextualAttribute
{
    /**
     * New ORM constructor.
     *
     * @param string $alias The alias.
     */
    public function __construct(
        protected string $alias
    ) {}

    /**
     * Load a shared handler instance.
     *
     * @param Container $container The Container.
     * @return Model The Model.
     */
    public function resolve(Container $container): Model
    {
        return $container->use(ModelRegistry::class)->use($this->alias);
    }
}
