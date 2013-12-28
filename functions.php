<?php

/**
 * Sets up theme defaults and registers the various WordPress features that
 * Cyrano supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add Visual Editor stylesheets.
 * @uses add_theme_support() To add support for automatic feed links, post
 * formats, and post thumbnails.
 * @uses register_nav_menu() To add support for a navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Cyrano 1.0
 *
 * @return void
 */
function cyrano_setup() {
	/*
	 * Makes Twenty Thirteen available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Thirteen, use a find and
	 * replace to change 'cyrano' to the name of your theme in all
	 * template files.
	 */
	load_theme_textdomain( 'cyrano', get_template_directory() . '/languages' );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	//add_editor_style( array( 'css/editor-style.css', 'fonts/genericons.css', twentythirteen_fonts_url() ) );

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Switches default core markup for search form, comment form, and comments
	// to output valid HTML5.
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	/*
	 * This theme supports all available post formats by default.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
	) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Navigation Menu', 'cyrano' ) );

	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
}
add_action( 'after_setup_theme', 'twentythirteen_setup' );

/**
 * Registers two widget areas.
 *
 * @since Cyrano 1.0
 *
 * @return void
 */
function cyrano_widgets_init() {

	register_sidebar( array(
		'name'          => __( 'Main Widget Area', 'cyrano' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Appears in the footer section of the site.', 'cyrano' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
		'name'          => __( 'Secondary Widget Area', 'cyrano' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Appears on posts and pages in the sidebar.', 'cyrano' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'cyrano_widgets_init' );

function cyrano_scripts() {

	wp_register_style( 'cyrano', get_stylesheet_uri(), array(), false, 'all' );
	wp_register_style( 'open-sans', "//fonts.googleapis.com/css?family=Open+Sans:300,700,800,600,400", array(), false, 'all' );

	wp_register_script( 'cyrano', get_template_directory_uri() . '/assets/js/public.js', array(), false, true );

	wp_enqueue_style( 'open-sans' );
	wp_enqueue_style( 'cyrano' );

	wp_enqueue_script( 'cyrano' );
}
add_action( 'wp_enqueue_scripts', 'cyrano_scripts' );


function cyrano_featured() {

	if ( is_sticky() && is_home() && ! is_paged() ) :
?>
						<div class="featured-post"><span class="entypo">&#9733;</span> &nbsp; <?php _e( 'Featured', 'cyrano' ) ?></div>
<?php
	endif;

}

/**
 * Prints HTML with date information for current post.
 *
 * Create your own cyrano_entry_date() to override in a child theme.
 *
 * @since Cyrano 1.0
 *
 * @param boolean $echo Whether to echo the date. Default true.
 *
 * @return string The HTML-formatted post date.
 */
function cyrano_entry_date( $echo = true ) {

	if ( has_post_format( array( 'chat', 'status' ) ) )
		$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'cyrano' );
	else
		$format_prefix = '%2$s';

	$date = sprintf( '<span class="date"><a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( sprintf( __( 'Permalink to %s', 'cyrano' ), the_title_attribute( 'echo=0' ) ) ),
		esc_attr( get_the_date( 'c' ) ),
		sprintf( '<span class="day">%s</span> <span class="monthyear"><span class="month">%s</span> <span class="year">%s</span></span>', get_the_date('j'), get_the_date('M'), get_the_date('Y') )
	);

	if ( $echo )
		echo $date;

	return $date;
}

/**
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own cyrano_entry_meta() to override in a child theme.
 *
 * @since Cyrano 1.0
 *
 * @return void
 */
function cyrano_entry_meta() {

	$author = $tags = $categories = $more = '';

	// Post author
	if ( 'post' == get_post_type() ) {
		$author = sprintf( '<li class="post-author"><span class="author vcard"><span class="entypo">&#128100;</span> &nbsp;<a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span></li>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'cyrano' ), get_the_author() ) ),
			get_the_author()
		);
	}

	$categories = get_the_category_list( __( ', ', 'cyrano' ) );
	if ( $categories ) {
		$c = explode( __( ', ', 'cyrano' ), $categories );
		if ( count( $c ) > 3 )
			$categories = implode( __( ', ', 'cyrano' ), array_slice( $c, 0, 3 ) ) . '… (' . ( count( $c ) - 3 ) . ' more)';

		$categories = sprintf( '<li class="post-categories"><span class="entypo">&#128193;</span> &nbsp;%s</li>', $categories );
	}

	$tags = get_the_tag_list( '', __( ', ', 'cyrano' ) );
	if ( $tags ) {
		$t = explode( __( ', ', 'cyrano' ), $tags );
		if ( count( $t ) > 3 )
			$tags = implode( __( ', ', 'cyrano' ), array_slice( $t, 0, 3 ) ) . '… (' . ( count( $t ) - 3 ) . ' more)';

		$tags = sprintf( '<li class="post-tags"><span class="entypo">&#59148;</span> &nbsp;%s</li>', $tags );
	}

	$more = sprintf( '<li class="post-more"><a href="%s" class="more">%s</a></li>', get_permalink(), __( 'Read More', 'cyrano' ) );
?>
						<ul>
							<?php echo $author ?>
							<?php echo $categories ?>
							<?php echo $tags ?>
							<?php echo $more ?>
						</ul>
<?php

}

/**
 * Displays navigation to next/previous set of posts when applicable.
 *
 * @since Cyrano 1.0
 *
 * @return void
 */
function cyrano_paging_nav() {

	global $wp_query, $page;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
?>

				<div class="pagination">
					<nav class="paging-navigation" role="pagination">
<?php if ( get_previous_posts_link() ) : ?>
						<div class="recent-posts"><?php previous_posts_link( __( '<span class="meta-nav">&larr;</span> Newer posts', 'cyrano' ) ); ?></div><?php endif; ?>
<?php if ( get_next_posts_link() ) : ?>
						<div class="older-posts"><?php next_posts_link( __( 'Older posts <span class="meta-nav">&rarr;</span>', 'cyrano' ) ); ?></div><?php endif; ?>
						<div class="page-number"><?php printf( __( 'Page %d of %d', 'cyrano' ), ( $wp_query->get('paged') ? $wp_query->get('paged') : 1 ), $wp_query->max_num_pages ) ?></div>
					</nav>
					<div style="clear:both"></div>
				</div>
<?php
}

function cyrano_post_cover( $post_id = null, $echo = true ) {

	$html = '';

	if ( is_null( $post_id ) ) {
		global $post;
		$post_id = $post->ID;
	}

	$t_id = get_post_thumbnail_id( $post_id );
	$thumbnail = wp_get_attachment_image_src( $t_id, 'large' );

	if ( ! $thumbnail ) {
		$html = sprintf( '<img class="landscape" src="%s" width="896" height="480" alt="%s" />'."\n", get_template_directory_uri() . '/assets/img/default_cover.jpg', get_bloginfo('name') );
	}
	else {
		$w = $thumbnail[1];
		$h = $thumbnail[2];

		$landscape = ( $w > $h );
		$wide      = ( $w > ( 2 * $h ) );

		if ( $wide ) {
			$class = 'class="wide"';
		}
		else if ( $landscape ) {
			$class = 'class="landscape"';
		}
		else {
			$class = '';
		}

		$html = sprintf( '<img %s src="%s" width="%d" height="%d" alt="%s" />'."\n", $class, $thumbnail[0], $w, $h, get_the_title( $post_id ) );
	}

	if ( $echo )
		echo $html;
	else
		return $html;
}







