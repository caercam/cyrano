<?php
/**
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Cyrano
 * @since Cyrano 1.0
 */
?><!DOCTYPE html>

<!--[if IE 7]><html class="ie ie7" <?php language_attributes(); ?>><![endif]-->
<!--[if IE 8]><html class="ie ie8" <?php language_attributes(); ?>><![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!--><html <?php language_attributes(); ?>><!--<![endif]-->

	<head>

		<meta http-equiv="Content-Type" content="text/html" charset="<?php bloginfo( 'charset' ); ?>" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

		<title><?php wp_title( '|', true, 'right' ); ?></title>

		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

		<meta name="description" content="" />

		<meta name="HandheldFriendly" content="True" />
		<meta name="MobileOptimized" content="320" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="generator" content="" />

<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>

		<div class="wrapper">

			<header id="header" class="site-header" role="header">

				<h1 class="site-logo">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<img src="<?php echo get_template_directory_uri() ?>/assets/img/logo_128.png" alt="CaerCam.org" />
					</a>
				</h1>
				<h2 class="site-title"><?php bloginfo( 'name' ); ?></h2>
				<p class="site-description"><?php bloginfo( 'description' ); ?></p>

			</header>

			<nav id="menu" class="menu">
				<ul>
					<li class="menu-home"><a href="#"><span class="entypo">&#8962;</span></a></li>
					<li class="menu-item"><a href="#">Voyages<span>Ici ou ailleurs</span></a></li>
					<li class="menu-item"><a href="#">Photographie<span>Vu dans l'objectif</span></a></li>
					<li class="menu-item"><a href="#">Cinéma<span>Vu en grand</span></a></li>
					<li class="menu-item"><a href="#">Lecture<span>À travers les pages</span></a></li>
					<li class="menu-item"><a href="#">Divers<span>Et bien plus</span></a></li>
					<li class="menu-nav"><a href="#header"><span class="entypo">&#59239;</span></a></li>
				</ul>
			</nav>
