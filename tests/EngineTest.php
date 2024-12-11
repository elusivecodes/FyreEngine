<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Config\Config;
use Fyre\Engine\Engine;
use Fyre\Lang\Lang;
use Fyre\Loader\Loader;
use Fyre\Router\Router;
use PHPUnit\Framework\TestCase;

final class EngineTest extends TestCase
{
    protected Engine $app;

    public function testBootstrap(): void
    {
        $this->assertSame(
            'Test',
            $this->app->use(Config::class)->get('App.value')
        );
    }

    public function testLang(): void
    {
        $this->assertSame(
            'Test',
            $this->app->use(Lang::class)->get('Values.test')
        );
    }

    public function testRoutes(): void
    {
        $this->assertSame(
            'https://test.com/',
            $this->app->use(Router::class)->getBaseUri()
        );
    }

    protected function setUp(): void
    {
        $loader = new Loader();
        $this->app = new Engine($loader);

        Engine::setInstance($this->app);

        $this->app->use(Config::class)
            ->load('functions')
            ->load('app');
    }
}
