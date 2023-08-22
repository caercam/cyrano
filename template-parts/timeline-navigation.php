<?php if ( is_date() ) : ?>
    <div class="line"></div>
    <nav>
<?php foreach ( get_all_post_years() as $year => $total ) : ?>
<?php if ( $total ) : ?>
        <a class="<?php echo $year == get_the_date( 'Y' ) ? 'active' : ''; ?>" href="<?php echo esc_url( get_year_link( $year ) ); ?>"><?php echo esc_html( $year ); ?></a>
<?php else : ?>
        <span><?php echo esc_html( $year ); ?></span>
<?php endif; ?>
<?php endforeach; ?>
    </nav>
<?php endif; ?>