(function(window){

	var modeInputElement = false;
	var activeModes = {};
	var mo = this;
	var $ = window.jQuery;

	/**
	 * Turns a mode on
	 * @param {string} mode - The mode to enable
	 * @return {void}
	 */
	mo.turnModeOn = function(mode) {
		activeModes[mode] = true;
		mo.updateJsonInput();
	};

	/**
	 * Turns a mode off
	 * @param {string} mode - The mode to disable
	 * @return {void}
	 */
	mo.turnModeOff = function(mode) {
		if ( activeModes.hasOwnProperty(mode) ) {
			delete activeModes[mode];
		}
		mo.updateJsonInput();
	};

	/**
	 * Converts data stored in activeModes into a string, and then sets the
	 * value of #malamute-codemirror_active_modes to the string
	 * this allows Wordpress to store the JSON string easily
	 * @return {void}
	 */
	mo.updateJsonInput = function() {
		var jsonString = JSON.stringify(activeModes);
		$('#malamute-codemirror_active_modes').val(jsonString);
	};

	/**
	 * Handles and routes action for checkboxes when they change
	 * @param {event} e - The event handler
	 * @return {void}
	 */
	mo.hndModeCheckbox = function(e) {

		var mode = $(this).attr('name');
		if ( this.checked ) {
			mo.turnModeOn(mode);
		} else {
			mo.turnModeOff(mode);
		}

		console.log(activeModes);

	};

	mo.checkAllBoxes = function(e) {

		var selected, unselected;
		selected = $('[data-hook=malamute-set-codemirror-mode]:checked').length;
		unselected = $('[data-hook=malamute-set-codemirror-mode]:not(:checked)').length;

		var direction = ( selected > unselected ? "uncheck" : "check" );

		$('[data-hook=malamute-set-codemirror-mode]').each(function(){
			if ( direction === "uncheck" ) {
				$(this).prop('checked', false);
			} else {
				$(this).prop('checked', true);
			}
		});
		$('[data-hook=malamute-set-codemirror-mode]').trigger('change');

	};

	/**
	 * Pre-loads and pre-ticks options if they exist
	 * @return {void}
	 */
	mo.preload = function() {

		var args = $('#malamute-codemirror_active_modes').val()
		if ( args ) {
			try {
				var j = JSON.parse(args);
				activeModes = j;

				for ( key in activeModes ) {
					if ( activeModes.hasOwnProperty(key) ) {
						$('#' + key).prop('checked', true);
					}
				}

			} catch(e) {

			}
		}

	};
	
	/**
	 * Hooks relevant elements
	 * @return {void}
	 */
	mo.init = function() {

		mo.preload();
		$('[data-hook=malamute-set-codemirror-mode]').off('change').on('change', mo.hndModeCheckbox);
		$('#malamute-check-all').off('change').on('change', mo.checkAllBoxes);

	};

	$(document).ready(function(){
		mo.init();	
	});
	

})(window);