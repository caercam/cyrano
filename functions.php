<?php
/**
 * Define the theme class.
 *
 * @since 2.0.0
 * @author Charlie Merland <charlie@caercam.org>
 */
final class Cyrano {

  /**
   * @var self
   */
  private static $_instance;

  /**
   * Class constructor.
   * 
   * @since 2.0.0
   * @access private
   */
  private function __construct() {}

  /**
   * Singleton.
   * 
   * @since 2.0.0
   * @access public
   */
  public static function get_instance() {

    if ( ! self::$_instance instanceof self ) {
      self::$_instance = new self;
    }

    return self::$_instance;
  }

  /**
   * Initialize the theme.
   * 
   * @since 2.0.0
   * @access private
   */
  private function init() {

    add_action( 'after_setup_theme', [ $this, 'setup' ] );

    add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
    add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );

    add_filter( 'post_thumbnail_html', [ $this, 'default_thumbnail' ], 10, 5 );
    add_filter( 'pre_get_posts',       [ $this, 'pre_get_posts' ] );

    add_filter( 'block_type_metadata_settings', [ $this, 'custom_cinemarathons_block_templates' ], 10, 2 );

    add_filter( 'render_block', [ $this, 'maybe_remove_critic_block' ], 10, 2 );
  }

  /**
   * Load dependencies.
   * 
   * @since 2.0.0
   * @access public
   */
  public function load() {

    require_once get_stylesheet_directory() . '/includes/helpers.php';
  }

  /**
   * Setup theme.
   * 
   * @since 2.0.0
   * @access public
   */
  public function setup() {

    $this->load();

    add_theme_support( 'title-tag' );
    add_theme_support( 'custom-background' );
    add_theme_support( 'custom-header' );
    add_theme_support( 'custom-logo' );
    add_theme_support( 'featured-content' );
    add_theme_support( 'html5', [ 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'menus' );
    add_theme_support( 'post-formats', [ 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio' ] );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'wp-block-styles' );
    add_theme_support( 'widgets' );

	  add_image_size( 'cyrano-index-thumb-2', 960, 360 );

  	register_nav_menus( [
  		'actions' => 'Menu actions',
  		'primary' => 'Menu principal',
  	] );

  	register_sidebar( array(
  		'name'          => 'Pied de page',
  		'id'            => 'sidebar-footer',
  		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
  		'after_widget'  => '</aside>',
  		'before_title'  => '',
  		'after_title'   => '',
  	) );
  }

  /**
   * Enqueue the theme's scripts.
   * 
   * @since 2.0.0
   * @access public
   */
  public function enqueue_scripts() {

    $this->register_scripts();

    wp_enqueue_script( 'cyrano-script' );
  }

  /**
   * Enqueue the theme's styles.
   * 
   * @since 2.0.0
   * @access public
   */
  public function enqueue_styles() {

    $this->register_styles();

    wp_enqueue_style( 'cyrano-style' );
  }

  /**
   * Register the theme's scripts.
   * 
   * @since 2.0.0
   * @access private
   */
  private function register_scripts() {

    wp_register_script( 'cyrano-script', get_theme_file_uri( '/dist/js/app.js' ), [], wp_get_theme()->get( 'Version' ), true );
  }

  /**
   * Register the theme's styles.
   * 
   * @since 2.0.0
   * @access private
   */
  private function register_styles() {

    wp_register_style( 'cyrano-style', get_stylesheet_uri(), [], wp_get_theme()->get( 'Version' ) );
  }

  /**
   * Set a default post thumbnail depending on post category.
   * 
   * @since 2.0.0
   * @access public
   * 
   * @param  string $html
   * @param  int    $post_ID,
   * @param  int    $post_thumbnail_id,
   * @param  string $size,
   * @param  array  $attr,
   * @return string
   */
  public function default_thumbnail( $html, $post_ID, $post_thumbnail_id, $size, $attr ) {

    if ( ! empty( $html ) ) {
      return $html;
    }
  
    $categories = get_the_category();
    foreach ( $categories as $category ) {
      $attachment_id  = get_term_meta( $category->cat_ID, 'category_image', $single = true );
      $attachment_url = wp_get_attachment_image_url( $attachment_id, $size );
      if ( $attachment_url ) {
        return '<img src="' . esc_url( $attachment_url ) . '" alt="' . esc_attr( $attr['alt'] ?? '' ) . '" />';
      }
    }
  
    return '<img src="' . esc_url( get_theme_file_uri( 'dist/images/default.jpg' ) ) . '" alt="' . esc_attr( $attr['alt'] ?? '' ) . '" />';
  }

  /**
   * Custom query filters.
   * 
   * @since 2.0.0
   * @access public
   * 
   * @param  WP_Query $wp_query
   */
  public function pre_get_posts( $wp_query ) {

    if ( ! $wp_query->is_main_query() ) {
      return;
    }

    if ( isset( $_GET['categorie'] ) ) {
      $wp_query->set( 'category_name', $_GET['categorie'] );
    }

    if ( ! isset( $_GET['annee'] ) && ! isset( $_GET['mois'] ) ) {
      return;
    }

    if ( ! $wp_query->is_tag && ! $wp_query->is_category ) {
      return;
    }

    $wp_query->set( 'year', $_GET['annee'] );

    if ( isset( $_GET['mois'] ) ) {
      $wp_query->set( 'monthnum', $_GET['mois'] );
    }
  }

  /**
   * Override Cinemarathons block templates.
   * 
   * @since 2.1.0
   * @access public
   * 
   * @param  array $settings
   * @param  array $metadata
   * @return array
   */
  public function custom_cinemarathons_block_templates( $settings, $metadata ) {

    if ( 'cinemarathons' === $metadata['category'] && is_callable( '\Cinemarathons\cinemarathons' ) ) {
      $settings['render_callback'] = [ \Cinemarathons\cinemarathons(), 'use_theme_block_templates' ];
    }

    return $settings;
  }

  /**
   * Conditionally remove 'roxane/critic' block from content.
   * 
   * @since 2.5.0
   * @access public
   * 
   * @param  string $block_content
   * @param  array  $block
   * @return string
   */
  public function maybe_remove_critic_block( $block_content, $block ) {

    if ( 'status' !== get_post_format() ) {
      return $block_content;
    }

    if ( is_single() ) {
      return $block_content;
    }

    if ( 'roxane/critic' === $block['blockName'] ) {
      return '';
    }

    return $block_content;
  }

  /**
   * Run!
   * 
   * @since 2.0.0
   * @access public
   */
  public function run() {

    $this->init();
  }
}

$cyrano = Cyrano::get_instance();
$cyrano->run();
