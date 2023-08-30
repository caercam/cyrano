
<?php foreach ( get_month_graph( $args['year'], $args['month'] ) as $month ) : ?>
        <span><?php echo esc_html( $month ); ?></span>
<?php endforeach; ?>
