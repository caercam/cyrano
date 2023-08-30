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

    $years = [];
    foreach ( $result as $year => $total ) {
        $years[] = compact( 'year', 'total' );
    }

    return $result;
}

function get_all_post_months( $year ) {

    global $wpdb;

    $wp_locale = new WP_Locale;

    $result = [];

    $months = $wpdb->get_results(
        $wpdb->prepare( "SELECT MONTH(post_date) as post_month, COUNT(*) as post_total FROM {$wpdb->posts} WHERE post_status = 'publish' AND YEAR(post_date) = %d GROUP BY post_month ORDER BY post_month ASC", $year ),
        ARRAY_N
    );

    if ( is_array( $months ) && 0 < count( $months ) ) {
        foreach ( $months as $month ) {
            $result[ $month[0] ] = $month[1];
        }
    }

    $result = $result + array_fill_keys( range( 1, 12 ), 0 );

    ksort( $result );

    $months = [];
    foreach ( $result as $month => $total ) {
        $months[ $month ] = [
            'month' => $wp_locale->get_month( $month ),
            'total' => $total,
        ];
    }

    return $months;
}

function get_year_graph( $year ) {

    global $wpdb;

    if ( is_tax() ) {
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT DATE(p.post_date) AS post_day, COUNT(*) AS total_posts
                    FROM {$wpdb->posts} AS p
                    INNER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id
                    INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    WHERE YEAR(p.post_date) = %d
                        AND p.post_type = 'post'
                        AND p.post_status = 'publish'
                        AND tt.term_id = %d
                    GROUP BY post_day
                    ORDER BY post_day;",
                $year,
                get_queried_object_id()
            )
        );
    } else {
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT DATE(post_date) AS post_day, COUNT(*) AS total_posts
                    FROM {$wpdb->posts}
                    WHERE YEAR(post_date) = %d
                    AND post_type = 'post'
                    AND post_status = 'publish'
                    GROUP BY post_day
                    ORDER BY post_day;",
                $year
            )
        );
    }

    $current = new DateTime( "$year-01-01" );
    $end = new DateTime( "$year-12-31" );
    $interval = new DateInterval( 'P1D' );

    $days = [];
    while ( $current <= $end ) {
        $date = $current->format( 'Y-m-d' );
        $days[ $date ] = 0;
        $current->add( $interval );
    }

    foreach ( $results as $row ) {
        $days[ $row->post_day ] = $row->total_posts;
    }

    return $days;
}

function get_month_graph( $year, $month ) {

    global $wpdb;

    $tax = null;
    if ( isset( $_GET['categorie'] ) ) {
        $tax = 'category';
        $term = $_GET['categorie'];
    } elseif ( isset( $_GET['tag'] ) ) {
        $tax = 'post_tag';
        $term = $_GET['tag'];
    }

    if ( $tax ) {
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT DATE(p.post_date) AS post_day, COUNT(*) AS total_posts
                    FROM {$wpdb->posts} AS p
                    INNER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id
                    INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    INNER JOIN {$wpdb->terms} AS t ON t.term_id = tt.term_id
                    WHERE YEAR(p.post_date) = %d
                        AND MONTH(p.post_date) = %d
                        AND p.post_type = 'post'
                        AND p.post_status = 'publish'
                        AND tt.taxonomy = %s
                        AND t.slug = %s
                    GROUP BY post_day
                    ORDER BY post_day;",
                $year,
                $month,
                $tax,
                $term
            )
        );
    } elseif ( is_tax() ) {
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT DATE(p.post_date) AS post_day, COUNT(*) AS total_posts
                    FROM {$wpdb->posts} AS p
                    INNER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id
                    INNER JOIN {$wpdb->term_taxonomy} AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
                    WHERE YEAR(p.post_date) = %d
                        AND MONTH(p.post_date) = %d
                        AND p.post_type = 'post'
                        AND p.post_status = 'publish'
                        AND tt.term_id = %d
                    GROUP BY post_day
                    ORDER BY post_day;",
                $year,
                $month,
                get_queried_object_id()
            )
        );
    } else {
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT DATE(post_date) AS post_day, COUNT(*) AS total_posts
                    FROM {$wpdb->posts}
                    WHERE YEAR(post_date) = %d
                        AND MONTH(post_date) = %d
                        AND post_type = 'post'
                        AND post_status = 'publish'
                    GROUP BY post_day
                    ORDER BY post_day;",
                $year,
                $month
            )
        );
    }

    $days = [];
    
    $start = new DateTime( "$year-$month-01" );
    $end = new DateTime( "$year-$month-01" );
    $end->modify( 'last day of this month' );
    
    while ( $start <= $end ) {
        $date = $start->format( 'Y-m-d' );
        $days[ $date ] = 0;
        $start->add( new DateInterval( 'P1D' ) );
    }

    foreach ( $results as $row ) {
        $days[ $row->post_day ] = $row->total_posts;
    }

    return $days;
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