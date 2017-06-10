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
	
	/**
	 * Hooks relevant elements
	 * @return {void}
	 */
	mo.init = function() {

		$('[data-hook=malamute-set-codemirror-mode]').off('change').on('change', mo.hndModeCheckbox);

	};

	$(document).ready(function(){
		mo.init();	
	});
	

})(window);