<?php

declare(strict_types=1);

namespace App\Application\Helper;

use App\Application\Exception\HyperfValidationException;
use App\Application\Interface\DTOInterface;
use App\Application\Interface\ValidatorInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Swoole\Http\Status;

class HyperfValidator implements ValidatorInterface
{
    public function __construct(
        protected ValidatorFactoryInterface $factory
    ) {
    }

    public function validate(DTOInterface $dto): void
    {
        if (empty($rules = $dto::getValidateRules())) {
            return;
        }

        $this->validateArray($dto->toArray(), $rules);
    }

    public function validateArray(array $array, array $rules): void
    {
        $validator = $this->factory->make($array, $rules);

        if ($validator->passes()) {
            return;
        }

        throw new HyperfValidationException(
            $validator->errors()->first(),
            Status::UNPROCESSABLE_ENTITY
        );
    }
}
