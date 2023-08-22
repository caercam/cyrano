
<?php if ( have_posts() ) : ?>
      <section class="timeline-navigation">
        <?php get_template_part( 'template-parts/timeline', 'navigation' ); ?>
      </section>
      <section class="timeline">
<?php
while ( have_posts() ) :
  the_post();
  get_template_part( 'template-parts/timeline', 'marker' );
  get_template_part( 'template-parts/cards/post' );
endwhile;
?>
      </section>
      <nav class="pagination">
<?php get_template_part( 'template-parts/pagination' ); ?>
      </nav>
<?php endif; ?>