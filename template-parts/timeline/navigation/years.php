
    <div class="line"></div>
    <div class="navigation">
<?php foreach ( get_all_post_years() as $year => $total ) : ?>
<?php if ( $total ) : ?>
<?php
if ( is_tag() ) :
  $url = add_query_arg( 'annee', $year, get_term_link( get_queried_object_id(), 'post_tag' ) );
elseif ( is_category() ) :
  $url = add_query_arg( 'annee', $year, get_term_link( get_queried_object_id(), 'category' ) );
else :
  $url = get_year_link( $year );
endif;
?>
        <a class="year<?php echo $year == get_the_date( 'Y' ) ? ' active' : ''; ?>" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $year ); ?></a>
<?php else : ?>
        <span><?php echo esc_html( $year ); ?></span>
<?php endif; ?>
<?php endforeach; ?>
    </div>
