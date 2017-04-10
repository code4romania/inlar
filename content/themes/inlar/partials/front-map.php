<section class="hero map-container map-closed">
	<div id="map" class="header-adjust">
		<div id="country-control" class="container"></div>
	</div>
	<div class="map-description header-adjust">
		<div class="container">
			<div class="row">
				<div class="col l5 s12">
					<?php
						$option = wp_parse_args(get_option('inlar_intro', array()), array(
							'title' => '',
							'text'  => '',
						));

						if (!empty($option['title']))
							printf('<h1>%s</h1>', $option['title']);

						if (!empty($option['text']))
							printf('<p>%s</p>', $option['text']);
					?>
					<div class="countries">
						<?php inlar_country_buttons(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

