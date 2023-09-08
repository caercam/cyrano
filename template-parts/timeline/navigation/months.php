
    <div class="line"></div>
    <div class="navigation">
<?php if ( $args['year'] < date( 'Y' ) ) : ?>
        <a class="previous-year" href="<?php echo esc_url( get_year_link( $args['year'] - 1 ) ); ?>"><?php echo esc_html( $args['year'] - 1 ); ?></a>
<?php endif; ?>
<?php foreach ( get_all_post_months( $args['year'] ) as $number => $month ) : ?>
<?php if ( $month['total'] ) : ?>
<?php
$query_args = [
  'annee' => $args['year'],
  'mois' => $number,
];

if ( is_tag() ) :
  $url = add_query_arg( $query_args, get_term_link( get_queried_object_id(), 'post_tag' ) );
elseif ( is_category() ) :
  $url = add_query_arg( $query_args, get_term_link( get_queried_object_id(), 'category' ) );
else :
  $url = get_month_link( $args['year'], $number );
endif;
?>
        <a class="month<?php echo $number == $args['month'] ? ' active' : ''; ?>" href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $month['month'] ); ?></a>
<?php else : ?>
        <span><?php echo esc_html( $month['month'] ); ?></span>
<?php endif; ?>
<?php endforeach; ?>
<?php if ( $args['year'] < date( 'Y' ) ) : ?>
        <a class="next-year" href="<?php echo esc_url( get_year_link( $args['year'] + 1 ) ); ?>"><?php echo esc_html( $args['year'] + 1 ); ?></a>
<?php endif; ?>
    </div>
