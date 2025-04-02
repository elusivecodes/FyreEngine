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
    protected string $alias;

    /**
     * New ORM constructor.
     *
     * @param string $alias The alias.
     */
    public function __construct(string $alias)
    {
        $this->alias = $alias;
    }

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
