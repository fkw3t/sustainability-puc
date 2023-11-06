<?php

declare(strict_types=1);

use App\Infrastructure\Http\Documentation\Handler\DocumentationOpenApi;
use Hyperf\View\Mode;

! defined('BASE_PATH') && define('BASE_PATH', dirname(__DIR__, 1));

return [
    'engine' => DocumentationOpenApi::class,
    'mode'   => Mode::SYNC,
    'config' => [
        'view_path'  => BASE_PATH . '/storage/view/',
        'cache_path' => BASE_PATH . '/runtime/view',
    ],
];
