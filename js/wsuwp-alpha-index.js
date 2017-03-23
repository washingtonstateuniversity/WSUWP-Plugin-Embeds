( function( $ ) {
	var $index = $( ".index" );

	$index.on( "click", "a", function( e ) {
		e.preventDefault();

		$( "html, body" ).animate( {
			scrollTop: $( this.hash ).offset().top
		}, 500 );
	} );
}( jQuery ) );
