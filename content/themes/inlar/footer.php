<footer id="bottom">
	<div class="flex-container">
		<a href="<?php echo home_url(); ?>" class="logo">
			<h1 class="site-title screen-reader-text" itemprop="headline"><?php bloginfo('name'); ?></h1>
		</a>

		<p class="copy"><?php
			/* translators: %s will be replaced with the current year */
			printf(__('&copy; %s INLAR. All Rights Reserved', 'inlar'), date('Y'));
		?></p>
	</div>

	<div class="footer-code container">
		<a class="brand footer-logo" href="https://code4.ro/">
			<img src="<?php printf('%s/assets/images/code4romania.png', get_template_directory_uri() ); ?>" alt="Code for Romania">
		</a>
		<p class="mono">
			<?php _e('In collaboration with Code for Romania.', 'inlar'); ?>
			<br>
			<?php _e('An independent, non-partisan, non-political, non-govermental organization.', 'inlar'); ?>
		</p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
