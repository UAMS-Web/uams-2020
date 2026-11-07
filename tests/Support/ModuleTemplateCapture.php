<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

/** Records the include boundary without substituting the module dispatcher itself. */
final class ModuleTemplateCapture
{
    public static array $calls = [];

    public static function record(string $template, array $variables): void
    {
        unset($variables['module']);
        ksort($variables);
        self::$calls[] = ['template' => $template, 'variables' => json_decode(json_encode($variables, JSON_THROW_ON_ERROR), true, flags: JSON_THROW_ON_ERROR)];
        echo '[template:'.$template.']';
    }
}
