<?php
declare(strict_types=1);

use Fyre\Cache\Cache;
use Fyre\Cache\Cacher;
use Fyre\Config\Config;
use Fyre\DateTime\DateTime;
use Fyre\Encryption\Encrypter;
use Fyre\Encryption\Encryption;
use Fyre\Error\Exceptions\Exception;
use Fyre\Http\Uri;
use Fyre\Lang\Lang;
use Fyre\ORM\Model;
use Fyre\ORM\ModelRegistry;
use Fyre\Router\Router;
use Fyre\Server\ServerRequest;
use Fyre\Session\Session;
use Fyre\Utility\HtmlHelper;
use Fyre\View\View;

if (!function_exists('__')) {
    /**
     * Get a language value.
     * @param string $key The language key.
     * @param array $data The data to insert.
     * @return string|array|null The formatted language string.
     */
    function __(string $key, array $data = []): string|array|null
    {
        return Lang::get($key, $data);
    }
}


if (!function_exists('abort')) {
    /**
     * Throw an Exception.
     * @param int $code The status code.
     * @param string $message The error message.
     * @throws Exception The Exception.
     */
    function abort(int $code = 500, string $message = ''): void
    {
        throw new Exception($message, $code);
    }
}

if (!function_exists('asset')) {
    /**
     * Generate a URL for an asset path.
     * @param string $path The asset path.
     * @param array $options The path options.
     * @return string The URL.
     */
    function asset(string $path, array $options = []): string
    {
        $options['fullBase'] ??= false;

        if ($options['fullBase']) {
            return Uri::fromString(Config::get('App.baseUri'))
                ->resolveRelativeUri($path)
                ->getUri();
        }

        return Uri::fromString($path)
            ->getUri();
    }
}

if (!function_exists('cache')) {
    /**
     * Load a shared cache instance.
     * @param string $key The config key.
     * @return Cacher The cache handler.
     */
    function cache(string $key = Cache::DEFAULT): Cacher
    {
        return Cache::use($key);
    }
}

if (!function_exists('config')) {
    /**
     * Retrieve a value from the config using "dot" notation.
     * @param string $key The config key.
     * @param mixed $default The default value.
     * @return mixed The config value.
     */
    function config(string $key, $default = null): mixed
    {
        return Config::get($key, $default);
    }
}

if (!function_exists('dd')) {
    /**
     * Dump data and die.
     * @param mixed ...$data The data to dump.
     */
    function dd(mixed ...$data): void
    {
        dump(...$data);
        die();
    }
}

if (!function_exists('dump')) {
    /**
     * Dump data.
     * @param mixed ...$data The data to dump.
     */
    function dump(mixed ...$data): void
    {
        foreach ($data AS $item) {
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

if (!function_exists('encryption')) {
    /**
     * Load a shared encryption instance.
     * @param string $key The config key.
     * @return Encrypter The encryption handler.
     */
    function encryption(string $key = Encryption::DEFAULT): Encrypter
    {
        return Encryption::use($key);
    }
}

if (!function_exists('escape')) {
    /**
     * Escape characters in a string for use in HTML.
     * @param string $string The input string.
     * @return string The escaped string.
     */
    function escape(string $string): string
    {
        return HtmlHelper::escape($string);
    }
}

if (!function_exists('model')) {
    /**
     * Load a shared Model instance.
     * @param string $alias The model alias.
     * @return Model The Model.
     */
    function model(string $alias): Model
    {
        return ModelRegistry::use($alias);
    }
}

if (!function_exists('now')) {
    /**
     * Create a new DateTime set to now.
     * @return DateTime The DateTime.
     */
    function now(): DateTime
    {
        return DateTime::now();
    }
}

if (!function_exists('request')) {
    /**
     * Load a shared ServerRequest instance.
     * @return ServerRequest The ServerRequest.
     */
    function request(): ServerRequest
    {
        return ServerRequest::instance();
    }
}

if (!function_exists('route')) {
    /**
     * Generate a URL for a named route.
     * @param string $name The name.
     * @param array $arguments The route arguments
     * @param array $options The route options.
     * @return string The URL.
     */
    function route(string $alias, array $arguments = [], array $options = []): string
    {
        return Router::url($alias, $arguments, $options);
    }
}

if (!function_exists('session')) {
    /**
     * Get or set a session value.
     * @param string $key The session key.
     * @param mixed $value The session value.
     * @return mixed The session value.
     */
    function session(string $key, mixed $value = null): mixed
    {
        if (func_num_args() === 2) {
            Session::set($key, $value);
        }

        return Session::get($key);
    }
}

if (!function_exists('view')) {
    /**
     * Render a view template.
     * @param string $file The template file.
     * @param array $data The view data.
     * @param string|null $layout The layout.
     * @return string The rendered template.
     */
    function view(string $template, array $data = [], string|null $layout = null): string
    {
        return (new View(ServerRequest::instance()))
            ->setData($data)
            ->setLayout($layout ?? Config::get('App.defaultLayout'))
            ->render($template);
    }
}
