<?php

get_template_part( 'template-parts/timeline/navigation' );
// get_template_part( 'template-parts/timeline/graph' );

if ( have_posts() ) :
?>
      <section class="timeline">
<?php
while ( have_posts() ) :
  the_post();
  get_template_part( 'template-parts/timeline/marker' );
  get_template_part( 'template-parts/cards/post' );
endwhile;
?>
      </section>
<?php endif; ?>