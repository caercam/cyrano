<?php if ( 'list' === $block->attributes['mode'] ) : ?>
    <div class="wp-block-cinemarathon-marathons mode-list">
<?php else : ?>
    <div class="wp-block-cinemarathon-marathons mode-grid" data-grid-columns="<?php echo esc_attr( $block->attributes['columns'] ); ?>">
<?php
endif;

if ( count( $block->data['items'] ) ) :
    foreach ( $block->data['items'] as $item ) :
?>
        <div class="marathon"<?php echo 'grid' === $block->attributes['mode'] ? ' style="--marathon-featured-image:url(' . esc_url( $item['image'] ) . ')' : ''; ?>">
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