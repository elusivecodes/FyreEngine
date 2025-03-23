<?php
declare(strict_types=1);

use Fyre\Auth\Auth;
use Fyre\Cache\CacheManager;
use Fyre\Cache\Cacher;
use Fyre\Collection\Collection;
use Fyre\Config\Config;
use Fyre\DateTime\DateTime;
use Fyre\DB\Connection;
use Fyre\DB\ConnectionManager;
use Fyre\DB\TypeParser;
use Fyre\DB\Types\Type;
use Fyre\Encryption\Encrypter;
use Fyre\Encryption\EncryptionManager;
use Fyre\Engine\Engine;
use Fyre\Entity\Entity;
use Fyre\Error\Exceptions\BadRequestException;
use Fyre\Error\Exceptions\ConflictException;
use Fyre\Error\Exceptions\Exception;
use Fyre\Error\Exceptions\ForbiddenException;
use Fyre\Error\Exceptions\GoneException;
use Fyre\Error\Exceptions\InternalServerException;
use Fyre\Error\Exceptions\MethodNotAllowedException;
use Fyre\Error\Exceptions\NotAcceptableException;
use Fyre\Error\Exceptions\NotFoundException;
use Fyre\Error\Exceptions\NotImplementedException;
use Fyre\Error\Exceptions\ServiceUnavailableException;
use Fyre\Error\Exceptions\UnauthorizedException;
use Fyre\Http\Uri;
use Fyre\Lang\Lang;
use Fyre\Log\LogManager;
use Fyre\Mail\Email;
use Fyre\Mail\MailManager;
use Fyre\ORM\Model;
use Fyre\ORM\ModelRegistry;
use Fyre\Queue\QueueManager;
use Fyre\Router\Router;
use Fyre\Server\ClientResponse;
use Fyre\Server\RedirectResponse;
use Fyre\Server\ServerRequest;
use Fyre\Session\Session;
use Fyre\Utility\HtmlHelper;
use Fyre\View\View;

if (!function_exists('__')) {
    /**
     * Get a language value.
     *
     * @param string $key The language key.
     * @param array $data The data to insert.
     * @return array|string|null The formatted language string.
     */
    function __(string $key, array $data = []): array|string|null
    {
        return app(Lang::class)->get($key, $data);
    }
}

if (!function_exists('abort')) {
    /**
     * Throw an Exception.
     *
     * @param int $code The status code.
     * @param string $message The error message.
     *
     * @throws Exception The Exception.
     */
    function abort(int $code = 500, string|null $message = null): void
    {
        throw match ($code) {
            400 => new BadRequestException($message),
            401 => new UnauthorizedException($message),
            403 => new ForbiddenException($message),
            404 => new NotFoundException($message),
            405 => new MethodNotAllowedException($message),
            406 => new NotAcceptableException($message),
            409 => new ConflictException($message),
            410 => new GoneException($message),
            501 => new NotImplementedException($message),
            503 => new ServiceUnavailableException($message),
            default => new InternalServerException($message, $code)
        };
    }
}

if (!function_exists('app')) {
    /**
     * Load a shared App instance.
     *
     * @param string|null $alias The class alias.
     * @param array $arguments The constructor arguments.
     * @return object The Engine or instance.
     */
    function app(string|null $alias = null, array $arguments = []): object
    {
        $app = Engine::getInstance();

        if ($alias === null) {
            return $app;
        }

        return $app->use($alias, $arguments);
    }
}

if (!function_exists('asset')) {
    /**
     * Generate a URL for an asset path.
     *
     * @param string $path The asset path.
     * @param array $options The path options.
     * @return string The URL.
     */
    function asset(string $path, array $options = []): string
    {
        $options['fullBase'] ??= false;

        if ($options['fullBase']) {
            return Uri::fromString(config('App.baseUri'))
                ->resolveRelativeUri($path)
                ->getUri();
        }

        return Uri::fromString($path)->getUri();
    }
}

if (!function_exists('auth')) {
    /**
     * Load a shared Auth instance.
     *
     * @return Auth The Auth.
     */
    function auth(): Auth
    {
        return app(Auth::class);
    }
}

if (!function_exists('authorize')) {
    /**
     * Authorize an access rule.
     *
     * @param string $rule The access rule name.
     * @param mixed ...$args Additional arguments for the access rule.
     *
     * @throws ForbiddenException if access was not authorized.
     */
    function authorize(string $rule, mixed ...$args): void
    {
        auth()
            ->access()
            ->authorize($rule, ...$args);
    }
}

if (!function_exists('cache')) {
    /**
     * Load a shared cache instance.
     *
     * @param string $key The config key.
     * @return Cacher The cache handler.
     */
    function cache(string $key = CacheManager::DEFAULT): Cacher
    {
        return app(CacheManager::class)->use($key);
    }
}

if (!function_exists('can')) {
    /**
     * Check whether an access rule is allowed.
     *
     * @param string $rule The access rule name.
     * @param mixed ...$args Additional arguments for the access rule.
     * @return bool TRUE if the access rule was allowed, otherwise FALSE.
     */
    function can(string $rule, mixed ...$args): bool
    {
        return auth()
            ->access()
            ->allows($rule, ...$args);
    }
}

if (!function_exists('can_any')) {
    /**
     * Check whether any access rule is allowed.
     *
     * @param array $rules The access rule names.
     * @param mixed ...$args Additional arguments for the access rule.
     * @return bool TRUE if any access rule was allowed, otherwise FALSE.
     */
    function can_any(array $rules, mixed ...$args): bool
    {
        return auth()
            ->access()
            ->any($rules, ...$args);
    }
}

if (!function_exists('can_none')) {
    /**
     * Check whether no access rule is allowed.
     *
     * @param array $rules The access rule names.
     * @param mixed ...$args Additional arguments for the access rule.
     * @return bool TRUE if no access rule was allowed, otherwise FALSE.
     */
    function can_none(array $rules, mixed ...$args): bool
    {
        return auth()
            ->access()
            ->none($rules, ...$args);
    }
}

if (!function_exists('cannot')) {
    /**
     * Check whether an access rule is not allowed.
     *
     * @param string $rule The access rule name.
     * @param mixed ...$args Additional arguments for the access rule.
     * @return bool TRUE if the access rule was not allowed, otherwise FALSE.
     */
    function cannot(string $rule, mixed ...$args): bool
    {
        return auth()
            ->access()
            ->denies($rule, ...$args);
    }
}

if (!function_exists('collect')) {
    /**
     * Create a new Collection
     *
     * @param array|Closure|JsonSerializable|Traversable|null $source The source.
     */
    function collect(array|Closure|JsonSerializable|Traversable|null $source): Collection
    {
        return new Collection($source);
    }
}

if (!function_exists('config')) {
    /**
     * Retrieve a value from the config using "dot" notation.
     *
     * @param string|null $key The config key.
     * @param mixed $default The default value.
     * @return mixed The config value.
     */
    function config(string|null $key = null, $default = null): mixed
    {
        $config = app()->use(Config::class);

        if ($key === null) {
            return $config;
        }

        return $config->get($key, $default);
    }
}

if (!function_exists('db')) {
    /**
     * Load a shared handler instance.
     *
     * @param string $key The config key.
     * @return Connection The handler.
     */
    function db(string $key = ConnectionManager::DEFAULT): Connection
    {
        return app(ConnectionManager::class)->use($key);
    }
}

if (!function_exists('dd')) {
    /**
     * Dump data and die.
     *
     * @param mixed ...$data The data to dump.
     */
    function dd(mixed ...$data): void
    {
        dump(...$data);
        exit();
    }
}

if (!function_exists('dump')) {
    /**
     * Dump data.
     *
     * @param mixed ...$data The data to dump.
     */
    function dump(mixed ...$data): void
    {
        foreach ($data as $item) {
            if (PHP_SAPI !== 'cli') {
                echo '<pre>';
            }

            var_dump($item);

            if (PHP_SAPI !== 'cli') {
                echo '</pre>';
            }
        }
    }
}

if (!function_exists('element')) {
    /**
     * Render an element.
     *
     * @param string $file The element file.
     * @param array $data The view data.
     * @return string The rendered element.
     */
    function element(string $file, array $data = []): string
    {
        return app(View::class)
            ->element($file, $data);
    }
}

if (!function_exists('email')) {
    /**
     * Create a new Email for a Mailer.
     */
    function email(string $key = MailManager::DEFAULT): Email
    {
        return app(MailManager::class)->use($key)->email();
    }
}

if (!function_exists('encryption')) {
    /**
     * Load a shared encryption instance.
     *
     * @param string $key The config key.
     * @return Encrypter The encryption handler.
     */
    function encryption(string $key = EncryptionManager::DEFAULT): Encrypter
    {
        return app(EncryptionManager::class)->use($key);
    }
}

if (!function_exists('env')) {
    /**
     * Retrieve an environment variable.
     *
     * @param string $name The variable name.
     * @param mixed $default The default value.
     * @return mixed The variable value.
     */
    function env(string $name, $default = null): mixed
    {
        $value = getenv($name);

        if ($value === false || $value === '') {
            return $default;
        }

        return $value;
    }
}

if (!function_exists('escape')) {
    /**
     * Escape characters in a string for use in HTML.
     *
     * @param string $string The input string.
     * @return string The escaped string.
     */
    function escape(string $string): string
    {
        return app(HtmlHelper::class)->escape($string);
    }
}

if (!function_exists('json')) {
    /**
     * Create a new ClientResponse with JSON data.
     *
     * @param mixed $data The data to send.
     * @return ClientResponse A new ClientResponse.
     */
    function json(mixed $data): ClientResponse
    {
        return response()->setJson($data);
    }
}

if (!function_exists('log_message')) {
    /**
     * Log a message.
     *
     * @param string $type The log type.
     * @param string $message The log message.
     * @param array $data Additional data to interpolate.
     */
    function log_message(string $type, string $message, array $data = []): void
    {
        app(LogManager::class)->handle($type, $message, $data);
    }
}

if (!function_exists('logged_in')) {
    /**
     * Determine if the current user is logged in.
     *
     * @return bool TRUE if the current user is logged in, otherwise FALSE.
     */
    function logged_in(): bool
    {
        return auth()->isLoggedIn();
    }
}

if (!function_exists('model')) {
    /**
     * Load a shared Model instance.
     *
     * @param string $alias The model alias.
     * @return Model The Model.
     */
    function model(string $alias): Model
    {
        return app(ModelRegistry::class)->use($alias);
    }
}

if (!function_exists('now')) {
    /**
     * Create a new DateTime set to now.
     *
     * @return DateTime The DateTime.
     */
    function now(): DateTime
    {
        return DateTime::now();
    }
}

if (!function_exists('queue')) {
    /**
     * Push a job to the queue.
     *
     * @param string $className The job class.
     * @param array $arguments The job arguments.
     * @param array $options The job options.
     */
    function queue(string $className, array $arguments = [], array $options = []): void
    {
        app(QueueManager::class)->push($className, $arguments, $options);
    }
}

if (!function_exists('redirect')) {
    /**
     * Create a new RedirectResponse.
     *
     * @return RedirectResponse The RedirectResponse.
     */
    function redirect(string|Uri $uri, int $code = 302, array $options = []): RedirectResponse
    {
        return app(RedirectResponse::class, [
            'uri' => $uri,
            'code' => $code,
            'options' => $options,
        ]);
    }
}

if (!function_exists('request')) {
    /**
     * Load a shared ServerRequest instance.
     *
     * @param string|null $key The key.
     * @param string|null $as The type.
     * @return mixed The ServerRequest or the post value.
     */
    function request(string|null $key = null, string|null $as = null): mixed
    {
        $request = app(ServerRequest::class);

        if (func_num_args() === 0) {
            return $request;
        }

        return $request->getData($key, $as);
    }
}

if (!function_exists('response')) {
    /**
     * Create a new ClientResponse.
     *
     * @return ClientResponse The ClientResponse.
     */
    function response(): ClientResponse
    {
        return app(ClientResponse::class);
    }
}

if (!function_exists('route')) {
    /**
     * Generate a URL for a named route.
     *
     * @param array $arguments The route arguments
     * @param array $options The route options.
     * @param string $name The name.
     * @return string The URL.
     */
    function route(string $alias, array $arguments = [], array $options = []): string
    {
        return app(Router::class)
            ->url($alias, $arguments, $options);
    }
}

if (!function_exists('session')) {
    /**
     * Get or set a session value.
     *
     * @param string|null $key The session key.
     * @param mixed $value The session value.
     * @return mixed The session value.
     */
    function session(string|null $key = null, mixed $value = null): mixed
    {
        $session = app(Session::class);

        if ($key === null) {
            return $session;
        }

        if (func_num_args() === 1) {
            return $session->get($key);
        }

        return $session->set($key, $value);
    }
}

if (!function_exists('type')) {
    /**
     * Get a Type class for a value type.
     *
     * @param string|null $type The value type.
     * @return Type|TypeParser The TypeParser or Type.
     */
    function type(string|null $type = null): Type|TypeParser
    {
        $typeParser = app(TypeParser::class);

        if ($type === null) {
            return $typeParser;
        }

        return $typeParser->use($type);
    }
}

if (!function_exists('user')) {
    /**
     * Get the current user.
     *
     * @return Entity|null The current user.
     */
    function user(): Entity|null
    {
        return auth()->user();
    }
}

if (!function_exists('view')) {
    /**
     * Render a view template.
     *
     * @param array $data The view data.
     * @param string|null $layout The layout.
     * @param string $file The template file.
     * @return string The rendered template.
     */
    function view(string $template, array $data = [], string|null $layout = null): string
    {
        return app(View::class)
            ->setData($data)
            ->setLayout($layout ?? config('App.defaultLayout'))
            ->render($template);
    }
}
