<?php 
		/**
		 * Functions hooked in to canvas_after_main_content
		 */
		do_action('canvas_after_main_content'); ?>

		</main><!-- #main -->

		<footer class="site-footer row">

			<nav id="footer-breadcrumb-nav" role="navigation" aria-label="Breadcrumbs">

				<div class="column col-right-unpadded col-xs-10 col-sm-10 col-md-11 col-lg-11">

					<?php do_action('canvas_footer_breadcrumb_area'); ?>

				</div>

				<div class="back-to-top column col-left-unpadded col-xs-2 col-sm-2 col-md-1 col-lg-1">

					<div id="back-to-top">

						<?php do_action('canvas_footer_back_to_top_area'); ?>

					</div>

				</div>

			</nav>

			<?php do_action('canvas_footer'); ?>

			<?php  get_template_part('_template-parts/footer/footer', 'widgets'); ?>

			<small class="copyright column col-xs-12">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></small>
			
		</footer>

		<?php wp_footer(); ?>

	</body>

</html>