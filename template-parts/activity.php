
          <div class="activity">
<?php
$years = array_reverse( get_all_post_years(), true );
foreach ( $years as $year => $total ) :
  $posts = get_year_graph( $year );
  $calendar = get_full_year_calendar( $year );
?>
            <div class="year">
              <div class="title">
                <a href="<?php echo esc_url( get_year_link( $year ) ); ?>"><?php echo esc_html( $year ); ?></a>
                <span><?php printf( '<strong>%s</strong>&nbsp; publication%s', $total, 1 < $total ? 's' : '' ); ?></span>
              </div>
              <div class="resume">
                <div class="content">
                  <span><strong><?php echo esc_html( number_format_i18n( get_total_posts_from_category_for_year( category: 'cinema', year: $year ) ) ); ?></strong> film(s)</span>
                  <span><strong><?php echo esc_html( number_format_i18n( get_total_posts_from_category_for_year( category: 'series', year: $year ) ) ); ?></strong> épisodes(s)</span>
                </div>
              </div>
              <div class="calendar">
<?php foreach ( $calendar as $month => $weeks ) : ?>
                <div class="month">
                  <div class="title"><?php echo ucfirst( date_i18n( 'F', strtotime( "$year-$month-1" ) ) ); ?></div>
                  <div class="days">
                    <span>L</span>
                    <span>M</span>
                    <span>M</span>
                    <span>J</span>
                    <span>V</span>
                    <span>S</span>
                    <span>D</span>
                  </div>
                  <div class="weeks">
<?php foreach ( $weeks as $week => $days ) : ?>
<?php if ( array_sum( $days ) ) : ?>
                    <div class="week">
<?php foreach ( $days as $day ) : $date = sprintf( '%d-%02d-%02d', $year, $month, $day ); ?>
<?php if ( ! $day ) : ?>
                      <div class="day void"></div>
<?php else : ?>
<?php if ( ! empty( $posts[ $date ] ) && 0 < $posts[ $date ] ) : ?>
                      <span class="day dot dot-<?php echo $posts[ $date ]; ?>" data-date="<?php echo esc_attr( $date ); ?>" aria-label="<?php printf( '%s - %d publication%s', date_i18n( 'j F Y', strtotime( $date ) ), $posts[ $date ], 1 < $posts[ $date ] ? 's' : '' ); ?>" data-microtip-position="top" role="tooltip"></span>
<?php else : ?>
                      <div class="day dot" aria-label="<?php printf( '%s - aucune publication', date_i18n( 'j F Y', strtotime( $date ) ) ); ?>" data-microtip-position="top" role="tooltip"></div>
<?php endif; ?>
<?php endif; ?>
<?php endforeach; ?>
                    </div>
<?php endif; ?>
<?php endforeach; ?>
                  </div>
                </div>
<?php endforeach; ?>
              </div>
            </div>
<?php endforeach; ?>
          </div>