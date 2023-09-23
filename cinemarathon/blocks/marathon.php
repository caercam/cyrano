
<div class="wp-block-cinemarathon-marathon">
    <div class="marathon-header">
        <img src="<?php echo esc_url( $block->data['image'] ); ?>" alt="<?php echo esc_html( $block->attributes['title'] ); ?>">
    </div>
    <div class="marathon-content">
        <div class="marker">
            <span>
                <a href="/cinemarathon">Cinémaration</a>
            </span>
        </div>
        <h2><?php echo esc_html( $block->attributes['title'] ); ?></h2>
        <?php echo wpautop( esc_html( $block->attributes['description'] ?? '' ) ); ?>
        <div class="details">
<?php if ( ! empty( $block->attributes['objectives'] ) ) : ?>
            <h3>Objectifs</h3>
            <?php echo wpautop( esc_html( $block->attributes['objectives'] ) ); ?>
<?php endif; ?>
            <h4>Légende</h4>
            <ul>
                <li><?php printf( __( '%s: Seen this one before, to be watched again', 'cinemarathon' ), '🟠' ); ?></li>
                <li><?php printf( __( '%s: Not watched (yet)!', 'cinemarathon' ), '🔴' ); ?></li>
                <li><?php printf( __( '%s: Watched!', 'cinemarathon' ), '🟢' ); ?></li>
                <li><?php printf( __( '%s: Available for watching', 'cinemarathon' ), '📀' ); ?></li>
                <li><?php printf( __( '%s: Unavailable for watching', 'cinemarathon' ), '💸' ); ?></li>
            </ul>
<?php if ( ! empty( $block->attributes['comments'] ) ) : ?>
            <h3>Commentaires</h3>
            <?php echo wpautop( esc_html( $block->attributes['comments'] ) ); ?>
<?php endif; ?>
        </div>
        <h3>Liste complète</h3>
        <p><?php
            printf(
                '<strong>%d</strong> film%s au total, <strong>%d</strong> regardé%s, <strong>%d</strong> restant%s, <strong>%d</strong> déjà vu%s précédemment.',
                $block->data['total'],
                1 < $block->data['total'] ? 's' : '',
                $block->data['current'],
                1 < $block->data['current'] ? 's' : '',
                ( $block->data['total'] - $block->data['current'] ),
                1 < ( $block->data['total'] - $block->data['current'] ) ? 's' : '',
                $block->data['rewatch'],
                1 < $block->data['rewatch'] ? 's' : ''
            ); ?></p>
        <div class="items">
            <ul>
<?php
foreach ( $block->data['movies'] as $movie ) :
    $icon = $movie['rewatch'] ? 'double-check' : 'check';
    $status = '';
    $status .= $movie['watched'] ? ' has-been-watched' : ' not-watched';
    $status .= $movie['rewatch'] ? ' is-rewatch' : '';
    $availability = $movie['available'] ? ' is-available': ' not-available';
?>
                <li>
                    <span class="item-status<?php echo esc_attr( $status ); ?>"><?php the_theme_svg( $icon ); ?></span>
                    <span class="item-availability<?php echo esc_attr( $availability ); ?>"><?php the_theme_svg( $movie['available'] ? 'dvd-check': 'dvd-no' ); ?></span>
                    <span class="item-title"><?php echo esc_html( $movie['title'] ); ?></span>
                </li>
<?php endforeach; ?>
            </ul>
            <h3>Bonus</h3>
            <p>Films à voir en bonus. Ne font pas partie du marathon à proprement parler, mais offre une satisfaction supplémentaire.</p>
            <ul>
<?php
foreach ( $block->data['bonuses'] as $movie ) :
    $icon = $movie['rewatch'] ? 'double-check' : 'check';
    $status = '';
    $status .= $movie['watched'] ? ' has-been-watched' : ' not-watched';
    $status .= $movie['rewatch'] ? ' is-rewatch' : '';
    $availability = $movie['available'] ? ' is-available': ' not-available';
?>
                <li>
                    <span class="item-status<?php echo esc_attr( $status ); ?>"><?php the_theme_svg( $icon ); ?></span>
                    <span class="item-availability<?php echo esc_attr( $availability ); ?>"><?php the_theme_svg( $movie['available'] ? 'dvd-check': 'dvd-no' ); ?></span>
                    <span class="item-title"><?php echo esc_html( $movie['title'] ); ?></span>
                </li>
<?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>