<?php

declare(strict_types=1);

return [
    Hyperf\Crontab\Process\CrontabDispatcherProcess::class,
    Hyperf\AsyncQueue\Process\ConsumerProcess::class,
];