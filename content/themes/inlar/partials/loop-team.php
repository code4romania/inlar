<article class="member" itemscope itemprop="member" itemtype="http://schema.org/Person">
	<header><?php
		if (has_post_thumbnail()) {
			the_post_thumbnail('team-profile', array(
				'itemprop' => 'image'
			));
		}

		print ('<div class="member-meta">');
		printf('<h1 class="member-name" itemprop="name">%s</h1>',
			get_the_title()
		);
		
		$title = get_post_meta($post->ID, '_ptf_team_title_meta', true);
		if (!empty($title)) {
			printf('<div class="member-title" itemprop="jobTitle">%s</div>',
				$title
			);
		}

		$email = get_post_meta($post->ID, '_ptf_team_email_meta', true);
		if (!empty($email)) {
			printf('<a href="mailto:%1$s" class="member-email" itemprop="email">%1$s</a>',
				$email
			);
		}
		print ('</div>');
	?></header>
	<div class="member-desc" itemprop="description"><?php the_content(); ?></div>
</article>
