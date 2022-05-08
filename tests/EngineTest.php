<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Config\Config,
    Fyre\Engine\Engine,
    Fyre\Lang\Lang,
    Fyre\Middleware\MiddlewareQueue,
    Fyre\Router\Router,
    Fyre\View\View,
    PHPUnit\Framework\TestCase;

final class EngineTest extends TestCase
{

    public function testBootstrap(): void
    {
        $this->assertSame(
            'Test',
            Config::get('value1')
        );

        $this->assertSame(
            'Test',
            Config::get('value2')
        );

        $this->assertSame(
            'Test',
            Lang::get('Test.test')
        );

        $this->assertSame(
            'Test',
            (new View)->render('test/template')
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

        $this->assertSame(
            'Test',
            Config::get('value3')
        );
    }

}
