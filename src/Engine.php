<?php
declare(strict_types=1);

namespace Fyre\Engine;

use Fyre\Command\CommandRunner;
use Fyre\Config\Config;
use Fyre\Controller\ComponentRegistry;
use Fyre\Entity\EntityLocator;
use Fyre\Lang\Lang;
use Fyre\Middleware\MiddlewareQueue;
use Fyre\Migration\MigrationRunner;
use Fyre\ORM\BehaviorRegistry;
use Fyre\ORM\ModelRegistry;
use Fyre\View\CellRegistry;
use Fyre\View\HelperRegistry;
use Fyre\View\Template;

use const CONFIG;
use const LANG;
use const TEMPLATES;

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
        Template::addPath(TEMPLATES);

        BehaviorRegistry::addNamespace('App\Model\Behaviors');
        CellRegistry::addNamespace('App\View\Cells');
        CommandRunner::addNamespace('App\Command');
        ComponentRegistry::addNamespace('App\Controller\Components');
        EntityLocator::addNamespace('App\Entity');
        HelperRegistry::addNamespace('App\View\Helpers');
        MigrationRunner::setNamespace('App\Migration');
        ModelRegistry::addNamespace('App\Model');

        Config::load('functions');
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
        Config::load('routes');
    }

}
