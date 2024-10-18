<?php
declare(strict_types=1);

namespace Tests;

use Fyre\Auth\Auth;
use Fyre\Cache\Cache;
use Fyre\Collection\Collection;
use Fyre\DateTime\DateTime;
use Fyre\DB\ConnectionManager;
use Fyre\DB\Handlers\Sqlite\SqliteConnection;
use Fyre\DB\Types\DateTimeType;
use Fyre\Encryption\Encryption;
use Fyre\Entity\Entity;
use Fyre\Error\Exceptions\ForbiddenException;
use Fyre\Error\Exceptions\GoneException;
use Fyre\Error\Exceptions\InternalServerException;
use Fyre\Error\Exceptions\NotFoundException;
use Fyre\Mail\Email;
use Fyre\ORM\Model;
use Fyre\Server\ClientResponse;
use Fyre\Server\RedirectResponse;
use Fyre\Server\ServerRequest;
use PHPUnit\Framework\TestCase;

use function __;
use function abort;
use function asset;
use function auth;
use function authorize;
use function cache;
use function can;
use function can_any;
use function can_none;
use function cannot;
use function config;
use function encryption;
use function escape;
use function json;
use function logged_in;
use function model;
use function now;
use function redirect;
use function request;
use function route;
use function session;
use function type;
use function user;
use function view;

use const PHP_EOL;

final class FunctionsTest extends TestCase
{
    public function testAbort(): void
    {
        $this->expectException(InternalServerException::class);

        abort();
    }

    public function testAbortCode(): void
    {
        $this->expectException(GoneException::class);

        abort(410);
    }

    public function testAbortMessage(): void
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('This is a message');

        abort(404, 'This is a message');
    }

    public function testAsset(): void
    {
        $this->assertSame(
            '/assets/test.txt',
            asset('assets/test.txt')
        );
    }

    public function testAssetFullBase(): void
    {
        $this->assertSame(
            'https://test.com/assets/test.txt',
            asset('assets/test.txt', ['fullBase' => true])
        );
    }

    public function testAuth(): void
    {
        $this->assertSame(
            Auth::instance(),
            auth()
        );
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testAuthorize(): void
    {
        authorize('test');
    }

    public function testAuthorizeFail(): void
    {
        $this->expectException(ForbiddenException::class);

        authorize('fail');
    }

    public function testCache(): void
    {
        $this->assertSame(
            Cache::use(),
            cache()
        );
    }

    public function testCacheKey(): void
    {
        $this->assertSame(
            Cache::use('null'),
            cache('null')
        );
    }

    public function testCan(): void
    {
        $this->assertTrue(can('test'));
    }

    public function testCanAny(): void
    {
        $this->assertTrue(can_any(['fail', 'test']));
    }

    public function testCanFail(): void
    {
        $this->assertFalse(can('fail'));
    }

    public function testCanNone(): void
    {
        $this->assertFalse(can_none(['fail', 'test']));
    }

    public function testCannot(): void
    {
        $this->assertTrue(cannot('fail'));
    }

    public function testCannotFail(): void
    {
        $this->assertFalse(cannot('test'));
    }

    public function testCollect(): void
    {
        $collection = collect([1, 2, 3]);

        $this->assertInstanceOf(
            Collection::class,
            $collection
        );

        $this->assertSame(
            [1, 2, 3],
            $collection->toArray()
        );
    }

    public function testConfig(): void
    {
        $this->assertSame(
            'Test',
            config('App.value')
        );
    }

    public function testDb(): void
    {
        $this->assertSame(
            ConnectionManager::use(),
            db()
        );
    }

    public function testDbKey(): void
    {
        $this->assertSame(
            ConnectionManager::use('other'),
            db('other')
        );
    }

    public function testEmail(): void
    {
        $this->assertInstanceOf(
            Email::class,
            email()
        );
    }

    public function testEncryption(): void
    {
        $this->assertSame(
            Encryption::use(),
            encryption()
        );
    }

    public function testEncryptionKey(): void
    {
        $this->assertSame(
            Encryption::use('openssl'),
            encryption('openssl')
        );
    }

    public function testEscape(): void
    {
        $this->assertSame(
            '&lt;b&gt;Test&lt;/b&gt;',
            escape('<b>Test</b>')
        );
    }

    public function testJson(): void
    {
        $response = json(['a' => 1]);

        $this->assertInstanceOf(
            ClientResponse::class,
            $response
        );

        $this->assertSame(
            '{'.PHP_EOL.
            '    "a": 1'.PHP_EOL.
            '}',
            $response->getBody()
        );

        $this->assertSame(
            'application/json; charset=UTF-8',
            $response->getHeaderValue('Content-Type')
        );
    }

    public function testLang(): void
    {
        $this->assertSame(
            'Test',
            __('Values.test')
        );
    }

    public function testLoggedIn(): void
    {
        $this->assertTrue(logged_in());
    }

    public function testModel(): void
    {
        $model = model('Test');

        $this->assertInstanceOf(
            Model::class,
            $model
        );

        $this->assertSame(
            'Test',
            $model->getAlias()
        );
    }

    public function testNow(): void
    {
        $this->assertInstanceOf(
            DateTime::class,
            now()
        );
    }

    public function testRedirect(): void
    {
        $response = redirect('https://test.com/');

        $this->assertInstanceOf(
            RedirectResponse::class,
            $response
        );

        $this->assertSame(
            'https://test.com/',
            $response->getHeaderValue('Location')
        );
    }

    public function testRequest(): void
    {
        $this->assertSame(
            ServerRequest::instance(),
            request()
        );
    }

    public function testRequestKey(): void
    {
        $this->assertNull(
            request('test')
        );
    }

    public function testRoute(): void
    {
        $this->assertSame(
            '/test',
            route('test')
        );
    }

    public function testRouteArguments(): void
    {
        $this->assertSame(
            '/test/1',
            route('test2', [1])
        );
    }

    public function testRouteFullBase(): void
    {
        $this->assertSame(
            'https://test.com/test',
            route('test', options: ['fullBase' => true])
        );
    }

    public function testSession(): void
    {
        $this->assertSame(
            1,
            session('a', 1)
        );

        $this->assertSame(
            1,
            session('a')
        );
    }

    public function testType(): void
    {
        $this->assertInstanceOf(
            DateTimeType::class,
            type('datetime')
        );
    }

    public function testUser(): void
    {
        $user = user();

        $this->assertInstanceOf(
            Entity::class,
            $user
        );

        $this->assertSame(1, $user->id);
    }

    public function testView(): void
    {
        $this->assertSame(
            'Template: 1',
            view('test/template', ['a' => 1])
        );
    }

    public function testViewLayout(): void
    {
        $this->assertSame(
            'Content: Template: 1',
            view('test/template', ['a' => 1], 'default')
        );
    }

    protected function setUp(): void
    {
        ConnectionManager::clear();

        ConnectionManager::setConfig([
            'default' => [
                'className' => SqliteConnection::class,
            ],
            'other' => [
                'className' => SqliteConnection::class,
            ],
        ]);
    }
}
