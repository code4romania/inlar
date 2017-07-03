<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
	<label for="search-field" class="screen-reader-text"><?php _ex('Search for:', 'label'); ?></label>
	<input type="search" class="search-field" placeholder="<?php echo esc_attr_x('Search', 'placeholder', 'inlar'); ?>" value="<?php the_search_query(); ?>" name="s" id="search-field">
	<input type="submit" class="search-submit icon-search" value="<?php echo esc_attr_x('Search', 'submit button', 'inlar'); ?>">
</form>
