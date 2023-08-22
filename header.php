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
        <div class="banner">
          <div class="site-logo">
            <a href="<?php echo esc_url( home_url() ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/dist/images/logo_128.png' ); ?>" alt="" /></a>
          </div>
          <div class="site-title">
            <a href="<?php echo esc_url( home_url() ); ?>"><?php echo esc_html( get_option('blogname') ); ?></a>
          </div>
          <div class="site-description"><?php echo esc_html( get_option( 'blogdescription' ) ); ?></div>
        </div>
        <nav class="mobile-navigation">
          <a class="nav-toggle" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M0 88C0 74.75 10.75 64 24 64H424C437.3 64 448 74.75 448 88C448 101.3 437.3 112 424 112H24C10.75 112 0 101.3 0 88zM0 248C0 234.7 10.75 224 24 224H424C437.3 224 448 234.7 448 248C448 261.3 437.3 272 424 272H24C10.75 272 0 261.3 0 248zM424 432H24C10.75 432 0 421.3 0 408C0 394.7 10.75 384 24 384H424C437.3 384 448 394.7 448 408C448 421.3 437.3 432 424 432z"/></svg>
          </a>
          <a class="search-toggle" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--! Font Awesome Pro 6.1.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M504.1 471l-134-134C399.1 301.5 415.1 256.8 415.1 208c0-114.9-93.13-208-208-208S-.0002 93.13-.0002 208S93.12 416 207.1 416c48.79 0 93.55-16.91 129-45.04l134 134C475.7 509.7 481.9 512 488 512s12.28-2.344 16.97-7.031C514.3 495.6 514.3 480.4 504.1 471zM48 208c0-88.22 71.78-160 160-160s160 71.78 160 160s-71.78 160-160 160S48 296.2 48 208z" /></svg>
          </a>
        </nav>
        <div class="search-form">
          <form action="<?php echo esc_url( home_url() ); ?>" method="GET">
            <input type="search" name="s" placeholder="Rechercher…" value="<?php echo isset( $_GET['s'] ) ? esc_attr( $_GET['s'] ) : ''; ?>" />
            <input type="submit" value="Rechercher" />
          </form>
        </div>
        <nav class="navigation">
          <?php wp_nav_menu( [
            'container' => '',
          ] ); ?>
        </nav>
      </header>

      <main class="content">
