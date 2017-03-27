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
		<nav class="container" itemscope itemtype="http://schema.org/SiteNavigationElement">
			<a href="<?php echo home_url(); ?>" class="title-area flex-item">
				<h1 class="site-title screen-reader-text" itemprop="headline"><?php bloginfo('name'); ?></h1>
				<p class="site-description screen-reader-text" itemprop="description"><?php bloginfo('description'); ?></p>
			</a>
			<?php inlar_navigation_menu('menu-primary'); ?>
			<div class="flex-item"><?php
				get_search_form();
				inlar_language_switcher();
			?></div>
		</nav>
	</header>

