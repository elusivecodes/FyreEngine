# FyreEngine

**FyreEngine** is a free, engine library for *PHP*.


## Table Of Contents
- [Installation](#installation)
- [Methods](#methods)



## Installation

**Using Composer**

```
composer require fyre/engine
```

In PHP:

```php
use Fyre\Engine\Engine;
```


## Methods

**Bootstrap**

Bootstrap application.

```php
Engine::bootstrap();
```

**Middleware**

Build application [middleware](https://github.com/elusivecodes/FyreMiddleware).

- `$queue` is a [*MiddlewareQueue*](https://github.com/elusivecodes/FyreMiddleware#middleware-queues).

```php
Engine::middleware($queue);
```

**Routes**

Build application [routes](https://github.com/elusivecodes/FyreRouter).

```php
Engine::routes();
```