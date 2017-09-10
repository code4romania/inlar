<article itemscope itemtype="http://schema.org/Article" <?php post_class('entry'); ?>>
	<a href="<?php the_permalink(); ?>" rel="bookmark"><?php
		if (has_post_thumbnail())
			the_post_thumbnail('medium');

		printf('<div class="entry-meta"><h1 class="entry-title" itemprop="headline">%s</h1><time itemprop="datePublished" datetime="%s">%s</time></div>',
			get_the_title(), get_the_date('c'), get_the_date()
		);
	?></a>
</article>
