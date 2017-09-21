(function($) {
	if (Modernizr.svgasimg) {
		// Replace png with svg
		$('img.png2svg').attr('src', function() {
			return $(this).removeClass('png2svg').attr('src').replace('.png', '.svg');
		});
	}

	if (typeof validation_messages != 'undefined') {
		for (var key in validation_messages) {
			if (key.match(/(min|max)length/i)) {
				validation_messages[key] = $.validator.format(validation_messages[key]);
			}
		}

		$.extend($.validator.messages, validation_messages);
	}

	$('.form').validate({
		submitHandler: function(form) {
			var submit = $(form).find('input[type="submit"]');

			submit.prop('disabled', true);

			$.post(ajax_url, {
				action:	'inlar_contact_form',
				data: 	$(form).serialize()
			}, function(response) {
				var target   = $('.form .response-container'),
					source   = $('#template-form_response').html(),
					template = Handlebars.compile(source);

				target.html(template($.extend({}, response.data, {
					icon: (response.success ? 'check' : 'cross')
				}))).addClass('has-response');
			});
		}
	});

	$('.form .response-container').on('click', 'button', function(e) {
		var form = $(this).parents('.form');

		form.find('.response-container').removeClass('has-response').empty();
		form.find('input[type="submit"]').prop('disabled', false);
		form.find('.text').val('');

		e.preventDefault();
	});
})(jQuery);

function png_or_svg() {
	return Modernizr.svgasimg ? '.svg' : '.png';
}

Handlebars.registerHelper('select-attrs', function(country_id) {
	var attrs = 'value="' + country_id + '"';

	if (country_id == mapconfig.current.id)
		attrs+= ' selected';

	return attrs;
});

Handlebars.registerHelper('list-attrs', function(country_id) {
	var attrs = 'data-country="' + country_id + '"';

	if (country_id == mapconfig.current.id)
		attrs+= ' hidden';

	return attrs;
});

Handlebars.registerHelper('ifeq', function(n1, n2, opts) {
	if (n1 == n2) {
		return opts.fn(this);
	} else {
		return opts.inverse(this);
	}
});

Handlebars.registerHelper('debug', function(arg) {
	console.log(arg);
	return arg;
});
