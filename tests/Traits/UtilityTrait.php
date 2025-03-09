<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use Closure;

trait UtilityTrait
{
    private function resolveValues(array ...$params): array
    {
        foreach($params as &$value) {
            $value = $this->resolveValue($value);
        }

        return $params;
    }

    private function resolveValue($value)
    {
        if (is_array($value)) {
            foreach ($value as &$subValue) {
                $subValue = $this->resolveValue($subValue);
            }
        }

        if ($value instanceof Closure) {
            $value = $value();
        }

        return $value;
    }
}