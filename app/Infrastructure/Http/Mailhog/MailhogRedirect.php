<?php

declare(strict_types=1);

namespace App\Infrastructure\Http\Mailhog;

use Hyperf\HttpServer\Contract\ResponseInterface;

final class MailhogRedirect
{
    public function handle(ResponseInterface $response)
    {
        return $response->redirect('http://0.0.0.0:8025');
    }
}
