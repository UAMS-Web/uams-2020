<?php

declare(strict_types=1);

namespace Uams2020\Tests\Support;

/** Supplies the include variables used by the theme's field-definition callers. */
final class AcfFieldSchemaLoader
{
    public static function load(string $name, string $suffix, array $children = []): array
    {
        $content = $children['content'] ?? [];
        $action_bar = $children['action_bar'] ?? [];
        $call_out = $children['call_out'] ?? [];
        $cta = $children['cta'] ?? [];
        $hero = $children['hero'] ?? [];
        $link_list = $children['link_list'] ?? [];
        $news = $children['news'] ?? [];
        $overlay = $children['overlay'] ?? [];
        $post_tile = $children['post_tile'] ?? [];
        $post_tiles = $children['post_tiles'] ?? [];
        $side_by_side = $children['side_by_side'] ?? [];
        $stacked = $children['stacked'] ?? [];
        $livewhale = $children['livewhale'] ?? [];
        $gallery = $children['gallery'] ?? [];

        return require dirname(__DIR__, 2).'/acf_fields/'.$name.'.php';
    }
}
