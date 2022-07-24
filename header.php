<!doctype html>
<html <?php language_attributes(); ?>>
  <head>
  	<meta charset="<?php bloginfo( 'charset' ); ?>" />
  	<meta name="viewport" content="width=device-width, initial-scale=1" />
  	<?php wp_head(); ?>
  </head>

  <body <?php body_class(); ?>>

    <div class="wrapper">

      <header class="header">
        <div class="site-logo">
          <a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/dist/images/logo_128.png' ); ?>" alt="" /></a>
        </div>
        <h1 class="site-title">
          <a href="<?php echo esc_url( home_url() ); ?>"><?php echo esc_html( get_option('blogname') ); ?></a>
        </h1>
        <div class="site-description"><?php echo esc_html( get_option( 'blogdescription' ) ); ?></div>
      </header>

      <nav class="nav">
        <?php wp_nav_menu( [
          'container' => '',
        ] ); ?>
      </nav>

      <main class="content">
