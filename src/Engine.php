<?php
declare(strict_types=1);

namespace Fyre\Engine;

use Fyre\Command\CommandRunner;
use Fyre\Config\Config;
use Fyre\Entity\EntityLocator;
use Fyre\Lang\Lang;
use Fyre\Middleware\MiddlewareQueue;
use Fyre\Migration\MigrationRunner;
use Fyre\ORM\BehaviorRegistry;
use Fyre\ORM\ModelRegistry;
use Fyre\Utility\Path;
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
        Config::addPath(Path::join(__DIR__, 'config'));
        Lang::addPath(LANG);
        Template::addPath(TEMPLATES);

        CommandRunner::addNamespace('App\Commands');
        CommandRunner::addNamespace('Fyre\Queue\Commands');
        CommandRunner::addNamespace('Fyre\Migration\Commands');

        CellRegistry::addNamespace('App\Cells');
        EntityLocator::addNamespace('App\Entities');
        HelperRegistry::addNamespace('App\Helpers');
        MigrationRunner::setNamespace('App\Migrations');
        ModelRegistry::addNamespace('App\Models');
        BehaviorRegistry::addNamespace('App\Models\Behaviors');

        Config::load('functions');
        Config::load('bootstrap');
    }

    /**
     * Build application middleware.
     *
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
