<?php
declare(strict_types=1);

namespace Fyre\Engine;

use
    Fyre\Command\CommandRunner,
    Fyre\Config\Config,
    Fyre\Controller\ComponentRegistry,
    Fyre\Lang\Lang,
    Fyre\Middleware\MiddlewareQueue,
    Fyre\Middleware\RequestHandler,
    Fyre\Migration\MigrationRunner,
    Fyre\ORM\BehaviorRegistry,
    Fyre\ORM\EntityLocator,
    Fyre\ORM\ModelRegistry,
    Fyre\Router\Router,
    Fyre\Server\ServerRequest,
    Fyre\View\HelperRegistry,
    Fyre\View\View;

use const
    CONFIG,
    LANG,
    TEMPLATES;

/**
 * Engine
 */
abstract class Engine
{

    /**
     * Bootstrap application.
     */
    public static function bootstrap(): void
    {
        Config::addPath(CONFIG);
        Lang::addPath(LANG);
        View::addPath(TEMPLATES);

        BehaviorRegistry::addNamespace('App\Model\Behaviors');
        CommandRunner::addNamespace('App\Command');
        ComponentRegistry::addNamespace('App\Controller\Components');
        EntityLocator::addNamespace('App\Entity');
        MigrationRunner::setNamespace('App\Migration');
        ModelRegistry::addNamespace('App\Model');
        HelperRegistry::addNamespace('App\View\Helpers');

        Config::load('functions');
        Config::load('app');
        Config::load('bootstrap');
    }

    /**
     * Build application middleware.
     * @param MiddlewareQueue $queue The MiddlewareQueue.
     * @return MiddlewareQueue The MiddlewareQueue.
     */
    public static function middleware(MiddlewareQueue $queue): MiddlewareQueue
    {
        return $queue;
    }

    /**
     * Build application routes.
     */
    public static function routes(): void
    {
        $baseUri = Config::get('App.baseUri');

        if ($baseUri) {
            Router::setBaseUri($baseUri);
        }

        Config::load('routes');
    }

}
