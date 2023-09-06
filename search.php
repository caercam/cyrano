<?php get_header(); ?>
<?php if ( isset( $_GET['s'] ) && empty( $_GET['s'] ) ) : ?>
<?php get_template_part( 'template-parts/timeline/navigation' ); ?>
      <section class="search">
<?php get_template_part( 'template-parts/search-form' ); ?>
      </section>
<?php else : ?>
<?php get_template_part( 'template-parts/timeline/timeline' ); ?>
<?php endif; ?>
<?php get_footer(); ?>
