<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Application\DTO\Product\Structure\ExpiringProductsNotificationDTO;
use App\Application\Job\SendExpiringProductsMail;
use Hyperf\AsyncQueue\Driver\DriverFactory;
use Hyperf\AsyncQueue\Driver\DriverInterface;
use Hyperf\Logger\LoggerFactory;

class NotificationQueueService
{
    private DriverInterface $driver;

    public function __construct(DriverFactory $driverFactory)
    {
        $this->driver = $driverFactory->get('default');
    }

    public function push(
        ExpiringProductsNotificationDTO $params,
        $delay = 0
    ): bool {
        return $this->driver->push(
            new SendExpiringProductsMail(
                $params,
                make(LoggerFactory::class)
            ),
            $delay
        );
    }
}
