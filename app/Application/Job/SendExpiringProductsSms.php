<?php

declare(strict_types=1);

namespace App\Application\Job;

use Hyperf\AsyncQueue\Job;

class SendExpiringProductsSms extends Job
{
    public $params;

    public function __construct($params)
    {
        // It's best to use normal data here. Don't pass the objects that carry IO, such as PDO objects.
        $this->params = $params;
    }

    public function handle()
    {
        // find the correlation of users products close to expiry
        //
        var_dump($this->params);
    }
}
