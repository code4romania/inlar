<footer id="bottom">
	<div class="flex-container">
		<a href="<?php echo home_url(); ?>" class="logo">
			<h1 class="site-title screen-reader-text" itemprop="headline"><?php bloginfo('name'); ?></h1>
		</a>

		<?php
			// TODO: replace with proper option
		?>
		<p class="copy">&copy; <?php echo date("Y"); ?> INLAR. All Rights Reserved</p>
	</div>

  <div class="footer-code">
    <a class="brand footer-logo" href="https://code4.ro/en/">
      <img src="//code4.ro/wp-content/uploads/2017/08/logo-bw.png" alt="Code for Romania">
    </a>
    <p class="mono">
      In collaboration with Code for Romania.<br> An independent, non-partisan, non-political, non-govermental organization.
    </p>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
