<?php

namespace Unit;

use Core\Request;
use Core\Router;
use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        parent::setUp();
        $this->router = new Router(new Request());
    }

    /** @test */
    public function no_routes_when_router_is_created()
    {
        self::assertEmpty($this->router->routes());
    }

    /** @test */
    public function it_register_a_route()
    {
        $router = new Router(new Request());

        $router->get('test', ['Core\Controller', 'index']);
        $router->post('test', ['Core\Controller', 'test']);
        $expected = [
            'get' => [
                'test' => ['Core\Controller', 'index']
            ],
            'post' => [
                'test' => ['Core\Controller', 'test']
            ]
        ];

        self::assertEquals($expected, $router->routes());
    }

    //all callback types works [callable, controller method (static, normal)

    /** @test
     * @dataProvider callbackCases
     */
    public function all_callback_types_work(string $method, string $url)
    {
        $controller = new class {
            public function index()
            {
                return true;
            }
        };
        $this->router->get('test', [$controller::class, 'index']);
        $this->router->get('closure', fn() => true);
        $this->router->get('closure2', function () {
            return true;
        });

        self::assertTrue($this->router->resolve($method, $url));
    }

    public static function callbackCases()
    {
        return [
            ['get', 'test'],
            ['get', 'closure'],
            ['get', 'closure2'],
        ];
    }
    //catch exception if no route found

    //extract route parameters
}