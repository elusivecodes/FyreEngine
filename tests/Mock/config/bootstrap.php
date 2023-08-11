<?php

use Fyre\Config\Config;
use Fyre\Router\Router;

Config::load('app');

Router::setBaseUri(Config::get('App.baseUri', ''));
