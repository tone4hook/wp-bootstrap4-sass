<?php get_header(); ?>
<main>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<!-- section -->
				<section>

					<h1 class="page-header"><?php _e( 'Archives', 'wpbootstrapsass' ); ?></h1>

					<?php get_template_part('loop'); ?>

					<?php get_template_part('pagination'); ?>

				</section>
				<!-- /section -->
			</div><!-- /.col-md-8 -->
		<?php get_sidebar(); ?>
		</div><!-- /.row -->
	</div><!-- /.container -->
</main>
<?php get_footer(); ?>
