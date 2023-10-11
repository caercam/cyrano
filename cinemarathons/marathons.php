<?php $items = \Cinemarathons\get_marathons( [ 'number' => $attributes['number'] ] ); ?>
    <div <?php echo get_block_wrapper_attributes( [ 'id' => $attributes['anchor'] ?? $attributes['id'] ?? '' ] ); ?>>
<?php if ( 'list' === $attributes['mode'] ) : ?>
        <div class="marathons marathons-list">
<?php else : ?>
        <div class="marathons marathons-grid" data-grid-columns="<?php echo esc_attr( $attributes['columns'] ); ?>">
<?php
endif;

if ( count( $items ) ) :
    foreach ( $items as $item ) :
?>
            <div class="marathon"<?php echo 'grid' === $attributes['mode'] ? ' style="--marathon-featured-image:url(' . esc_url( $item['image'] ) . ')' : ''; ?>">
                <a href="<?php echo esc_url( $item['url'] ); ?>">
                    <span class="title"><?php echo esc_html( $item['title'] ); ?></span>
                    <span class="progress">
                        <span class="bar">
                            <span style="--marathon-progress:<?php echo esc_attr( "{$item['progress']}%" ); ?>"></span>
                        </span>
                        <span class="score"><?php printf( __( "%s%% − %s on %s watched", "cinemarathon" ), $item['progress'], $item['current'], $item['total'] ); ?></span>
                    </span>
                </a>
            </div>
<?php
    endforeach;
endif;
?>
        </div>
    </div>