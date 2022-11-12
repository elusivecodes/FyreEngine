<?php
declare(strict_types=1);

namespace Tests;

use
    Fyre\Config\Config,
    Fyre\Engine\Engine,
    Fyre\Lang\Lang,
    Fyre\Middleware\MiddlewareQueue,
    Fyre\Router\Router,
    Fyre\Server\ClientResponse,
    Fyre\Server\ServerRequest,
    Fyre\View\View,
    PHPUnit\Framework\TestCase,
    Tests\Mock\MockController;

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
            Config::get('value3')
        );

        $this->assertSame(
            'Test',
            Lang::get('Test.test')
        );

        $request = new ServerRequest();
        $response = new ClientResponse();
        $controller = new MockController($request, $response);
        $view = new View($controller);

        $this->assertSame(
            'Test',
            $view->render('test/template')
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
