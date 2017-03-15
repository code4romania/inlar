<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js" prefix="og: http://ogp.me/ns#">
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
	<header id="top" itemscope itemtype="http://schema.org/WPHeader">
		<nav class="container">
			<a href="<?php echo home_url(); ?>" class="title-area">
				<h1 class="site-title screen-reader-text" itemprop="headline"><?php bloginfo('name'); ?></h1>
				<p class="site-description screen-reader-text" itemprop="description"><?php bloginfo('description'); ?></p>
			</a>
		</nav>
	</header>

