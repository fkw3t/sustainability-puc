<?php

declare(strict_types=1);

namespace App\Application\Job;

use App\Application\DTO\Product\Structure\ExpiringProductsNotificationDTO;
use Hyperf\AsyncQueue\Job;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;

class SendExpiringProductsMail extends Job
{
    private LoggerInterface $logger;

    public function __construct(
        private readonly ExpiringProductsNotificationDTO $params,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    public function handle(): void
    {
        var_dump($this->params);
        $this->logger->info(
            'User expiring products mail sent',
            $this->params->toArray()
        );

        // TODO: send mail
    }
}
