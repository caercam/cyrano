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
add_action( 'after_setup_theme', 'cyrano_setup' );

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

/**
 * Enqueues scripts and styles for front end.
 *
 * @since Cyrano 1.0
 */
function cyrano_scripts() {

	wp_register_style( 'cyrano', get_stylesheet_uri(), array(), false, 'all' );
	wp_register_style( 'open-sans', "//fonts.googleapis.com/css?family=Open+Sans:300,700,800,600,400", array(), false, 'all' );

	wp_register_script( 'cyrano', get_template_directory_uri() . '/assets/js/public.js', array( 'jquery' ), false, true );

	wp_enqueue_style( 'open-sans' );
	wp_enqueue_style( 'cyrano' );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'cyrano' );
}
add_action( 'wp_enqueue_scripts', 'cyrano_scripts' );


/**
 * Displays a Custom Icon for Post Formats.
 *
 * @since Cyrano 1.0
 */
function cyrano_post_format( $post_id = null ) {

	if ( is_null( $post_id ) ) {
		global $post;
		$post_id = $post->ID;
	}

	$icons = array(
		'post'    => '&#9998;',
		'page'    => '&#10002;',
		'aside'   => '&#128319;',
		'audio'   => '&#127925;',
		'chat'    => '&#59168;',
		'gallery' => '&#127748;',
		'image'   => '&#128247;',
		'link'    => '&#128279;',
		'quote'   => '&#10078;',
		'status'  => '&#9000;',
		'video'   => '&#127916;'
	);

	$post_format = get_post_format( $post_id );

	echo '<div class="post-type"><span class="entypo">' . ( false === $post_format ? $icons['post'] : $icons[ $post_format ] ). '</span></div>';
}

/**
 * Displays a Featured Post featureness.
 *
 * @since Cyrano 1.0
 */
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
 * Prints HTML with meta information for current post: categories, tags,
 * permalink, author, and date.
 *
 * @since Cyrano 1.0
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

	if ( ! is_single() )
		$more = sprintf( '<li class="post-more more"><a href="%s" class="more">%s</a></li>', get_permalink(), __( 'Read More', 'cyrano' ) );
	else if ( is_single() && comments_open() )
		$more = '<li class="post-more more-comment"><a href="' . get_comments_link() . '">' . __( 'Leave a comment', 'cyrano' ) . '</a></li>';
	else
		$more = '<li class="post-more void"><a>' . __( 'Comments closed.', 'cyrano' ) . '</a></li>';
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
 */
function cyrano_paging_nav() {

	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;

	$page  = ( $wp_query->get('paged') ? $wp_query->get('paged') : 1 );
	$total = $wp_query->max_num_pages;
?>

				<div class="pagination">
					<nav class="paging-navigation" role="pagination">
<?php if ( get_previous_posts_link() ) : ?>
						<div class="recent-posts"><?php previous_posts_link( __( '<span class="meta-nav">&larr;</span> Newer posts', 'cyrano' ) ); ?></div>
<?php
endif;
if ( get_next_posts_link() ) : ?>
						<div class="older-posts"><?php next_posts_link( __( 'Older posts <span class="meta-nav">&rarr;</span>', 'cyrano' ) ); ?></div>
<?php endif; ?>
						<div class="page-number">
							<ul id="paginate-links">
<?php
for ( $i = 1; $i <= $total; $i++ ) : 
	$selected = ( $i == $page );
?>
								<?php printf( '<li class="paginate-link%s" id="page_%d"><a href="%s">%s</a></li>', ( $selected ? ' selected' : '' ), $i, ( $selected ? '#' : get_pagenum_link( $i ) ), sprintf( __( 'Page %d of %d', 'cyrano' ), $i, $total ) ) ?>

<?php endfor; ?>
							</ul>
						</div>
					</nav>
					<div style="clear:both"></div>
				</div>
<?php
}

/**
 * Displays navigation to next/previous post when applicable.
*
* @since Cyrano 1.0
*
* @return void
*/
function cyrano_post_nav() {

	global $post;

	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous )
		return;
?>
				<div class="pagination">
					<nav class="post-navigation" role="navigation">
						<div class="recent-posts"><?php previous_post_link( '%link', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'cyrano' ) ); ?></div>
						<div class="older-posts"><?php next_post_link( '%link', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link', 'cyrano' ) ); ?></div>
					</nav>
					<div style="clear:both"></div>
				</div>
<?php
}

/**
 * Displays a Post thumbnail. If no Thumbnail was set, show a default one. If no
 * Post ID is submitted, use the current Post.
 *
 * @param    int        $post_id Post's ID. Default null.
 * @param    boolean    $echo Whether to echo the image. Default true.
 *
 * @since Cyrano 1.0
 */
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


function cyrano_comments( $comment, $args, $depth ) {

	extract( $args, EXTR_SKIP );

?>

				<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>

					<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

						<header class="comment-header">
							<div class="comment-author vcard">
								<?php echo get_avatar( $comment->comment_author_email, 74 ); ?>
								<?php printf( '<div class="vcar-content"><cite class="fn">%s</cite></div>', get_comment_author_link(), __( 'says', 'cyrano' ) ); ?>
							</div>
						</header>

						<div class="comment-text">
<?php if ( '0' == $comment->comment_approved ) : ?>
							<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ) ?></em><br />
<?php endif; ?>

							<?php comment_text(); ?>
						</div>

						<footer class="comment-meta">
							<ul>
								<li class="comment-date"><span class="entypo">&#128340;</span> &nbsp;<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '%1$s at %2$s' ), get_comment_date(),  get_comment_time() ); ?></a></li>
								<?php edit_comment_link( __( 'Edit' ), '<li class="comment-edit"><span class="entypo">&#9998;</span> &nbsp;', '</li>' ); ?>
								<?php
									comment_reply_link(
										array_merge(
											$args,
											array(
												'before'     => '<li class="comment-reply">',
												'reply_text' => '<span class="entypo">&#59154;</span> &nbsp;' . __( 'Reply' ),
												'after'      => '</li>',
												'depth'      => $depth,
												'max_depth'  => $args['max_depth']
											)
										)
									); ?>
							</ul>
						</footer>

					</article>

				</li>
<?php
}

function cyrano_comment_form_top() {
?>
				<header class="comment-respond-header">
<?php
}
add_action( 'comment_form_top', 'cyrano_comment_form_top' );

function cyrano_comment_form_field_comment( $comment_field ) {
	return "\t\t\t\t</header>\n" . $comment_field . "\n";
}
add_filter( 'comment_form_field_comment', 'cyrano_comment_form_field_comment' );

function cyrano_comment_form( $post_id ) {
?>
				</div> <!-- /comment-respond-content -->
<?php
}
add_action( 'comment_form', 'cyrano_comment_form' );


