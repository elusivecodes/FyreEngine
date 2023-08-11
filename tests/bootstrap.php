<?php
declare(strict_types=1);

use Fyre\Engine\Engine;

define('CONFIG', 'tests/Mock/config');
define('LANG', 'tests/Mock/language');
define('TEMPLATES', 'tests/Mock/templates');

Engine::bootstrap();
Engine::routes();
