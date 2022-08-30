<?php

class Cyrano {

  /**
   * @var self
   */
  private static $_instance;

  /**
   * Class constructor.
   */
  private function __construct() {}

  /**
   * Singleton.
   */
  public static function get_instance() {
    if (!self::$_instance instanceof self) {
      self::$_instance = new self;
    }
    return self::$_instance;
  }

  /**
   * Initialize the theme.
   */
  private function init() {

    add_action( 'after_setup_theme', [$this, 'setup'] );

    add_action( 'wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    add_action( 'wp_enqueue_scripts', [$this, 'enqueue_styles']);
  }

  /**
   * Setup theme.
   */
  public function setup() {

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
   */
  public function enqueue_scripts() {

    $this->register_scripts();

    wp_enqueue_script( 'cyrano-script' );
  }

  /**
   * Enqueue the theme's styles.
   */
  public function enqueue_styles() {

    $this->register_styles();

    wp_enqueue_style( 'cyrano-style' );
  }

  /**
   * Register the theme's scripts.
   */
  private function register_scripts() {

    wp_register_script( 'cyrano-script', get_theme_file_uri( '/dist/js/app.js' ), [], wp_get_theme()->get( 'Version' ), true );
  }

  /**
   * Register the theme's styles.
   */
  private function register_styles() {

    wp_register_style( 'cyrano-style', get_stylesheet_uri(), [], wp_get_theme()->get( 'Version' ) );
  }

  /**
   * Run!
   */
  public function run() {

    $this->init();
  }

}

$cyrano = Cyrano::get_instance();
$cyrano->run();
