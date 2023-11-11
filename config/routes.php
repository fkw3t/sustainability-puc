<?php

declare(strict_types=1);

use App\Infrastructure\Http\Documentation\Handler\DocumentationOpenApi;
use App\Infrastructure\Http\Middleware\BasicAuthMiddleware;
use App\Infrastructure\Http\Product\Handler\AssignProduct;
use App\Infrastructure\Http\Product\Handler\GetProduct;
use App\Infrastructure\Http\User\Handler\AuthenticateUser;
use App\Infrastructure\Http\User\Handler\RegisterUser;
use Hyperf\HttpServer\Router\Router;

Router::get('/', function () {
    return ['hello word with Hyperf'];
});
Router::get('/info', function () {
    return phpinfo();
});

Router::get('/docs', [DocumentationOpenApi::class, 'html']);
Router::get('/docs.json', [DocumentationOpenApi::class, 'json']);
Router::get('/docs.yaml', [DocumentationOpenApi::class, 'yaml']);

Router::addGroup('/api', static function (): void {
    Router::addGroup('/user', static function (): void {
        Router::post('/register', [RegisterUser::class, 'handle']);
        Router::post('/authenticate', [AuthenticateUser::class, 'handle']);
    });

    Router::addGroup('/product', static function (): void {
        Router::get('', [GetProduct::class, 'handle']);
        Router::get('/assign', [AssignProduct::class, 'handle']);
    }, ['middleware' => [BasicAuthMiddleware::class]]);
});
