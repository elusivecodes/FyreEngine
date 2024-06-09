<?php

use Fyre\Config\Config;
use Fyre\Router\Router;
use Tests\Mock\Controller\TestController;

Router::setBaseUri(Config::get('App.baseUri', ''));

Router::get('test', TestController::class, ['as' => 'test']);
Router::get('test/(:segment)', TestController::class, ['as' => 'test2']);
