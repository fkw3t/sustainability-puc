<?php

declare(strict_types=1);

namespace App\Application\DTO;

use App\Application\Helper\Str;
use App\Application\Interface\DTOInterface;
use InvalidArgumentException;

abstract class DTO implements DTOInterface
{
    protected static array $validateRules = [];

    protected static array $hidden = [];

    public static function getValidateRules(): array
    {
        return static::$validateRules;
    }

    public static function fromArray(array $parameters): self
    {
        $dto = new static();

        foreach ($parameters as $key => $value) {
            if (! property_exists($dto, $key)) {
                throw new InvalidArgumentException(
                    sprintf('Attribute %s does not exist in %s', $key, get_class($dto))
                );
            }

            $setter = sprintf('set%s', Str::camelCase((string) $key));

            if (method_exists($dto, $setter)) {
                $dto->{$setter}($value);
                continue;
            }

            $dto->{$key} = $value;
        }

        return $dto;
    }

    public function toArray(array $attributes = null): array
    {
        $attributes = $attributes ?? get_object_vars($this);
        $array = [];

        foreach ($attributes as $key => $value) {
            $str_key = is_integer($key) ? $key : Str::camelCaseToSnakeCase($key);

            if (in_array($key, static::$hidden, true)) {
                continue;
            }

            if ($value instanceof DTOInterface) {
                $array[$str_key] = $value->toArray();
                continue;
            }

            if (is_array($value)) {
                $array[$str_key] = $this->toArray($value);
                continue;
            }

            $getter_default = sprintf('get%s', Str::camelCase((string) $key));
            if (method_exists($this, $getter_default)) {
                $array[$str_key] = $this->{$getter_default}();
                continue;
            }

            $getter_boolean = sprintf('is%s', Str::camelCase((string) $key));
            if (method_exists($this, $getter_boolean)) {
                $array[$str_key] = $this->{$getter_boolean}();
                continue;
            }

            $array[$str_key] = $value;
        }

        return $array;
    }
}
