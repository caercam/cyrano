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

	load_theme_textdomain( 'cyrano', get_template_directory() . '/languages' );

	//add_editor_style( array( 'css/editor-style.css', 'fonts/genericons.css', twentythirteen_fonts_url() ) );

	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	add_theme_support( 'custom-header', array() );
	//require get_template_directory() . '/include/custom-header.php';

	$default_background = array(
		'default-color'          => '0c141e',
		'default-image'          => get_template_directory_uri() . '/assets/img/bg.png',
		'wp-head-callback'       => '_custom_background_cb',
		'admin-head-callback'    => '',
		'admin-preview-callback' => ''
	);

	$default_header = array(
		'default-text-color'     => 'fff',
		'default-image'          => '%s/assets/img/header.jpg',
		'height'                 => 288,
		'width'                  => 896,
		'wp-head-callback'       => 'cyrano_header_style',
		'admin-head-callback'    => 'cyrano_admin_header_style',
		'admin-preview-callback' => 'cyrano_admin_header_image',
	);

	register_default_headers(
		array(
			'cyrano' => array(
				'url'           => '%s/assets/img/header.jpg',
				'thumbnail_url' => '%s/screenshot.png',
				'description'   => _x( 'Cyrano', 'header image description', 'cyrano' )
			)
		) 
	);

	add_theme_support( 'custom-background', $default_background );
	add_theme_support( 'custom-header', $default_header );

	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'post-formats', array(
		'aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'
	) );

	register_nav_menu( 'primary', __( 'Navigation Menu', 'cyrano' ) );

	//add_filter( 'use_default_gallery_style', '__return_false' );

	if ( ! isset( $content_width ) ) $content_width = 896;
}
add_action( 'after_setup_theme', 'cyrano_setup' );


/**
 * Load Open Sans Font
 *
 * @since Cyrano 1.0
 */
function cyrano_custom_header_fonts() {

	wp_enqueue_style( 'cyrano-fonts', '//fonts.googleapis.com/css?family=Open+Sans:300,700', array(), null );
}
add_action( 'admin_print_styles-appearance_page_custom-header', 'cyrano_custom_header_fonts' );


/**
 * Header Styling
 *
 * @since Cyrano 1.0
 */
function cyrano_header_style() {

	$header_image = get_header_image();
	$text_color   = get_header_textcolor();

	if ( empty( $header_image ) && $text_color == get_theme_support( 'custom-header', 'default-text-color' ) )
		return;

	echo "\t\t".'<style type="text/css" id="cyrano-header-css">'."\n\t\t";

	if ( ! empty( $header_image ) )
		echo '.site-header {background: url(' . get_header_image() . ') no-repeat scroll top; background-size: cover;} ';

	if ( ! display_header_text() )
		echo '.site-title, .site-description {display: none} ';
	else if ( $text_color != get_theme_support( 'custom-header', 'default-text-color' ) )
		echo '.site-title, .site-description {color: #' . esc_attr( $text_color ) . '} ';

	echo "\t\t".'</style>'."\n";
}


/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @since Cyrano 1.0
 */
function cyrano_admin_header_style() {
	$header_image = get_header_image();
?>
	<style type="text/css" id="cyrano-admin-header-css">
	.appearance_page_custom-header #headimg {
		border: none;
		-webkit-box-sizing: border-box;
		-moz-box-sizing:    border-box;
		box-sizing:         border-box;
		<?php if ( ! empty( $header_image ) ) { echo 'background: url(' . esc_url( $header_image ) . ') no-repeat scroll top;'; } ?>
		margin: 0 auto;
		max-width: 896px;
		padding: 2em;
		text-align: center;
		<?php if ( ! empty( $header_image ) || display_header_text() ) { echo 'min-height: 288px;'; } ?>
		width: 100%;
	}

	<?php if ( ! display_header_text() ) : ?>
	#headimg h1,
	#headimg h2 {
		display: none;
	}
	<?php endif; ?>

	#headimg .site-title {
		font-family: 'Open Sans', sans-serif;
		font-size: 3em;
		font-weight: 100;
		margin-top: -0.25em;
	}
	#headimg .site-description {
		color: #fff;
		font-family: 'Open Sans', sans-serif;
		font-size: 0.9em;
		font-weight: 700;
		opacity: 0.5;
		text-transform: uppercase;
	}

	.default-header img {
		max-width: 230px;
		width: auto;
	}
	</style>
<?php
}


/**
 * Outputs markup to be displayed on the Appearance > Header admin panel.
 * This callback overrides the default markup displayed there.
 *
 * @since Cyrano 1.0
 */
function cyrano_admin_header_image() {
	?>
	<header id="headimg" class="site-header" role="header" style="background: url(<?php header_image(); ?>) no-repeat scroll top;">
		<?php $style = ' style="color:#' . get_header_textcolor() . ';"'; ?>
		<h1 class="site-logo">
			<a href="#"<?php echo $style; ?> onclick="return false;"><img src="<?php echo get_template_directory_uri() . '/assets/img/logo_128.png'; ?>" alt="<?php bloginfo( 'name' ); ?>"></a>
		</h1>
		<h2 class="site-title"<?php echo $style; ?>><?php bloginfo( 'name' ); ?></h2>
		<p class="site-description"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></p>
	</header>
<?php
}


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
}
add_action( 'widgets_init', 'cyrano_widgets_init' );


/**
 * Enqueues scripts and styles for front end.
 *
 * @since Cyrano 1.0
 */
function cyrano_scripts() {

	wp_register_style( 'cyrano', get_stylesheet_uri(), array(), false, 'all' );
	wp_register_style( 'open-sans', "//fonts.googleapis.com/css?family=Open+Sans:100,300,700", array(), false, 'all' );

	wp_register_script( 'cyrano', get_template_directory_uri() . '/assets/js/public.js', array( 'jquery' ), false, true );

	wp_enqueue_style( 'open-sans' );
	wp_enqueue_style( 'cyrano' );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'cyrano' );
	wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'cyrano_scripts' );


/**
* Get the site logo
* If no logo is set in the theme's options, use default WP-Badge as logo 
* 
* @since    Cyrano 1.0
*
* @return   string        The site logo URL.
*/
function cyrano_site_logo() {
	$site_logo = get_theme_mod( 'cyrano_logo', get_template_directory_uri() . '/assets/img/logo_128.png' );
	return $site_logo;
}


/**
 * Displays Cyrano Menu.
 *
 * @since Cyrano 1.0
 */
function cyrano_nav_menu() {

	$menu_name = 'primary';

	if ( ( $locations = get_nav_menu_locations() ) && isset( $locations[ $menu_name ] ) ) {

		$menu  = wp_get_nav_menu_object( $locations[ $menu_name ] );
		$items = wp_get_nav_menu_items( $menu->term_id );
		_wp_menu_item_classes_by_context( $items );

		if ( $items ) {
?>
				<ul>
					<li class="menu-home"><a href="<?php echo home_url( '/' ); ?>"><span class="entypo">&#8962;</span></a></li>
<?php
			foreach ( (array) $items as $item ) {
?>
					<?php printf( '<li class="%s"><a href="%s" title="%s">%s<span>%s</span></a></li>', trim( implode( ' ', $item->classes ) ), $item->url, $item->description, $item->title, $item->attr_title ); ?>
<?php
			}
?>
					<li class="menu-nav"><a href="#header"><span class="entypo">&#59239;</span></a></li>
				</ul>
<?php
		}
	}
}


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
			$categories = implode( __( ', ', 'cyrano' ), array_slice( $c, 0, 3 ) ) . '&hellip; (' . ( count( $c ) - 3 ) . ' more)';

		$categories = sprintf( '<li class="post-categories"><span class="entypo">&#128193;</span> &nbsp;%s</li>', $categories );
	}

	$tags = get_the_tag_list( '', __( ', ', 'cyrano' ) );
	if ( $tags ) {
		$t = explode( __( ', ', 'cyrano' ), $tags );
		if ( count( $t ) > 3 )
			$tags = implode( __( ', ', 'cyrano' ), array_slice( $t, 0, 3 ) ) . '&hellip; (' . ( count( $t ) - 3 ) . ' more)';

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

/**
 * Displays a custom Comments List.
 *
 * @param    object     $comment The Comment Object.
 * @param    array      $args Display arguments.
 * @param    int        $depth Comment Depth.
 *
 * @since Cyrano 1.0
 */
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
							<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'cyrano' ) ?></em><br />
<?php endif; ?>

							<?php comment_text(); ?>
						</div>

						<footer class="comment-meta">
							<ul>
								<li class="comment-date"><span class="entypo">&#128340;</span> &nbsp;<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( __( '%1$s at %2$s', 'cyrano' ), get_comment_date(),  get_comment_time() ); ?></a></li>
								<?php edit_comment_link( __( 'Edit', 'cyrano' ), '<li class="comment-edit"><span class="entypo">&#9998;</span> &nbsp;', '</li>' ); ?>
								<?php
									comment_reply_link(
										array_merge(
											$args,
											array(
												'before'     => '<li class="comment-reply">',
												'reply_text' => '<span class="entypo">&#59154;</span> &nbsp;' . __( 'Reply', 'cyrano' ),
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


/**
 * Styling the Comment Form to look like Comments List.
 * 
 * @uses comment_form_logged_in Filter Hook to display a Header before the Author VCard
 * 
 * @param    string    $logged_in_as   The logged-in-as HTML-formatted message.
 *
 * @since Cyrano 1.0
 */
function cyrano_comment_logged_in( $logged_in_as ) {
?>
				<header class="comment-respond-header comment-respond-loggedin">
<?php
	return $logged_in_as;
}
add_filter( 'comment_form_logged_in', 'cyrano_comment_logged_in' );


/**
 * Styling the Comment Form to look like Comments List.
 * 
 * @uses comment_form_field_comment Filter Hook to end the Header before the Comment Textarea
 * 
 * @param array  $commenter     An array containing the comment author's username, email, and URL.
 *
 * @since Cyrano 1.0
 */
function cyrano_comment_form_logged_in_after( $commenter ) {
?>
				</header>

<?php
}
add_action( 'comment_form_logged_in_after', 'cyrano_comment_form_logged_in_after' );


/**
 * Styling the Comment Form to look like Comments List.
 * 
 * @uses comment_form_before_fields Action Hook to display a Header before the Author VCard
 *
 * @since Cyrano 1.0
 */
function cyrano_comment_before_fields() {
?>
				<header class="comment-respond-header comment-respond-fields">
<?php
}
add_action( 'comment_form_before_fields', 'cyrano_comment_before_fields' );


/**
 * Styling the Comment Form to look like Comments List.
 * 
 * @uses comment_form_after_fields Action Hook to end the Header before the Comment Textarea
 *
 * @since Cyrano 1.0
 */
function cyrano_comment_form_after_fields() {
?>
				</header>
				<div class="comment-respond-avatar"><?php echo get_avatar( null, 74 ); ?></div>
<?php
}
add_action( 'comment_form_after_fields', 'cyrano_comment_form_after_fields' );


/**
 * Styling the Comment Form to look like Comments List.
 * 
 * @uses comment_form Action Hook to display 
 *
 * @since Cyrano 1.0
 */
function cyrano_comment_form( $post_id ) {
?>
				</div> <!-- /comment-respond-content -->
<?php
}
add_action( 'comment_form', 'cyrano_comment_form' );





/**
 * Theme Customizer
 *
 * @since Cyrano 1.0
 */
function cyrano_theme_customizer( $wp_customize ) {

	$wp_customize->add_section(
		'cyrano_logo_section',
		array(
			'title'       => __( 'Site Logo', 'cyrano' ),
			'priority'    => 50,
			'description' => '',
		)
	);

	$wp_customize->add_setting(
		'cyrano_logo',
		array(
			'default'   => get_template_directory_uri() . '/assets/img/logo_128.png',
			'transport' => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'cyrano_logo',
			array(
				'label'      => __( 'Custom Logo', 'cyrano' ),
				'section'    => 'cyrano_logo_section',
				'settings'   => 'cyrano_logo',
			)
		)
	);

	$wp_customize->get_setting('cyrano_logo')->transport='postMessage';

	// Enqueue scripts for real-time preview
	wp_enqueue_script( 'cyrano-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'jquery' ) );
}
add_action( 'customize_register', 'cyrano_theme_customizer' );
