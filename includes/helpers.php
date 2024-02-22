<?php

/**
 * Generates a full year calendar for a given year.
 * 
 * @since 2.2.0
 * 
 * @param int $year The year for build the calendar.
 * @return array The calendar array.
 */
function get_full_year_calendar( $year ) {

    // Initialize the calendar array
    $calendar = [];

    // Loop through each month of the year
    for ( $month = 1; $month <= 12; $month++ ) {

        // Get the number of days in the month
        $numDays = cal_days_in_month( CAL_GREGORIAN, $month, $year );

        // Initialize the calendar array for the month
        $calendar[ $month ] = [];

        // Get the first day of the week for the month
        $firstDayOfWeek = date( 'N', strtotime( "$year-$month-01" ) );

        // Initialize the week and day of the week
        $week = 1;

        // Add empty days for days before the first day of the month
        $calendar[ $month ][ $week ] = [];

        for ( $i = 1; $i < $firstDayOfWeek; $i++ ) {
            $calendar[ $month ][ $week ][ $i ] = 0;
        }

        // Loop through each day of the month
        for ( $day = 1; $day <= $numDays; $day++ ) {
            // Add the day to the calendar
            $calendar[ $month ][ $week ][ $firstDayOfWeek ] = $day;

            // Increment the day and the day of the week
            $firstDayOfWeek++;
            // If it's Sunday (7), start a new week
            if ( 8 === $firstDayOfWeek ) {
                $firstDayOfWeek = 1;
                $week++;
                $calendar[ $month ][ $week ] = [];
            }
        }

        // Add empty days for days after the last day of the month
        while ( 7 >= $firstDayOfWeek ) {
            $calendar[ $month ][ $week ][ $firstDayOfWeek ] = 0;
            $firstDayOfWeek++;
        }
    }

    return $calendar;
}

/**
 * Retrieves the rating for a given post.
 *
 * @since 2.2.0
 *
 * @param int|WP_Post|null $post Optional. Post ID or WP_Post object. Default is global $post.
 * @return string Returns HTML for displaying the rating stars.
 */
function get_the_rating( $post = null ) {

    // Retrieve the post object
    $post = get_post( $post );
    
    // Check if post exists
    if ( ! $post ) {
        return '';
    }

    // Retrieve the rating terms associated with the post
    $rating = get_the_terms( $post, 'rating' );

    // If no rating terms found or it's a WP_Error, return empty string
    if ( ! $rating || ! count( $rating ) || is_wp_error( $rating ) ) {
        return '';
    }

    // Initialize variables for rating calculation
    $none = 0;
    $half = 0;
    $full = 0;

    // Extract the first rating term
    $rating = array_shift( $rating );

    // Determine the number of full, half, and empty stars based on the rating term
    switch ( $rating->name ) {
        case '1.0':
        case '2.0':
        case '3.0':
        case '4.0':
        case '5.0':
            $full = intval( $rating->name );
            $half = 0;
            $none = 5 - $full;
            break;
        case '0.5':
        case '1.5':
        case '2.5':
        case '3.5':
        case '4.5':
            $full = intval( $rating->name );
            $half = 1;
            $none = 4 - $full;
            break;
        default:
            $rating = ''; // If rating doesn't match, set it to empty
            break;
    }

    $stars = str_repeat( times: $full, string: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M10 1l3 6 6 .75-4.12 4.62L16 19l-6-3-6 3 1.13-6.63L1 7.75 7 7z"/></g></svg>' )
        . str_repeat( times: $half, string: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M10 1L7 7l-6 .75 4.13 4.62L4 19l6-3 6 3-1.12-6.63L19 7.75 13 7zm0 2.24l2.34 4.69 4.65.58-3.18 3.56.87 5.15L10 14.88V3.24z"/></g></svg>' )
        . str_repeat( times: $none, string: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><rect x="0" fill="none" width="20" height="20"/><g><path d="M10 1L7 7l-6 .75 4.13 4.62L4 19l6-3 6 3-1.12-6.63L19 7.75 13 7zm0 2.24l2.34 4.69 4.65.58-3.18 3.56.87 5.15L10 14.88l-4.68 2.34.87-5.15-3.18-3.56 4.65-.58z"/></g></svg>' );

    // Return the HTML with rating stars and title
    return '<span class="stars" title="' . $rating->name . ' − ' . $rating->description . '">' . $stars . '</span>';
}

/**
 * Retrieves the total number of posts for a category and a given year.
 * 
 * @since 2.2.0
 *
 * @param int $year The year for which to retrieve the post data.
 * @return int The total number of posts for the given year.
 */
function get_total_posts_from_category_for_year( $category, $year ) {

    // Create a new WP_Query object to retrieve the total number of posts for a given year and category
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

    // Return the total number of posts
    return $query->found_posts;
}

/**
 * Retrieves the total number of posts per year.
 * 
 * @since 2.2.0
 *
 * @return array An array of objects containing the post year and the total number of posts for that year.
 */
function get_all_post_years() {

    global $wpdb;

    // Initialize the result array
    $result = [];

    // Retrieve the total number of posts for each year
    $years = $wpdb->get_results( "SELECT YEAR(post_date) as post_year, COUNT(*) as post_total FROM {$wpdb->posts} WHERE post_status = 'publish' GROUP BY post_year ORDER BY post_year ASC", ARRAY_N );

    // Loop through the results and add the total number of posts for each year to the result array
    if ( is_array( $years ) && 0 < count( $years ) ) {
        foreach ( $years as $year ) {
            $result[ $year[0] ] = $year[1];
        }
    }

    // Fill in any missing years with a total of 0 posts
    $keys = array_keys( $result );
    // Get the first and last year
    $start = array_shift( $keys );
    // Get the first and last year
    $end = array_pop( $keys );

    // Fill in any missing years with a total of 0 posts
    $result = $result + array_fill_keys( range( $start, $end ), 0 );

    // Sort the result array by year
    ksort( $result );

    $years = [];

    // Loop through the result array and add the year and total number of posts to the years array
    foreach ( $result as $year => $total ) {
        $years[] = compact( 'year', 'total' );
    }

    // Return the years array
    return $result;
}

/**
 * Retrieves the total number of posts per month for a given year.
 * 
 * @since 2.2.0
 *
 * @param int $year The year for which to retrieve the post data.
 * @return array An array of objects containing the post month and the total number of posts for that month.
 */
function get_all_post_months( $year ) {

    global $wpdb;

    // Get the global WP_Locale object
    $wp_locale = new WP_Locale;

    // Initialize the result array
    $result = [];

    // Retrieve the total number of posts for each month
    $months = $wpdb->get_results(
        $wpdb->prepare( "SELECT MONTH(post_date) as post_month, COUNT(*) as post_total FROM {$wpdb->posts} WHERE post_status = 'publish' AND YEAR(post_date) = %d GROUP BY post_month ORDER BY post_month ASC", $year ),
        ARRAY_N
    );

    // Loop through the results and add the total number of posts for each month to the result array
    if ( is_array( $months ) && 0 < count( $months ) ) {
        foreach ( $months as $month ) {
            $result[ $month[0] ] = $month[1];
        }
    }

    // Fill in any missing months with a total of 0 posts
    $result = $result + array_fill_keys( range( 1, 12 ), 0 );

    // Sort the result array by month
    ksort( $result );

    $months = [];

    // Loop through the result array and add the month and total number of posts to the months array
    foreach ( $result as $month => $total ) {
        $months[ $month ] = [
            'month' => $wp_locale->get_month( $month ),
            'total' => $total,
        ];
    }

    // Return the months array
    return $months;
}

/**
 * Retrieves the total number of posts per day for a given year.
 * 
 * @since 2.2.0
 *
 * @param int $year The year for which to retrieve the post data.
 * @return array An array of objects containing the post day and the total number of posts for that day.
 */
function get_year_graph( $year ) {

    // Get the global database object
    global $wpdb;

    // Check if the request is for a specific category or tag
    if ( is_tax() ) {
        // Retrieve the post data for the specific category or tag
        $results = $wpdb->get_results(
            // The SQL query retrieves the post date and the total number of posts for each day for a given year. The results are grouped by the post day and ordered by the post day.
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
        // Retrieve the post data for all posts
        $results = $wpdb->get_results(
            // The SQL query retrieves the post date and the total number of posts for each day for a given year. The results are grouped by the post day and ordered by the post day.
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

    // Initialize the days array
    $current = new DateTime( "$year-01-01" );
    // Create a new DateTime object for the end of the year
    $end = new DateTime( "$year-12-31" );
    // Create a new DateInterval object for one day
    $interval = new DateInterval( 'P1D' );

    // Loop through each day of the year
    $days = [];
    while ( $current <= $end ) {
        // Format the date as a string
        $date = $current->format( 'Y-m-d' );
        // Add the date to the days array with a total of 0 posts
        $days[ $date ] = 0;
        // Increment the current date by one day
        $current->add( $interval );
    }

    // Loop through the results and add the total number of posts for each day to the days array
    foreach ( $results as $row ) {
        $days[ $row->post_day ] = $row->total_posts;
    }

    // Return the days array
    return $days;
}

/**
 * Retrieves the total number of posts per day for a given month and year.
 * 
 * @since 2.2.0
 *
 * @param int $year The year for which to retrieve the post data.
 * @param int $month The month for which to retrieve the post data.
 * @return array An array of objects containing the post day and the total number of posts for that day.
 */
function get_month_graph( $year, $month ) {

    global $wpdb;

    // Initialize the tax and term variables
    $tax = null;

    // Check if the request is for a specific category or tag
    if ( isset( $_GET['categorie'] ) ) {
        $tax = 'category';
        $term = $_GET['categorie'];
    // Check if the request is for a specific category or tag
    } elseif ( isset( $_GET['tag'] ) ) {
        $tax = 'post_tag';
        $term = $_GET['tag'];
    }

    // Check if the request is for a specific category or tag
    if ( $tax ) {
        // Retrieve the post data for the specific category or tag
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
        // Retrieve the post data for the specific category or tag
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
        // Retrieve the post data for all posts
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

    // Create a new DateTime object for the first day of the month
    $start = new DateTime( "$year-$month-01" );
    // Create a new DateTime object for the last day of the month
    $end = new DateTime( "$year-$month-01" );
    // Modify the end date to the last day of the month
    $end->modify( 'last day of this month' );
    
    // Loop through each day of the month
    while ( $start <= $end ) {
        // Format the date as a string
        $date = $start->format( 'Y-m-d' );
        // Add the date to the days array with a total of 0 posts
        $days[ $date ] = 0;
        // Increment the current date by one day
        $start->add( new DateInterval( 'P1D' ) );
    }

    // Loop through the results and add the total number of posts for each day to the days array
    foreach ( $results as $row ) {
        $days[ $row->post_day ] = $row->total_posts;
    }

    // Return the days array
    return $days;
}

/**
 * Retrieves the watchlists for a given month and year.
 * 
 * @since 2.4.0
 *
 * @param int $year The year for which to retrieve the post data.
 * @param int $month The month for which to retrieve the post data.
 * @return array An array of posts containing the watchlists for the given month and year.
 */
function get_watchlists_for_the_month( $year = null, $month = null ) {

    $year = $year ?? get_the_date( 'Y' );
    $month = $month ?? get_the_date( 'n' );

    $watchlists = get_posts( [
        'post_type' => 'watchlist',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'date_query' => [
            'year' => $year,
            'month' => $month,
        ],
    ] );

    return $watchlists;
}

/**
 * Retrieves the total number of posts per day for a given month and year.
 * 
 * @since 2.2.0
 *
 * @param int $year The year for which to retrieve the post data.
 * @param int $month The month for which to retrieve the post data.
 * @return array An array of objects containing the post day and the total number of posts for that day.
 */
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

    // Return the SVG markup
    return $icons[ $name ] ?? '';
}

/**
 * Displays the SVG markup for a given SVG name.
 *
 * @since 2.2.0
 *
 * @param string $name The name of the SVG.
 */
function the_theme_svg( $name ) {
    
    echo get_the_theme_svg( $name );
}