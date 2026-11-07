<?php

declare(strict_types=1);

function uams_mutation_function_probe($value = 1)
{
    return $value;
}

final class UamsMutationMethodProbe
{
    public function probe($value = 1)
    {
        return $value;
    }
}

$trace = [];
$closure = function ($value = 1) use (&$trace) {
    $trace[] = $value;

    return $value;
};

$arrow = fn ($value = 1) => $value;
