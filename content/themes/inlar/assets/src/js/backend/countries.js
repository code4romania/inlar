"use strict";
(function($) {
	$('#country_flag', '#addtag, #edittag').on('change', function(e) {
		var url = location.origin + '/content/themes/inlar/assets/images/flags',
			country = $(this).val();

		$('#flag_preview').attr('src', url + '/' + country + '.png');
	});
})(jQuery);
