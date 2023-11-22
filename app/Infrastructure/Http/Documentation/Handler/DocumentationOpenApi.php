<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Documentation\Handler;

use Hyperf\ViewEngine\View;
use OpenApi\Attributes as OA;
use OpenApi\Generator;

use function Hyperf\ViewEngine\view;

#[OA\Info(
    version: '1.0',
    description: 'a REST API for sustainability work school project',
    title: 'sustainability api'
)]
#[OA\Contact(name: 'sustainability owners', url: 'https://github.com/fkw3t')]
#[OA\Server(url: 'http://0.0.0.0:9999', description: 'local environment')]
#[OA\Server(url: 'https://helloelitxmq.com', description: 'production environment')]
#[OA\SecurityScheme(securityScheme: 'auth', type: 'http', bearerFormat: 'JWT', scheme: 'bearer')]
final readonly class DocumentationOpenApi
{
    public function __construct(
        private Generator $generator
    ) {
    }

    public function html(): View
    {
        return view('documentation-swagger');
    }

    public function json(): string
    {
        return $this->generator::scan([
            BASE_PATH . '/app',
        ])->toJson();
    }

    public function yaml(): string
    {
        return $this->generator::scan([
            BASE_PATH . '/app',
        ])->toYaml();
    }
}
