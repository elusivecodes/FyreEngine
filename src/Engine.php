<?php
declare(strict_types=1);

namespace Fyre\Engine;

use Fyre\Auth\Access;
use Fyre\Auth\Auth;
use Fyre\Auth\Identifier;
use Fyre\Auth\Middleware\AuthenticatedMiddleware;
use Fyre\Auth\Middleware\AuthMiddleware;
use Fyre\Auth\Middleware\AuthorizedMiddleware;
use Fyre\Auth\Middleware\UnauthenticatedMiddleware;
use Fyre\Auth\PolicyRegistry;
use Fyre\Cache\CacheManager;
use Fyre\Command\CommandRunner;
use Fyre\Config\Config;
use Fyre\Console\Console;
use Fyre\Container\Container;
use Fyre\DB\ConnectionManager;
use Fyre\DB\TypeParser;
use Fyre\Encryption\EncryptionManager;
use Fyre\Entity\EntityLocator;
use Fyre\Error\ErrorHandler;
use Fyre\Error\Middleware\ErrorHandlerMiddleware;
use Fyre\Event\EventDispatcherTrait;
use Fyre\Event\EventManager;
use Fyre\Forge\ForgeRegistry;
use Fyre\Form\FormBuilder;
use Fyre\Lang\Lang;
use Fyre\Loader\Loader;
use Fyre\Log\LogManager;
use Fyre\Mail\MailManager;
use Fyre\Make\Make;
use Fyre\Middleware\MiddlewareQueue;
use Fyre\Middleware\MiddlewareRegistry;
use Fyre\Migration\MigrationRunner;
use Fyre\ORM\BehaviorRegistry;
use Fyre\ORM\ModelRegistry;
use Fyre\Queue\QueueManager;
use Fyre\Router\Middleware\RouterMiddleware;
use Fyre\Router\Middleware\SubstituteBindingsMiddleware;
use Fyre\Router\Router;
use Fyre\Schema\SchemaRegistry;
use Fyre\Security\ContentSecurityPolicy;
use Fyre\Security\CsrfProtection;
use Fyre\Security\Middleware\CspMiddleware;
use Fyre\Security\Middleware\CsrfProtectionMiddleware;
use Fyre\Server\ServerRequest;
use Fyre\Session\Session;
use Fyre\Utility\Formatter;
use Fyre\Utility\HtmlHelper;
use Fyre\Utility\Inflector;
use Fyre\Utility\Iterator;
use Fyre\Utility\Path;
use Fyre\Utility\Timer;
use Fyre\View\CellRegistry;
use Fyre\View\HelperRegistry;
use Fyre\View\TemplateLocator;

use function file_exists;

use const CONFIG;
use const LANG;
use const TEMPLATES;

/**
 * Engine
 */
class Engine extends Container
{
    use EventDispatcherTrait;

    /**
     * New Engine constructor.
     *
     * @param Loader $loader The Loader.
     */
    public function __construct(Loader $loader)
    {
        parent::__construct();

        $this->instance(Loader::class, $loader);

        $this
            ->singleton(Access::class)
            ->singleton(Auth::class)
            ->singleton(
                BehaviorRegistry::class,
                fn(): BehaviorRegistry => $this->build(BehaviorRegistry::class)
                    ->addNamespace('App\Models\Behaviors')
            )
            ->singleton(CacheManager::class)
            ->singleton(
                CellRegistry::class,
                fn(): CellRegistry => $this->build(CellRegistry::class)
                    ->addNamespace('App\Cells')
            )
            ->singleton(
                CommandRunner::class,
                fn(): CommandRunner => $this->build(CommandRunner::class)
                    ->addNamespace('App\Commands')
                    ->addNamespace('Fyre\Queue\Commands')
                    ->addNamespace('Fyre\Make\Commands')
                    ->addNamespace('Fyre\Migration\Commands')
            )
            ->singleton(
                Config::class,
                fn(): Config => $this->build(Config::class)
                    ->addPath(CONFIG)
                    ->addPath(Path::join(__DIR__, 'config'))
            )
            ->singleton(ConnectionManager::class)
            ->singleton(Console::class)
            ->singleton(ContentSecurityPolicy::class)
            ->singleton(CsrfProtection::class)
            ->singleton(EncryptionManager::class)
            ->singleton(
                EntityLocator::class,
                fn(): EntityLocator => $this->build(EntityLocator::class)
                    ->addNamespace('App\Entities')
            )
            ->singleton(ErrorHandler::class)
            ->singleton(
                EventManager::class,
                fn(): EventManager => $this->getEventManager()
            )
            ->singleton(ForgeRegistry::class)
            ->singleton(Formatter::class)
            ->singleton(FormBuilder::class)
            ->singleton(
                HelperRegistry::class,
                fn(): HelperRegistry => $this->build(HelperRegistry::class)
                    ->addNamespace('App\Helpers')
            )
            ->singleton(HtmlHelper::class)
            ->singleton(Identifier::class)
            ->singleton(Inflector::class)
            ->singleton(Iterator::class)
            ->singleton(
                Lang::class,
                fn(): Lang => $this->build(Lang::class)
                    ->addPath(LANG)
            )
            ->singleton(LogManager::class)
            ->singleton(MailManager::class)
            ->singleton(Make::class)
            ->singleton(
                MiddlewareQueue::class,
                function(): MiddlewareQueue {
                    $middleware = $this->middleware($this->build(MiddlewareQueue::class));

                    $this->dispatchEvent('Engine.buildMiddleware', ['middleware' => $middleware]);

                    return $middleware;
                }
            )
            ->singleton(
                MiddlewareRegistry::class,
                fn(): MiddlewareRegistry => $this->build(MiddlewareRegistry::class)
                    ->map('auth', AuthMiddleware::class)
                    ->map('authenticated', AuthenticatedMiddleware::class)
                    ->map('bindings', SubstituteBindingsMiddleware::class)
                    ->map('can', AuthorizedMiddleware::class)
                    ->map('csp', CspMiddleware::class)
                    ->map('csrf', CsrfProtectionMiddleware::class)
                    ->map('error', ErrorHandlerMiddleware::class)
                    ->map('router', RouterMiddleware::class)
                    ->map('unauthenticated', UnauthenticatedMiddleware::class)
            )
            ->singleton(
                MigrationRunner::class,
                fn(): MigrationRunner => $this->build(MigrationRunner::class)
                    ->addNamespace('App\Migrations')
            )
            ->singleton(
                ModelRegistry::class,
                fn(): ModelRegistry => $this->build(ModelRegistry::class)
                    ->addNamespace('App\Models')
            )
            ->singleton(
                PolicyRegistry::class,
                fn(): PolicyRegistry => $this->build(PolicyRegistry::class)
                    ->addNamespace('App\Policies')
            )
            ->singleton(QueueManager::class)
            ->singleton(Router::class, function(): Router {
                $router = $this->build(Router::class);
                $routesPath = Path::join(CONFIG, 'routes.php');

                if (file_exists($routesPath)) {
                    require $routesPath;
                }

                return $router;
            })
            ->singleton(SchemaRegistry::class)
            ->singleton(ServerRequest::class)
            ->singleton(Session::class)
            ->singleton(
                TemplateLocator::class,
                fn(): TemplateLocator => $this->build(TemplateLocator::class)
                    ->addPath(TEMPLATES)
            )
            ->singleton(Timer::class)
            ->singleton(TypeParser::class);
    }

    /**
     * Build application middleware.
     *
     * @param MiddlewareQueue $queue The MiddlewareQueue.
     * @return MiddlewareQueue The MiddlewareQueue.
     */
    public function middleware(MiddlewareQueue $queue): MiddlewareQueue
    {
        return $queue;
    }
}
