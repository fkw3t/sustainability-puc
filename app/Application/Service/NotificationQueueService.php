<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\Product\Structure\ExpiringProductNotificationDTO;
use App\Application\Job\SendExpiringProductsMail;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Driver\DriverInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\View\RenderInterface;
use Symfony\Component\Mailer\MailerInterface;

use function Hyperf\Support\make;

class NotificationQueueService
{
    private DriverInterface $driver;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driver = $driverFactory->get('default');
    }

    public function push(
        ExpiringProductNotificationDTO $params,
        $delay = 0
    ): bool {
        return $this->driver->push(
            job: new SendExpiringProductsMail(
                params: $params,
                loggerFactory: make(LoggerFactory::class),
            ),
            delay: $delay
        );
    }
}
