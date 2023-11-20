<?php

declare(strict_types=1);

namespace App\Application\Task;

use App\Application\Interface\ProductServiceInterface;
use App\Application\Service\NotificationQueueService;
use Exception;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use Throwable;

#[Crontab(
    rule: '*/10 * * * *',
    //        rule: '*/10 * * * * *',
    name: 'ExpiringProductsNotification',
    callback: 'handle',
    memo: 'This is user expiring products notification scheduled task'
)]
class ExpiringProductsNotificationTask
{
    private LoggerInterface $logger;

    public function __construct(
        private ProductServiceInterface $productService,
        private NotificationQueueService $notificationQueueService,
        LoggerFactory $loggerFactory,
    ) {
        $this->logger = $loggerFactory->get('log', 'default');
    }

    public function handle(): void
    {
        try {
            $this->logger->info('Processing users expiring products notification task');
            $expiringProductsNotificationDTO = $this->productService->todayExpiringProducts();

            $this->notificationQueueService->push($expiringProductsNotificationDTO);
        } catch (Throwable|Exception $exception) {
            $this->logger->error(__CLASS__ . __FUNCTION__, [
                'message' => $exception->getMessage(),
                'line'    => $exception->getLine(),
                'file'    => $exception->getFile(),
                'trace'   => $exception->getTraceAsString(),
            ]);
        }
    }
}
