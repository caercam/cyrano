<?php
$data = [
    'image' => CINEMARATHONS_URL . 'assets/images/default-image.jpg',
    'current' => 0,
    'total' => 0,
    'progress' => 0,
];

if ( ! empty( $attributes['image'] ) ) {
    $data['image'] = wp_get_attachment_image_url( (int) $attributes['image'], 'original' );
}

if ( ! empty( $attributes['movies'] ) ) {
    $data['current'] = count( wp_filter_object_list( $attributes['movies'], [ 'watched' => 1 ] ) );
    $data['rewatch'] = count( wp_filter_object_list( $attributes['movies'], [ 'rewatch' => 1 ] ) );
    $data['total'] = count( $attributes['movies'] );
    $data['progress'] = round( ( $data['current'] / $data['total'] ) * 100 );
}

$data['bonuses'] = wp_filter_object_list( $attributes['movies'] ?? [], [ 'bonus' => 1 ] );
$data['movies'] = wp_filter_object_list( $attributes['movies'] ?? [], [ 'bonus' => 0 ] );

$data['posts'] = wp_list_pluck( $data['movies'], 'post_id' );
$data['posts'] = array_filter( $data['posts'] );

$data['ratings'] = [];
$data['results'] = [
    '0.0' => 0,
    '0.5' => 0,
    '1.0' => 0,
    '1.5' => 0,
    '2.0' => 0,
    '2.5' => 0,
    '3.0' => 0,
    '3.5' => 0,
    '4.0' => 0,
    '4.5' => 0,
    '5.0' => 0,
];

foreach ( $data['posts'] as $post_id ) {
    if ( has_term( taxonomy: 'rating', post: $post_id ) ) {
        $ratings = get_the_terms( $post_id, 'rating' );
        if ( is_array( $ratings ) && count( $ratings ) && $ratings[0] instanceof \WP_Term ) {
            $data['ratings'][ $post_id ] = $ratings[0]->name;
            $data['results'][ $ratings[0]->name ]++;
        }
    }
}
?>
<div <?php echo get_block_wrapper_attributes( [ 'id' => $attributes['anchor'] ?? $attributes['id'] ?? '' ] ); ?>>
    <div class="marathon-header">
        <img src="<?php echo esc_url( $data['image'] ); ?>" alt="<?php echo esc_html( $attributes['title'] ); ?>">
    </div>
    <div class="marathon-content">
        <div class="marker">
            <span>
                <a href="/cinemarathon">Cinémaration</a>
            </span>
        </div>
        <h2><?php echo esc_html( $attributes['title'] ); ?></h2>
        <?php echo wpautop( esc_html( $attributes['description'] ?? '' ) ); ?>
        <div class="details">
<?php if ( ! empty( $attributes['objectives'] ) ) : ?>
            <h3>Objectifs</h3>
            <?php echo wpautop( esc_html( $attributes['objectives'] ) ); ?>
<?php endif; ?>
            <h4>Légende</h4>
            <ul>
                <li><?php printf( __( '%s: Seen this one before, to be watched again', 'cinemarathon' ), '<span class="item-status is-rewatch">' . get_the_theme_svg( 'double-check' ) . '</span>' ); ?></li>
                <li><?php printf( __( '%s: Not watched (yet)!', 'cinemarathon' ), '<span class="item-status not-watched">' . get_the_theme_svg( 'check' ) . '</span>' ); ?></li>
                <li><?php printf( __( '%s: Watched!', 'cinemarathon' ), '<span class="item-status has-been-watched">' . get_the_theme_svg( 'check' ) . '</span>' ); ?></li>
                <li><?php printf( __( '%s: Available for watching', 'cinemarathon' ), '<span class="item-availability is-available">' . get_the_theme_svg( 'dvd-check' ) . '</span>' ); ?></li>
                <li><?php printf( __( '%s: Unavailable for watching', 'cinemarathon' ), '<span class="item-availability not-available">' . get_the_theme_svg( 'dvd-no' ) . '</span>' ); ?></li>
            </ul>
<?php if ( ! empty( $attributes['comments'] ) ) : ?>
            <h3>Commentaires</h3>
            <?php echo wpautop( esc_html( $attributes['comments'] ) ); ?>
<?php endif; ?>
<?php if ( $data['current'] === $data['total'] ) : ?>
            <h3>Résultats</h3>
            <p>Note moyenne : <?php echo round( array_sum( $data['ratings'] ) / count( $data['ratings'] ), 1 ); ?></p>
            <div data-chartist="marathonData<?php the_ID(); ?>"></div>
            <script>localStorage.setItem('marathonData<?php the_ID(); ?>', '<?php echo json_encode( [
                'labels' => array_keys( $data['results'] ),
                'series' => [ array_values( $data['results'] ) ],
            ] ) ;?>' );</script>
<?php endif; ?>
        </div>
        <h3>Liste complète</h3>
        <p><?php
            printf(
                '<strong>%d</strong> film%s au total, <strong>%d</strong> regardé%s, <strong>%d</strong> restant%s, <strong>%d</strong> déjà vu%s précédemment.',
                $data['total'],
                1 < $data['total'] ? 's' : '',
                $data['current'],
                1 < $data['current'] ? 's' : '',
                ( $data['total'] - $data['current'] ),
                1 < ( $data['total'] - $data['current'] ) ? 's' : '',
                $data['rewatch'],
                1 < $data['rewatch'] ? 's' : ''
            ); ?></p>
        <div class="items">
            <ul>
<?php
foreach ( $data['movies'] as $movie ) :
    $icon = $movie['rewatch'] ? 'double-check' : 'check';
    $status = '';
    $status .= $movie['watched'] ? ' has-been-watched' : ' not-watched';
    $status .= $movie['rewatch'] ? ' is-rewatch' : '';
    $availability = $movie['available'] ? ' is-available': ' not-available';
?>
                <li>
                    <span class="item-status<?php echo esc_attr( $status ); ?>"><?php the_theme_svg( $icon ); ?></span>
                    <span class="item-availability<?php echo esc_attr( $availability ); ?>"><?php the_theme_svg( $movie['available'] ? 'dvd-check': 'dvd-no' ); ?></span>
<?php if ( ! empty( $movie['post_id'] ) ) : ?>
                    <a class="item-title" href="<?php echo esc_html( get_permalink( $movie['post_id'] ) ); ?>"><?php echo esc_html( $movie['title'] ); ?><?php the_theme_svg( 'link' ); ?></a>
<?php if ( has_term( taxonomy: 'rating', post: $movie['post_id'] ) ) : ?>
                    <div class="rating"><?php echo \roxane\support\get_the_rating( $movie['post_id'] ); ?></div>
<?php endif; ?>
<?php else : ?>
                    <span class="item-title"><?php echo esc_html( $movie['title'] ); ?></span>
<?php endif; ?>
                </li>
<?php endforeach; ?>
            </ul>
<?php if ( 0 < count( $data['bonuses'] ) ) : ?>
            <h3>Bonus</h3>
            <p>Films à voir en bonus. Ne font pas partie du marathon à proprement parler, mais offre une satisfaction supplémentaire.</p>
            <ul>
<?php
foreach ( $data['bonuses'] as $movie ) :
    $icon = $movie['rewatch'] ? 'double-check' : 'check';
    $status = '';
    $status .= $movie['watched'] ? ' has-been-watched' : ' not-watched';
    $status .= $movie['rewatch'] ? ' is-rewatch' : '';
    $availability = $movie['available'] ? ' is-available': ' not-available';
?>
                <li>
                    <span class="item-status<?php echo esc_attr( $status ); ?>"><?php the_theme_svg( $icon ); ?></span>
                    <span class="item-availability<?php echo esc_attr( $availability ); ?>"><?php the_theme_svg( $movie['available'] ? 'dvd-check': 'dvd-no' ); ?></span>
<?php if ( ! empty( $movie['post_id'] ) ) : ?>
                    <a class="item-title" href="<?php echo esc_html( get_permalink( $movie['post_id'] ) ); ?>"><?php echo esc_html( $movie['title'] ); ?><?php the_theme_svg( 'link' ); ?></a>
<?php else : ?>
                    <span class="item-title"><?php echo esc_html( $movie['title'] ); ?></span>
<?php endif; ?>
                </li>
<?php endforeach; ?>
            </ul>
<?php endif; ?>
        </div>
    </div>
</div>