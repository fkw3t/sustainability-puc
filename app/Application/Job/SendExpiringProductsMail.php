<?php

declare(strict_types=1);

namespace App\Application\Job;

use App\Application\DTO\Product\Structure\ExpiringProductNotificationDTO;
use Exception;
use Hyperf\AsyncQueue\Job;
use Hyperf\Logger\LoggerFactory;
use Hyperf\View\RenderInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Throwable;

use function Hyperf\Support\env;
use function Hyperf\Support\make;
use function Hyperf\ViewEngine\view;

class SendExpiringProductsMail extends Job
{
    public const SUBJECT = 'Expiring products';

    //    private readonly MailerInterface $mailer;
    //    private readonly RenderInterface $view;
    private LoggerInterface $logger;

    public function __construct(
        private readonly ExpiringProductNotificationDTO $params,
        # TODO: understand better way to fix di conflict with serialize jobs
        //        private readonly MailerInterface $mailer,
        //        private readonly RenderInterface $view,
        LoggerFactory $loggerFactory,
    ) {
        //        $this->mailer = make(MailerInterface::class);
        //        $this->view = make(RenderInterface::class);
        $this->logger = $loggerFactory->get('log', 'default');
    }

    public function handle(): void
    {
        $this->logger->info(
            'Processing user expiring products mail send job',
            $this->params->toArray()
        );

        try {
            $view = make(RenderInterface::class);

            $email = (new Email())
                ->from('support@zerowaste.com')
                ->to($this->params->userEmail)
                ->subject(self::SUBJECT)
                ->html(
                    view('email.expiring-product', [
                        'userName'               => $this->params->userName,
                        'productName'            => $this->params->productName,
                        'productExpireDate'      => $this->params->productExpireDate,
                        'productDaysUntilExpiry' => $this->params->productDaysUntilExpiry,
                        'productImageUrl'        => $this->params->productImageUrl,
                        'subject'                => self::SUBJECT,
                    ])->render()
                );

            $mailer = new Mailer(Transport::fromDsn(env('MAILER_DSN')));
            $mailer->send($email);

            $this->logger->info(
                'Mail sent',
                $this->params->toArray()
            );
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
