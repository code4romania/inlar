"use strict";
(function($) {
	$(document).on('click', '.dropdown-toggle', function(e) {
		$(this).closest('.dropdown-container').toggleClass('open');
	}).on('click', function(e) {
		if (!$(e.target).closest('.dropdown-container').length) {
			$('.dropdown-container').removeClass('open');
		}
	});

	$('#lang-select').change(function() {
		window.location = $(this).find('option:selected').val();
	});
})(jQuery);
