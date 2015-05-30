;(function ($, window, document, undefined) {

	var pluginName = "dokoEmailSuggest",
		defaults = {
			container: '.form-group'
		};

	function Plugin (element, options) {
		this.element = element;
		this.settings = $.extend({}, defaults, options);
		this._defaults = defaults;
		this._name = pluginName;
		this.init();
	}

	$.extend(Plugin.prototype, {
		init: function () {
			var $this = $(this.element),
				settings = this.settings,
				$container = $this.closest(settings.container);

			$this.on('blur', function() {
				$(this).mailcheck({
					suggested: function($el, suggestion) {
						$container.find('.mailcheck').remove();

						if (suggestion) {
							$container.append('<p class="form-control-actions mailcheck">' + $el.data('mailcheck').replace('{{suggestion}}', '<a href="#">' + suggestion.address + '@<strong>' + suggestion.domain + '</strong></a>') + '</p>');
							$container.find('.mailcheck').on('click', 'a', function(e) {
								e.preventDefault();
								$el.val(suggestion.full);
								$container.find('.mailcheck').remove();
							});
						}
					},
					empty: function($el) {
						$container.find('.mailcheck').remove();
					}
				});
			});
		}
	});

	$.fn[pluginName] = function (options) {
		this.each(function() {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName, new Plugin(this, options));
			}
		});

		return this;
	};

})(jQuery, window, document);
