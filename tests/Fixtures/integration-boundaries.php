<?php

declare(strict_types=1);

// Integration exercises WordPress, while the external image plugin and Genesis are boundaries.
function bis_get_attachment_image_src($id, $dimensions, $crop): array
{
    $GLOBALS['uams2020_image_calls'][] = [$id, $dimensions, $crop];

    return ['src' => 'https://example.test/resized/'.$id];
}

function genesis(): void {}

function genesis_standard_loop(): void
{
    $GLOBALS['uams2020_loop_ids'] = wp_list_pluck($GLOBALS['wp_query']->posts, 'ID');
}
