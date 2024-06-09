<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Config\Config;
use Fyre\Engine\Engine;
use Fyre\Lang\Lang;
use Fyre\Middleware\MiddlewareQueue;
use Fyre\Router\Router;
use PHPUnit\Framework\TestCase;

use function function_exists;

final class EngineTest extends TestCase
{

    public function testBootstrap(): void
    {
        $this->assertTrue(
            function_exists('test1')
        );

        $this->assertSame(
            'Test',
            Config::get('App.value')
        );
    }

    public function testLang(): void
    {
        $this->assertSame(
            'Test',
            Lang::get('Values.test')
        );
    }

    public function testMiddleware(): void
    {
        $queue = new MiddlewareQueue;

        $this->assertSame(
            $queue,
            Engine::middleware($queue)
        );
    }

    public function testRoutes(): void
    {
        $this->assertSame(
            'https://test.com/',
            Router::getBaseUri()
        );
    }

}
