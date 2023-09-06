<?php
/**
 * Template name: Activité
 */
get_header();
?>
      <article id="<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="post-content">
<?php get_template_part( 'template-parts/activity' ); ?>
        </div>
      </article>
<?php get_footer(); ?>
