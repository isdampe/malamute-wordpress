(function(window){

	this.wpEditor = null;

	var mde = this;
	var CodeMirror = window.CodeMirror;
	var $ = window.jQuery;
	window.markdownEditor = this;
	

	/**
	 * Automatically called on loading
	 * Sets up CodeMirror on the Wordpress editor textarea
	 * @return {void}
	 */
	mde.init = function() {
		mde.wpEditor = document.getElementById('content');
		if (! mde.wpEditor ) return;
		mde.setupCodeMirror();
	}

	/**
	 * Attachs a new CodeMirror instance to the Wordpress editor
	 * @return {void}
	 */
	mde.setupCodeMirror = function() {
		mde.CodeMirror = CodeMirror.fromTextArea(mde.wpEditor, {
		 	value: this.wpEditor.value,
			mode:  "gfm",
		 	viewportMargin: Infinity,
		 	lineNumbers: true,
		 	theme: window.malamuteTheme || "base16-light"
		});
	}

	$(document).ready(function(){
		mde.init();	
	});
	

})(window);