<?php get_header(); ?>
      <article id="<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="post-thumbnail">
          <?php the_post_thumbnail( [ 960, 360 ] ); ?>
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
