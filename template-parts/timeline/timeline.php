<?php
foreach ( range( 2000, 2024 ) as $y ) :
  //var_dump( $y, get_all_post_months( $y ) );
endforeach;

get_template_part( 'template-parts/timeline/navigation' );
get_template_part( 'template-parts/timeline/graph' );

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
      <nav class="pagination">
<?php get_template_part( 'template-parts/pagination' ); ?>
      </nav>
<?php endif; ?>