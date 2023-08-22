<?php

function get_all_post_years() {

    global $wpdb;

    $result = [];

    $years = $wpdb->get_results( "SELECT YEAR(post_date) as post_year, COUNT(*) as post_total FROM {$wpdb->posts} WHERE post_status = 'publish' GROUP BY post_year ORDER BY post_year ASC", ARRAY_N );

    if ( is_array( $years ) && 0 < count( $years ) ) {
        foreach ( $years as $year ) {
            $result[ $year[0] ] = $year[1];
        }
    }

    $keys = array_keys( $result );
    $start = array_shift( $keys );
    $end = array_pop( $keys );

    $result = $result + array_fill_keys( range( $start, $end ), 0 );

    ksort( $result );

    return $result;
}

function get_the_theme_svg( $name ) {

    $icons = [
        'angle-left' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M41.4 256l11.3-11.3 160-160L224 73.4 246.6 96l-11.3 11.3L86.6 256 235.3 404.7 246.6 416 224 438.6l-11.3-11.3-160-160L41.4 256z"/></svg>',
        'angle-right' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M278.6 256l-11.3 11.3-160 160L96 438.6 73.4 416l11.3-11.3L233.4 256 84.7 107.3 73.4 96 96 73.4l11.3 11.3 160 160L278.6 256z"/></svg>',
    ];

    return $icons[ $name ] ?? '';
}

function the_theme_svg( $name ) {
    
    echo get_the_theme_svg( $name );
}