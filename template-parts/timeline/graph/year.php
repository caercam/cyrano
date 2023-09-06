
<?php foreach ( get_year_graph( $args['year'] ) as $day => $total ) : ?>
        <span class="dots dots-<?php echo esc_html( $total ); ?>"></span>
<?php endforeach; ?>
