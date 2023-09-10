<?php

declare(strict_types=1);

use App\Infrastructure\Http\Product\Handler\GetProduct;
use Hyperf\HttpServer\Router\Router;

Router::get('/', function () {
    return ['hello word with Hyperf'];
});
Router::get('/info', function () {
    return phpinfo();
});

Router::addGroup('/product', static function (): void {
    Router::get('', [GetProduct::class, 'handle']);
});