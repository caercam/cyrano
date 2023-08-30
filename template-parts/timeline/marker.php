<?php
global $current;
if ( ( ! is_home() || ! is_sticky() ) && ( ! isset( $current ) || $current !== get_the_date( 'F' ) ) ) :
    $current = get_the_date( 'F' );

    if ( is_year() ) {
        $date = get_the_date( 'F' );
        $url  = get_month_link( get_the_date( 'Y' ), get_the_date( 'n' ) );
    } elseif ( is_month() ) {
        $date = get_the_date( 'F Y' );
        $url  = get_month_link( get_the_date( 'Y' ), get_the_date( 'n' ) );
    } elseif ( is_day() ) {
        $date = get_the_date( 'j F Y' );
        $url  = get_day_link( get_the_date( 'Y' ), get_the_date( 'n' ), get_the_date( 'j' ) );
    } else {
        $date = get_the_date( 'F Y' );
        $url  = get_month_link( get_the_date( 'Y' ), get_the_date( 'n' ) );
    }

    if ( is_tag() ) {
        $url = add_query_arg( 'tag', get_queried_object()->slug, $url );
    } elseif ( is_category() ) {
        $url = add_query_arg( 'categorie', get_queried_object()->slug, $url );
    }
?>
        <div class="marker"><span><a href="<?php echo esc_url( $url ); ?>"><?php echo $date; ?></a></span></div>
<?php endif; ?>