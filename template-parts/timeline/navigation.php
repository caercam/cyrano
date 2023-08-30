
      <section class="timeline-navigation">
<?php
if ( isset( $_GET['annee'] ) ) :
  get_template_part( 'template-parts/timeline/navigation/months', args: [ 'year' => $_GET['annee'], 'month' => $_GET['mois'] ?? null ] );
elseif ( is_month() || is_day() ) :
  get_template_part( 'template-parts/timeline/navigation/months', args: [ 'year' => get_the_date( 'Y' ), 'month' => get_the_date( 'm' ) ] );
else :
  get_template_part( 'template-parts/timeline/navigation/years' );
endif;
?>
      </section>