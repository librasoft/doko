+function ($, doko) { "use strict";

	doko.init();

	var triggers = $('html').data('js');

	for (var i in triggers) {
		for (var j in triggers[i]) {
			if (typeof doko.modules[i + '-' + triggers[i][j]] === 'function') {
				doko.modules[i + '-' + triggers[i][j]]();
			}
		}
	}

	doko.shutdown();

}(jQuery, doko);
