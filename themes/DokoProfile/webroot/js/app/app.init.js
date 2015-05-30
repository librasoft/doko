+function ($, doko) { "use strict";

	doko.init = function () {
		FastClick.attach(document.body);

		offCanvas();

		$.scrollUp({
			zIndex: 999
		});

		var $password_meter = $('.strength-password');

		$password_meter.dokoPassword({
			weakLabel: $password_meter.data('weak-label'),
			normalLabel: $password_meter.data('normal-label'),
			strongLabel: $password_meter.data('strong-label'),
			strengthScaleFactor: $password_meter.data('strength-factor'),
			minimumChars: $password_meter.data('min-length')
		});

		$('input[data-mailcheck]').dokoEmailSuggest();
		if ($('.input-timezone').length) {
			$('.input-timezone').val(jstz.determine().name());
		}
		$('[data-toggle=remove]').remove(); //For honeypot fields
	};

	var offCanvas = function () {
		var $doc = $('html');

		$doc.on('click', '.btn-offcanvas', function (e) {
			e.preventDefault();

			var offcanvas_dir = $(this).data('offcanvas-dir');

			$doc.toggleClass('off-canvas-' + offcanvas_dir);
			$doc.addClass('off-canvas-' + offcanvas_dir + '-transition');

			if (!$.support.transition) {
				setTimeout(function () {
					$doc.removeClass('off-canvas-' + offcanvas_dir + '-transition');
				}, 200);
			} else {
				$doc.one('transitionend webkitTransitionEnd oTransitionEnd otransitionend', function () {
					$doc.removeClass('off-canvas-' + offcanvas_dir + '-transition');
				});
			}
		});
	};

}(jQuery, doko);
