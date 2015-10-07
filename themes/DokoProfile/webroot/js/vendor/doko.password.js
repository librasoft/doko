;(function ($, window, document, undefined) {

    var pluginName = "dokoPassword",
        defaults = {
            container: '.form-group',
            weakLabel: 'Weak',
            normalLabel: 'Normal',
            strongLabel: 'Strong',
            strengthScaleFactor: 1,
            minimumChars: 8
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

            $container.append('<div class="strength-meter"><div class="meter-bar"><span class="bar-1"></span><span class="bar-2"></span><span class="bar-3"></span></div><p class="meter-label"></p></div>');

            var $meter = $container.find('.strength-meter');

            $this.complexify({
                strengthScaleFactor: settings.strengthScaleFactor,
                minimumChars: settings.minimumChars
            }, function(valid, complexity) {
                $meter.removeClass('strength-weak strength-normal strength-strong');
                $meter.find('.meter-label').text('');

                if ($this.val().length < $this.data('min-length')) {
                    return;
                }

                if (complexity < 35) {
                    $container.find('.strength-meter').addClass('strength-weak');
                    $meter.find('.meter-label').text(settings.weakLabel);
                } else if (complexity < 55) {
                    $container.find('.strength-meter').addClass('strength-normal');
                    $meter.find('.meter-label').text(settings.normalLabel);
                } else {
                    $container.find('.strength-meter').addClass('strength-strong');
                    $meter.find('.meter-label').text(settings.strongLabel);
                }
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
