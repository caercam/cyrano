<?php

function get_total_posts_from_category_for_year( $category, $year ) {

    $query = new WP_Query( [
        'post_type' => 'post',
        'post_status' => 'publish',
        'year' => $year,
        'tax_query' => [
            [
                'taxonomy' => 'post_format',
                'field' => 'slug',
                'terms' => [ 'post-format-status' ],
            ],
            [
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => [ $category ],
            ]
        ],
        'rields' => 'ids',
    ] );

    return $query->found_posts;
}

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

        'check' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M448 130L431 147 177.5 399.2l-16.9 16.9-16.9-16.9L17 273.1 0 256.2l33.9-34 17 16.9L160.6 348.3 397.1 112.9l17-16.9L448 130z"/></svg>',
        'double-check' => '<svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M337 81l17-17L320 30.1 303 47l-143 143L97 127l-17-17L46.1 144l17 17 80 80 17 17 17-17L337 81zm94 130L448 194l-33.9-34-17 16.9L160.6 412.3 50.8 303.1l-17-16.9L0 320.2l17 16.9L143.7 463.2l16.9 16.9 16.9-16.9L431 211z"/></svg>',
        'dvd-check' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M0 256C0 164.5 48.8 80 128 34.3s176.8-45.7 256 0c60.3 34.8 103 92.1 119.9 157.9c-2.6-.1-5.3-.2-7.9-.2c-60 0-113 30-144.7 75.8c.5-3.9 .7-7.8 .7-11.8c0-34.3-18.3-66-48-83.1s-66.3-17.1-96 0s-48 48.8-48 83.1s18.3 66 48 83.1s66.3 17.1 96 0c8.4-4.8 15.8-10.8 22.2-17.7c-4.1 14.8-6.2 30.4-6.2 46.5c0 45.9 17.6 87.6 46.4 119c-75.7 36.2-164.9 33.1-238.4-9.3C48.8 432 0 347.5 0 256zm64 0c10.7 0 21.3 0 32 0c0-88.4 71.6-160 160-160c0-10.7 0-21.3 0-32C150 64 64 150 64 256zm160 0c0-17.7 14.3-32 32-32s32 14.3 32 32s-14.3 32-32 32s-32-14.3-32-32zM352 368c0-79.5 64.5-144 144-144s144 64.5 144 144s-64.5 144-144 144s-144-64.5-144-144zm65.4 0L480 430.6c31.5-31.5 63.1-63.1 94.6-94.6c-7.5-7.5-15.1-15.1-22.6-22.6c-24 24-48 48-72 72c-13.3-13.3-26.7-26.7-40-40c-7.5 7.5-15.1 15.1-22.6 22.6z"/></svg>',
        'dvd-no' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M0 256C0 164.5 48.8 80 128 34.3s176.8-45.7 256 0c60.3 34.8 103 92.1 119.9 157.9c-2.6-.1-5.3-.2-7.9-.2c-60 0-113 30-144.7 75.8c.5-3.9 .7-7.8 .7-11.8c0-34.3-18.3-66-48-83.1s-66.3-17.1-96 0s-48 48.8-48 83.1s18.3 66 48 83.1s66.3 17.1 96 0c8.4-4.8 15.8-10.8 22.2-17.7c-4.1 14.8-6.2 30.4-6.2 46.5c0 45.9 17.6 87.6 46.4 119c-75.7 36.2-164.9 33.1-238.4-9.3C48.8 432 0 347.5 0 256zm64 0c10.7 0 21.3 0 32 0c0-88.4 71.6-160 160-160c0-10.7 0-21.3 0-32C150 64 64 150 64 256zm160 0c0-17.7 14.3-32 32-32s32 14.3 32 32s-14.3 32-32 32s-32-14.3-32-32zM352 368c0-79.5 64.5-144 144-144s144 64.5 144 144s-64.5 144-144 144s-144-64.5-144-144zm73.4-48l48 48c-16 16-32 32-48 48c7.5 7.5 15.1 15.1 22.6 22.6c16-16 32-32 48-48l48 48c7.5-7.5 15.1-15.1 22.6-22.6c-16-16-32-32-48-48c16-16 32-32 48-48c-7.5-7.5-15.1-15.1-22.6-22.6c-16 16-32 32-48 48c-16-16-32-32-48-48c-7.5 7.5-15.1 15.1-22.6 22.6z"/></svg>',
        'dvd-check-light' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M0 256C0 164.5 48.8 80 128 34.3s176.8-45.7 256 0c60.3 34.8 103 92.1 119.9 157.9c-2.6-.1-5.3-.2-7.9-.2c-8.4 0-16.7 .6-24.8 1.7C455.3 138.9 418.8 91.3 368 62c-69.3-40-154.7-40-224 0S32 176 32 256s42.7 154 112 194c62.3 36 137.6 39.6 202.5 10.9c5.8 9.3 12.5 18 19.8 26.1c-75.7 36.2-164.9 33.1-238.4-9.3C48.8 432 0 347.5 0 256zm80 0c0-97.2 78.8-176 176-176c0 10.7 0 21.3 0 32c-79.5 0-144 64.5-144 144c-10.7 0-21.3 0-32 0zm80 0c0-34.3 18.3-66 48-83.1s66.3-17.1 96 0s48 48.8 48 83.1c0 4-.2 7.9-.7 11.8c-11.2 16.1-19.7 34.2-25.1 53.6c-6.4 6.9-13.9 12.8-22.2 17.7c-29.7 17.1-66.3 17.1-96 0s-48-48.8-48-83.1zm32 0c0 22.9 12.2 44 32 55.4s44.2 11.4 64 0s32-32.6 32-55.4s-12.2-44-32-55.4s-44.2-11.4-64 0s-32 32.6-32 55.4zm40 0c0-13.3 10.7-24 24-24s24 10.7 24 24s-10.7 24-24 24s-24-10.7-24-24zM352 368c0-51.4 27.4-99 72-124.7s99.4-25.7 144 0s72 73.3 72 124.7s-27.4 99-72 124.7s-99.4 25.7-144 0S352 419.4 352 368zm32 0c0 40 21.3 77 56 97s77.3 20 112 0s56-57 56-97s-21.3-77-56-97s-77.3-20-112 0s-56 57-56 97zm33.4 0c7.5-7.5 15.1-15.1 22.6-22.6c3.8 3.8 7.5 7.5 11.3 11.3c9.6 9.6 19.1 19.1 28.7 28.7c20.2-20.2 40.5-40.5 60.7-60.7c3.8-3.8 7.5-7.5 11.3-11.3c7.5 7.5 15.1 15.1 22.6 22.6c-3.8 3.8-7.5 7.5-11.3 11.3c-24 24-48 48-72 72c-3.8 3.8-7.5 7.5-11.3 11.3l-11.3-11.3-40-40L417.4 368z"/></svg>',
        'dvd-no-light' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M0 256C0 164.5 48.8 80 128 34.3s176.8-45.7 256 0c60.3 34.8 103 92.1 119.9 157.9c-2.6-.1-5.3-.2-7.9-.2c-8.4 0-16.7 .6-24.8 1.7C455.3 138.9 418.8 91.3 368 62c-69.3-40-154.7-40-224 0S32 176 32 256s42.7 154 112 194c62.3 36 137.6 39.6 202.5 10.9c5.8 9.3 12.5 18 19.8 26.1c-75.7 36.2-164.9 33.1-238.4-9.3C48.8 432 0 347.5 0 256zm80 0c0-97.2 78.8-176 176-176c0 10.7 0 21.3 0 32c-79.5 0-144 64.5-144 144c-10.7 0-21.3 0-32 0zm80 0c0-34.3 18.3-66 48-83.1s66.3-17.1 96 0s48 48.8 48 83.1c0 4-.2 7.9-.7 11.8c-11.2 16.1-19.7 34.2-25.1 53.6c-6.4 6.9-13.9 12.8-22.2 17.7c-29.7 17.1-66.3 17.1-96 0s-48-48.8-48-83.1zm32 0c0 22.9 12.2 44 32 55.4s44.2 11.4 64 0s32-32.6 32-55.4s-12.2-44-32-55.4s-44.2-11.4-64 0s-32 32.6-32 55.4zm40 0c0-13.3 10.7-24 24-24s24 10.7 24 24s-10.7 24-24 24s-24-10.7-24-24zm139.3 40c25.7-44.6 73.3-72 124.7-72s99 27.4 124.7 72s25.7 99.4 0 144S547.4 512 496 512s-99-27.4-124.7-72s-25.7-99.4 0-144zM399 312c-20 34.7-20 77.3 0 112s57 56 97 56s77-21.3 97-56s20-77.3 0-112s-57-56-97-56s-77 21.3-97 56zm26.4 8c7.5-7.5 15.1-15.1 22.6-22.6c3.8 3.8 7.5 7.5 11.3 11.3c12.2 12.2 24.5 24.5 36.7 36.7c12.2-12.2 24.5-24.5 36.7-36.7c3.8-3.8 7.5-7.5 11.3-11.3c7.5 7.5 15.1 15.1 22.6 22.6c-3.8 3.8-7.5 7.5-11.3 11.3c-12.2 12.2-24.5 24.5-36.7 36.7c12.2 12.2 24.5 24.5 36.7 36.7c3.8 3.8 7.5 7.5 11.3 11.3c-7.5 7.5-15.1 15.1-22.6 22.6l-11.3-11.3L496 390.6c-12.2 12.2-24.5 24.5-36.7 36.7c-3.8 3.8-7.5 7.5-11.3 11.3c-7.5-7.5-15.1-15.1-22.6-22.6c3.8-3.8 7.5-7.5 11.3-11.3c12.2-12.2 24.5-24.5 36.7-36.7l-36.7-36.7L425.4 320z"/></svg>',
    ];

    return $icons[ $name ] ?? '';
}

function the_theme_svg( $name ) {
    
    echo get_the_theme_svg( $name );
}