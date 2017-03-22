"use strict";
(function($) {
	if (Modernizr.svgasimg) {
		// Replace png with svg
		$('img.png2svg').attr('src', function() {
			return $(this).removeClass('png2svg').attr('src').replace('.png', '.svg');
		});
	}
})(jQuery);
