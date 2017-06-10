(function(window){

	this.wpEditor = null;

	var mlm = this;
	var CodeMirror = window.CodeMirror;
	var $ = window.jQuery;
	window.malamuteEditor = this;
	

	/**
	 * Automatically called on loading
	 * Sets up CodeMirror on the Wordpress editor textarea
	 * @return {void}
	 */
	mlm.init = function() {
		mlm.wpEditor = document.getElementById('content');
		if (! mlm.wpEditor ) return;
		mlm.setupCodeMirror();
	}

	/**
	 * Attachs a new CodeMirror instance to the Wordpress editor
	 * @return {void}
	 */
	mlm.setupCodeMirror = function() {
		mlm.CodeMirror = CodeMirror.fromTextArea(mlm.wpEditor, {
		 	value: this.wpEditor.value,
			mode:  "gfm",
		 	viewportMargin: Infinity,
		 	lineNumbers: true,
		 	theme: window.malamuteTheme || "base16-light"
		});
	}

	$(document).ready(function(){
		mlm.init();	
	});
	

})(window);