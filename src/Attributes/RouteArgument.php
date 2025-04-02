<?php
declare(strict_types=1);

namespace Fyre\Engine\Attributes;

use Attribute;
use Fyre\Container\Container;
use Fyre\Container\ContextualAttribute;
use Fyre\Server\ServerRequest;

#[Attribute(Attribute::TARGET_PARAMETER)]
class RouteArgument extends ContextualAttribute
{
    protected string $name;

    /**
     * New Config constructor.
     *
     * @param string $name The name.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Get a route argument.
     *
     * @param Container $container The Container.
     * @return mixed The route argument.
     */
    public function resolve(Container $container): mixed
    {
        return $container->use(ServerRequest::class)
            ->getParam('route')
            ->getArguments()[$this->name] ?? null;
    }
}
