
      <section class="timeline-graph">
<?php
if ( is_month() || is_day() ) :
  get_template_part( 'template-parts/timeline/graph/month', args: [ 'year' => get_the_date( 'Y' ), 'month' => get_the_date( 'm' ) ] );
elseif ( is_year() ) :
  get_template_part( 'template-parts/timeline/graph/year', args: [ 'year' => get_the_date( 'Y' ) ] );
endif;
?>
      </section>