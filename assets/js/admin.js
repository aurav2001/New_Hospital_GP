/* Media picker for image fields in meta boxes and settings. */
jQuery(function ($) {
	$(document).on('click', '.bs-upload', function (e) {
		e.preventDefault();
		var button = $(this);
		var input = button.siblings('input[type="text"]');
		var frame = wp.media({ title: 'Select image', button: { text: 'Use this image' }, multiple: false });
		frame.on('select', function () {
			var att = frame.state().get('selection').first().toJSON();
			input.val(att.url).trigger('change');
			var img = button.parent().find('img');
			if (img.length) {
				img.attr('src', att.url);
			} else {
				button.parent().append('<br><img src="' + att.url + '" style="max-height:90px;margin-top:8px;border-radius:6px">');
			}
		});
		frame.open();
	});
});

/* Colors tab: WP colour picker + one-click presets. */
jQuery(function ($) {
	var $fields = $('.bs-color-field');
	if (!$fields.length) return;

	if ($.fn.wpColorPicker) {
		$fields.wpColorPicker();
	}

	$('.bs-preset').on('click', function () {
		var primary = $(this).data('primary');
		var dark = $(this).data('dark');
		var set = function (id, value) {
			var $f = $('#' + id);
			if (!$f.length) return;
			if ($.fn.wpColorPicker) { $f.wpColorPicker('color', value); } else { $f.val(value); }
		};
		set('color_primary', primary);
		set('color_dark', dark);
		$('.bs-preset').removeClass('is-active');
		$(this).addClass('is-active');
	});
});
