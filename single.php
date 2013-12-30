<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Cyrano
 * @since Cyrano 1.0
 */

get_header(); ?>

			<main id="main" class="main" role="main">

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
				<article <?php post_class() ?> role="article" itemscope itemtype="http://schema.org/Article">
					<?php cyrano_post_format(); ?>
					<header class="post-header">
						<div class="post-thumbnail"><?php echo cyrano_post_cover(); ?></div>
						<h1 class="post-title" itemprop="headline">
							<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title() ?></a>
							<?php edit_post_link( '&#9998;', '<span class="edit-link entypo">', '</span>' ); ?>

						</h1>
						<?php cyrano_entry_date() ?>
						<?php cyrano_featured() ?>

					</header>
					<div class="post-content" itemprop="articleBody">
						<?php
if ( '' != get_the_content() )
	the_content();
else
	_e( 'This is somewhat embarassing, huh? Seems like there is nothing here…', 'cyrano' );
?>
					</div>
					<footer class="post-footer">
<?php cyrano_entry_meta(); ?>
						<div style="clear:both"></div>
					</footer>
				</article>

				<?php comments_template(); ?>

<?php endwhile; endif; ?>

<?php cyrano_post_nav(); ?>

			</main>

<?php get_footer(); ?>