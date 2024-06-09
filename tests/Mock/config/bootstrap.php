<?php

use Fyre\Cache\Cache;
use Fyre\Cache\Handlers\NullCacher;
use Fyre\Config\Config;
use Fyre\Session\Session;
use Tests\Mock\MockSessionHandler;

Config::load('app');

Cache::setConfig('default', [
    'className' => NullCacher::class
]);

Cache::setConfig('null', [
    'className' => NullCacher::class
]);

Session::register([
    'className' => MockSessionHandler::class
]);
