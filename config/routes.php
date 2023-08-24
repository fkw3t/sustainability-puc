<?php

declare(strict_types=1);

use App\Infrastructure\Http\BarcodeManager\Handler\GetUsageLimit;
use App\Infrastructure\Http\IndexHandler;
use Hyperf\HttpServer\Router\Router;

Router::get('/', [IndexHandler::class, 'handle']);
Router::get('/info', function () {
    return phpinfo();
});

Router::addGroup('/barcode-manager', static function (): void {
    Router::get('/limits', [GetUsageLimit::class, 'handle']);
});
