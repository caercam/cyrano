<?php
$watchlists = get_watchlists_for_the_month( year: get_the_date( 'Y' ), month: get_the_date( 'n' ) );
if ( count( $watchlists ) ) :
?>
        <div class="watchlists-wrapper">
            <a class="watchlists-toggle" href="#watchlists">Watchlists</a>
            <div class="watchlists">
<?php foreach ( $watchlists as $watchlist ) : ?>
                <div class="watchlist">
                    <div class="title">
                        <a href="<?php echo esc_url( get_the_permalink( post: $watchlist ) ); ?>"><?php echo get_the_title( post: $watchlist ); ?></a>
                    </div>
                    <div class="content"><?php echo do_blocks( $watchlist->post_content ); ?></div>
                </div>
<?php endforeach; ?>
            </div>
        </div>
<?php endif;