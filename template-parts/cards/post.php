
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
          <div class="post-date">
            <span title="<?php echo esc_attr( get_the_date( '\l\e j F Y \à H \h i' ) ); ?>"><?php echo 'il y a ' . human_time_diff( get_the_date( 'U' ), current_time( 'timestamp' ) ); ?></span>
          </div>
<?php else : ?>
          <div class="post-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </div>
          <div class="post-date">
            <span title="<?php echo esc_attr( get_the_date( '\l\e j F Y \à H \h i' ) ); ?>"><?php echo 'il y a ' . human_time_diff( get_the_date( 'U' ), current_time( 'timestamp' ) ); ?></span>
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
