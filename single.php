<?php get_header(); ?>
      <article id="<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="post-meta">
          <div class="prev-post">
            <?php echo get_previous_post_link( '%link', get_the_theme_svg( 'angle-left' ) ); ?>
          </div>
          <div class="details">
            <?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
            <?php the_category(); ?>
          </div>
          <div class="next-post">
            <?php echo get_next_post_link( '%link', get_the_theme_svg( 'angle-right' ) ); ?>
          </div>
        </div>
        <div class="post-thumbnail">
          <?php the_post_thumbnail( 'original' ); ?>
        </div>
        <div class="post-title">
          <?php the_title(); ?>
        </div>
        <div class="post-content">
          <?php the_content(); ?>
        </div>
      </article>
<?php get_footer(); ?>
