
        <article id="<?php the_ID(); ?>" <?php post_class(); ?>>
          <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail( 'original' ); ?>
            </a>
          </div>
<?php if ( 'status' === get_post_format() ) : ?>
          <div class="post-content">
            <?php the_content(); ?>
          </div>
          <div class="post-meta">
            <span class="post-date" title="<?php echo esc_attr( get_the_date( '\l\e j F Y \à H \h i' ) ); ?>"><?php echo 'il y a ' . human_time_diff( get_the_date( 'U' ), current_time( 'timestamp' ) ); ?></span>
<?php if ( has_term( taxonomy: 'rating' ) ) : ?>
            <span class="post-rating"><?php echo get_the_rating(); ?></span>
<?php endif; ?>
          </div>
<?php else : ?>
          <div class="post-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </div>
          <div class="post-meta">
            <span class="post-date" title="<?php echo esc_attr( get_the_date( '\l\e j F Y \à H \h i' ) ); ?>"><?php echo 'il y a ' . human_time_diff( get_the_date( 'U' ), current_time( 'timestamp' ) ); ?></span>
          </div>
<?php if ( is_tax() ) : ?>
          <div class="post-content">
            <?php the_content(); ?>
          </div>
<?php else : ?>
          <div class="post-excerpt">
            <?php the_excerpt(); ?>
          </div>
<?php endif; ?>
<?php endif; ?>
        </article>
