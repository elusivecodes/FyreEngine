<?php
declare(strict_types=1);

use Fyre\Auth\Access;
use Fyre\Auth\Auth;
use Fyre\Engine\Engine;
use Fyre\Entity\Entity;

define('CONFIG', 'tests/Mock/config');
define('LANG', 'tests/Mock/language');
define('TEMPLATES', 'tests/Mock/templates');

Engine::bootstrap();
Engine::routes();

$user = new Entity(['id' => 1]);
Auth::instance()->login($user);

Access::define('fail', fn(): bool => false);
Access::define('test', fn(Entity|null $user): bool => (bool) $user);
