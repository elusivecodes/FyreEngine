# FyreEngine

**FyreEngine** is a free, open-source engine library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Basic Usage](#basic-usage)
- [Methods](#methods)
- [Attributes](#attributes)
- [Global Functions](#global-functions)



## Installation

**Using Composer**

```
composer require fyre/engine
```

In PHP:

```php
use Fyre\Engine\Engine;
```


## Basic Usage

- `$loader` is a [*Loader*](https://github.com/elusivecodes/FyreLoader).

```php
$engine = new Engine($loader);
```


## Methods

This class extends the [*Container*](https://github.com/elusivecodes/FyreContainer) class.

**Boot**

Boot the application.

```php
$engine->boot();
```

**Middleware**

Build application [middleware](https://github.com/elusivecodes/FyreMiddleware).

- `$queue` is a [*MiddlewareQueue*](https://github.com/elusivecodes/FyreMiddleware#middleware-queues).

```php
$middleware = $engine->middleware($queue);
```


## Attributes

Attributes can be used to provide context when resolving values from the [*Container*](https://github.com/elusivecodes/FyreContainer).

**Cache**

Load a shared [*Cacher*](https://github.com/elusivecodes/FyreCache#cachers) instance.

```php
use Fyre\Cache\Cacher;
use Fyre\Engine\Attributes\Cache;

$engine->call(function(#[Cache] Cacher $cache): void { });
$engine->call(function(#[Cache('default')] Cacher $cache): void { });
```

**Config**

Retrieve a value from the config using "dot" notation.

```php
use Fyre\Engine\Attributes\Config;

$engine->call(function(#[Config('App.value')] string $value): void { });
```

**Current User**

Get the current user.

```php
use Fyre\Engine\Attributes\CurrentUser;
use Fyre\Entity\Entity;

$engine->call(function(#[CurrentUser] Entity|null $user): void { });
```

**DB**

Load a shared [*Connection*](https://github.com/elusivecodes/FyreDB#connections) instance.

```php
use Fyre\DB\Connection;
use Fyre\Engine\Attributes\DB;

$engine->call(function(#[DB] Connection $connection): void { });
$engine->call(function(#[DB('default')] Connection $connection): void { });
```

**Encryption**

Load a shared [*Encrypter*](https://github.com/elusivecodes/FyreEncryption#encrypters) instance.

```php
use Fyre\Encryption\Encrypter;
use Fyre\Engine\Attributes\Encryption;

$engine->call(function(#[Encryption] Encrypter $encrypter): void { });
$engine->call(function(#[Encryption('default')] Encrypter $encrypter): void { });
```

**Log**

Load a shared [*Logger*](https://github.com/elusivecodes/FyreLog#loggers) instance.

```php
use Fyre\Log\Logger;
use Fyre\Engine\Attributes\Log;

$engine->call(function(#[Log] Logger $logger): void { });
$engine->call(function(#[Log('default')] Logger $logger): void { });
```

**Mail**

Load a shared [*Mailer*](https://github.com/elusivecodes/FyreMail#mailers) instance.

```php
use Fyre\Mail\Mailer;
use Fyre\Engine\Attributes\Mail;

$engine->call(function(#[Mail] Mailer $mailer): void { });
$engine->call(function(#[Mail('default')] Mailer $mailer): void { });
```

**ORM**

Load a shared [*Model*](https://github.com/elusivecodes/FyreORM#models) instance.

```php
use Fyre\ORM\Model;
use Fyre\Engine\Attributes\ORM;

$engine->call(function(#[ORM] Model $model): void { });
$engine->call(function(#[ORM('default')] Model $model): void { });
```

**Route Argument**

Retrieve an argument from the loaded route.

```php
use Fyre\Engine\Attributes\RouteArgument;

$engine->call(function(#[RouteArgument('id')] int $id): void { });
```


## Global Functions

**__**

Get a language value.

- `$key` is a string representing the key to lookup.
- `$data` is an array containing data to insert into the language string.

```php
$lang = __($key, $data);
```

**Abort**

Throw an [*Exception*](https://github.com/elusivecodes/FyreError).

- `$code` is a number representing the status code, and will default to *500*.
- `$message` is a string representing the error message, and will default to *""*.

```php
abort($code, $message);
```

**App**

Load a shared *Engine* instance.

```php
$app = app();
```

You can also load other shared class instances.

- `$alias` is a string representing the alias.
- `$arguments` is an array containing the named arguments for the class constructor.

```php
$instance = app($alias, $arguments);
```

**Asset**

Generate a URL for an asset path.

- `$path` is a string representing the asset path.
- `$options` is an array containing the route options.
    - `fullBase` is a boolean indicating whether to use the full base URI and will default to *false*.

```php
$url = asset($path);
```

**Auth**

Load a shared [*Auth*](https://github.com/elusivecodes/FyreAuth) instance.

```php
$auth = auth();
```

**Authorize**

Authorize an access rule.

- `$rule` is a string representing the access rule name or [*Policy*](https://github.com/elusivecodes/FyreAuth#policies) method.

Any additional arguments supplied will be passed to the access rule callback or [*Policy*](https://github.com/elusivecodes/FyreAuth#policies) method.

```php
authorize($rule, ...$args);
```

**Cache**

Load a shared [*Cacher*](https://github.com/elusivecodes/FyreCache) instance.

- `$key` is a string representing the *Cacher* key, and will default to `Cache::DEFAULT`.

```php
$cacher = cache($key);
```

**Can**

Check whether an access rule is allowed.

- `$rule` is a string representing the access rule name or [*Policy*](https://github.com/elusivecodes/FyreAuth#policies) method.

Any additional arguments supplied will be passed to the access rule callback or [*Policy*](https://github.com/elusivecodes/FyreAuth#policies) method.

```php
$result = can($rule, ...$args);
```

**Can Any**

Check whether any access rule is allowed.

- `$rules` is an array containing access rule names or [*Policy*](https://github.com/elusivecodes/FyreAuth#policies) methods.

Any additional arguments supplied will be passed to the access rule callbacks or [*Policy*](https://github.com/elusivecodes/FyreAuth#policies) methods.

```php
$result = can_any($rules, ...$args);
```

**Can None**

Check whether no access rule is allowed.

- `$rules` is an array containing access rule names or [*Policy*](https://github.com/elusivecodes/FyreAuth#policies) methods.

Any additional arguments supplied will be passed to the access rule callbacks or [*Policy*](https://github.com/elusivecodes/FyreAuth#policies) methods.

```php
$result = can_none($rule, ...$args);
```

**Cannot**

Check whether an access rule is not allowed.

- `$rule` is a string representing the access rule name or [*Policy*](https://github.com/elusivecodes/FyreAuth#policies) method.

Any additional arguments supplied will be passed to the access rule callback or [*Policy*](https://github.com/elusivecodes/FyreAuth#policies) method.

```php
$result = cannot($rule, ...$args);
```

**Collect**

Create a new [*Collection*](https://github.com/elusivecodes/FyreCollection).

- `$source` can be either an array, a *Closure* that returns a *Generator*, or a *Traversable* or *JsonSerializable* object.

```php
$collection = collect($source);
```

**Config**

Load a shared [*Config*](https://github.com/elusivecodes/FyreConfig) instance.

```php
$config = config();
```

You can also retrieve a value from the config using "dot" notation.

- `$key` is a string representing the key to lookup.
- `$default` is the default value to return, and will default to *null*.

```php
$value = config($key, $default);
```

**DB**

Load a shared [*Connection*](https://github.com/elusivecodes/FyreDB#connections) instance.

- `$key` is a string representing the [*Connection*](https://github.com/elusivecodes/FyreDB#connections) key, and will default to `ConnectionManager::DEFAULT`.

```php
$connection = db($key);
```

**DD**

Dump and die.

```php
dd(...$data);
```

**Dump**

Dump data.

```php
dump(...$data);
```

**Email**

Create an [*Email*](https://github.com/elusivecodes/FyreMail#emails).

- `$key` is a string representing the [*Mailer*](https://github.com/elusivecodes/FyreMail#mailers) key, and will default to *Mail::DEFAULT*.

```php
$email = email($key);
```

**Encryption**

Load a shared [*Encrypter*](https://github.com/elusivecodes/FyreEncryption#encrypters) instance.

- `$key` is a string representing the *Encrypter* key, and will default to `Encryption::DEFAULT`.

```php
$encrypter = encryption($key);
```

**Env**

Retrieve an environment variable.

- `$name` is a string representing the variable name.
- `$default` is the default value to return, and will default to *null*.

```php
$value = env($name, $default);
```

**Escape**

Escape characters in a string for use in HTML.

- `$string` is the string to escape.

```php
$escaped = escape($string);
```

**Json**

Create a new [*ClientResponse*](https://github.com/elusivecodes/FyreServer#client-responses) with JSON data.

- `$data` is the data to send.

```php
$response = json($data);
```

**Log Message**

Log a message.

- `$type` is a string representing the log level.
- `$message` is a string representing the log message.
- `$data` is an array containing data to insert into the message string.

```php
log_message($type, $message, $data);
```

The `$type` must be one of the supported [log levels](https://github.com/elusivecodes/FyreLog#logging).

**Logged In**

Determine if the current user is logged in.

```php
$loggedIn = logged_in();
```

**Model**

Load a shared [*Model*](https://github.com/elusivecodes/FyreORM#models) instance.

- `$alias` is a string representing the model alias.

```php
$model = model($alias);
```

**Now**

Create a new [*DateTime*](https://github.com/elusivecodes/FyreDateTime) set to now.

```php
$now = now();
```

**Queue**

Push a job to a [*Queue*](https://github.com/elusivecodes/FyreQueue#queues).

- `$className` is a string representing the job class.
- `$arguments` is an array containing arguments that will be passed to the job.
- `$options` is an array containing options for the [*Message*](#messages).
    - `config` is a string representing the configuration key, and will default to "*default*".
    - `queue` is a string representing the [*Queue*](#queues) name, and will default to "*default*".
    - `method` is a string representing the class method, and will default to "*run*".
    - `delay` is a number representing the number of seconds before the job should run, and will default to *0*.
    - `expires` is a number representing the number of seconds after which the job will expire, and will default to *0*.

```php
queue($className, $arguments, $options);
```

**Redirect**

Create a new [*RedirectResponse*](https://github.com/elusivecodes/FyreServer#redirect-responses).

- `$uri` is a [*Uri*](https://github.com/elusivecodes/FyreURI) or string representing the URI to redirect to.
- `$code` is a number representing the header status code, and will default to *302*.
- `$options` is an array containing configuration options.

```php
$response = redirect($uri, $code, $options);
```

**Request**

Load a shared [*ServerRequest*](https://github.com/elusivecodes/FyreServer#server-requests) instance.

```php
$request = request();
```

You can also retrieve value from the `$_POST` array by passing arguments to this function.

- `$key` is a string representing the array key using "dot" notation.
- `$as` is a string representing the value type, and will default to *null*.

```php
$value = request($key, $as);
```

**Response**

Create a new [*ClientResponse*](https://github.com/elusivecodes/FyreServer#client-responses).

```php
$response = response();
```

**Route**

Generate a URL for a named [*Route*](https://github.com/elusivecodes/FyreRouter).

- `$name` is a string representing the route alias.
- `$arguments` is an array containing the route arguments.
    - `?` is an array containing route query parameters.
    - `#` is a string representing the fragment component of the URI.
- `$options` is an array containing the route options.
    - `fullBase` is a boolean indicating whether to use the full base URI and will default to *false*.

```php
$route = route($name, $arguments, $options);
```

**Session**

Load a shared [*Session*](https://github.com/elusivecodes/FyreSession) instance.

```php
$session = session();
```

Retrieve a value from the session.

- `$key` is a string representing the session key.

```php
$value = session($key);
```

You can also set a session value by including a second argument.

```php
session($key, $value);
```

**Type**

Load a shared [*TypeParser*](https://github.com/elusivecodes/FyreTypeParser) instance.

```php
$parser = type();
```

You can also get the mapped [*Type*](https://github.com/elusivecodes/FyreTypeParser#types) class for a value type.

- `$type` is a string representing the value type.

```php
$typeClass = type($type);
```

**User**

Get the current user.

```php
$user = user();
```

**View**

Render a [*View*](https://github.com/elusivecodes/FyreView) template.

- `$template` is a string representing the template file.
- `$data` is an array containing data to pass to the template.
- `$layout` is a string representing the layout file, and will default to *null*.

```php
$view = view($template, $data, $layout);
```

If the `$layout` is set to null, it will use the `App.defaultLayout` option from the *Config*.