<?php

declare(strict_types=1);

namespace Uams2020\PhpStan;

use PhpParser\Node\Expr\FuncCall;
use PHPStan\Analyser\Scope;
use PHPStan\PhpDoc\TypeStringResolver;
use PHPStan\Reflection\FunctionReflection;
use PHPStan\Type\DynamicFunctionReturnTypeExtension;
use PHPStan\Type\Type;

/** Types `get_field('literal')` and `get_sub_field('literal')` from the theme's field definitions. */
final class AcfGetFieldReturnTypeExtension implements DynamicFunctionReturnTypeExtension
{
    /** @param array<string, string> $types */
    public function __construct(private TypeStringResolver $resolver, private array $types) {}

    public function isFunctionSupported(FunctionReflection $functionReflection): bool
    {
        return in_array($functionReflection->getName(), ['get_field', 'get_sub_field'], true);
    }

    public function getTypeFromFunctionCall(FunctionReflection $functionReflection, FuncCall $functionCall, Scope $scope): ?Type
    {
        $args = $functionCall->getArgs();
        if ($args === []) {
            return null;
        }

        $names = $scope->getType($args[0]->value)->getConstantStrings();
        if (count($names) !== 1 || ! isset($this->types[$names[0]->getValue()])) {
            return null;
        }

        return $this->resolver->resolve($this->types[$names[0]->getValue()]);
    }
}
