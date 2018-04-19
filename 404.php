<?php get_header(); ?>
<main>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<!-- section -->
				<section>
					<!-- article -->
					<article id="post-404">
						<h1 class="page-header"><?php _e( 'Page not found', 'wpbootstrapsass' ); ?></h1>
						<h2>
							<a href="<?php echo home_url(); ?>"><?php _e( 'Return home?', 'wpbootstrapsass' ); ?></a>
						</h2>
					</article>
					<!-- /article -->
				</section>
				<!-- /section -->
			</div><!-- /.col-md-8 -->
			<?php get_sidebar(); ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>
<?php get_footer(); ?>
