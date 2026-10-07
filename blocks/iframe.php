<?php
/*
 *
 * UAMS iFrame Block (administrators only)
 *
 */

if ( uamswp_block_placeholder( $block ?? null, $is_preview ?? false ) ) {
    return;
}

$url        = get_field( 'iframe_url' );
$title      = get_field( 'iframe_title' );
$height     = absint( get_field( 'iframe_height' ) ) ?: 600;
$fullscreen = (bool) get_field( 'iframe_allow_fullscreen' );

$id = 'uams-iframe-' . ( $block['id'] ?? wp_unique_id() );
if ( ! empty( $block['anchor'] ) ) {
    $id = $block['anchor'];
}

$className = 'uams-module uams-iframe';
if ( ! empty( $block['className'] ) ) {
    $className .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
    $className .= ' align' . $block['align'];
}

// Re-check on render: the URL lives in block markup, which can be edited outside the ACF form.
if ( empty( $url ) || ! uamswp_iframe_url_is_allowed( $url ) ) {
    if ( ! empty( $is_preview ) ) {
        echo '<div class="uams-block-placeholder" style="padding:1.5rem;border:1px dashed currentColor;text-align:center;">' . esc_html__( 'UAMS iFrame: the URL is missing, not https, or not on the approved host list.' ) . '</div>';
    }
    return;
}

$sandbox = apply_filters( 'uamswp_iframe_sandbox', 'allow-scripts allow-same-origin allow-forms allow-popups allow-popups-to-escape-sandbox', $url );
?>
<div class="<?php echo esc_attr( $className ); ?>" id="<?php echo esc_attr( $id ); ?>">
    <iframe
        src="<?php echo esc_url( $url ); ?>"
        title="<?php echo esc_attr( $title ); ?>"
        style="width:100%;height:<?php echo esc_attr( $height ); ?>px;border:0;<?php echo ! empty( $is_preview ) ? 'pointer-events:none;' : ''; ?>"
        loading="lazy"
        referrerpolicy="strict-origin-when-cross-origin"
        <?php echo $sandbox ? 'sandbox="' . esc_attr( $sandbox ) . '"' : ''; ?>
        <?php echo $fullscreen ? 'allowfullscreen' : ''; ?>
    ></iframe>
</div>
