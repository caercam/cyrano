
          <div class="activity">
<?php foreach ( array_reverse( get_all_post_years(), true ) as $year => $total ) : ?>
            <div class="year">
              <div class="title">
                <a href="<?php echo esc_url( get_year_link( $year ) ); ?>"><?php echo esc_html( $year ); ?></a>
                <span><?php printf( '<strong>%s</strong>&nbsp; publication%s', $total, 1 < $total ? 's' : '' ); ?></span>
              </div>
              <div class="dots">
<?php foreach ( get_year_graph( $year ) as $day => $total ) : ?>
<?php if ( $total ) : ?>
                <span class="dot dot-<?php echo esc_attr( $total ); ?>" data-date="<?php echo esc_attr( $day ); ?>" aria-label="<?php printf( '%s - %d publication%s', date_i18n( 'j F Y', strtotime( $day ) ), $total, 1 < $total ? 's' : '' ); ?>" data-microtip-position="top" role="tooltip"></span>
<?php else : ?>
                <span class="dot" aria-label="<?php printf( '%s - aucune publication', date_i18n( 'j F Y', strtotime( $day ) ) ); ?>" data-microtip-position="top" role="tooltip"></span>
<?php endif; ?>
<?php endforeach; ?>
              </div>
            </div>
<?php endforeach; ?>
          </div>