<?php get_header(); ?>
      <article id="<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="post-thumbnail">
          <?php the_post_thumbnail( 'original' ); ?>
        </div>
        <div class="post-title">
          <?php the_title(); ?>
        </div>
        <div class="post-meta">
          <span class="date"><?php echo get_the_date( 'j F Y' ); ?></span>
          <span class="categories"><?php the_category(); ?></span>
<?php $count = wp_count_comments( get_the_ID() ); ?>
          <span class="comments"><?php printf( '%d commentaire%s', $count->approved, 1 < $count->approved ? 's' : '' ); ?></span>
        </div>
        <div class="post-content">
          <?php the_content(); ?>
        </div>
      </article>
<?php get_footer(); ?>
