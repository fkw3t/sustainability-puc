<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Infrastructure\Http\Handler\IndexHandler;
use App\Infrastructure\Http\Handler\Pokemon\FindByNameHandler;
use Hyperf\HttpServer\Router\Router;

Router::get('/', [IndexHandler::class, 'handle']);
Router::get('/pokemon', [FindByNameHandler::class, 'handle']);

Router::get('/info', function () {
    return phpinfo();
});
