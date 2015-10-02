jQuery(document).ready(function($) {
	$( 'body' ).show();
	$( '.easychangelog' ).accordion( {
		animate: 100,
		collapsible: true,
		active: false,
		header: easychangelogSelector,
		heightStyle: 'content'
	});
});
