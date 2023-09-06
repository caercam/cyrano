<?php get_header(); ?>
      <article id="<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="post-thumbnail">
          <?php the_post_thumbnail( 'original' ); ?>
          <div class="post-navigation">
            <div class="prev-post">
              <?php echo get_previous_post_link( '%link', get_the_theme_svg( 'angle-left' ) ); ?>
            </div>
            <div class="next-post">
              <?php echo get_next_post_link( '%link', get_the_theme_svg( 'angle-right' ) ); ?>
            </div>
          </div>
        </div>
        <div class="post-title">
          <?php the_title(); ?>
        </div>
        <div class="post-meta">
          <span class="date"><?php echo get_the_date( 'j F Y' ); ?></span>
          <span class="categories"><?php the_category(); ?></span>
        </div>
        <div class="post-content">
          <?php the_content(); ?>
        </div>
      </article>
<?php get_footer(); ?>
