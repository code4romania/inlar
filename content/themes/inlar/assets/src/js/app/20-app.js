"use strict";
(function($) {
	$(document).on('click', '.dropdown-container', function(e) {
		$(this).addClass('open');
	}).on('click', function(e) { 
		if (!$(e.target).closest('.dropdown-container').length) {
			$('.dropdown-container').removeClass('open');
		}
	});
})(jQuery);
