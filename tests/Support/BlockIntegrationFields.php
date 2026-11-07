<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support {
    /** Field values only; integration keeps WordPress queries, escaping, filters and shortcodes real. */
    final class BlockIntegrationFields
    {
        public static array $values = [];
    }
}

namespace {
    use Uams2020\Tests\Support\BlockIntegrationFields;

    if (! function_exists('get_field')) {
        function get_field($key)
        {
            if (! array_key_exists($key, BlockIntegrationFields::$values)) {
                throw new LogicException('Unexpected integration field: '.$key);
            }

            return BlockIntegrationFields::$values[$key];
        }
    }
}
