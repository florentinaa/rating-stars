(function( $ ) {
	'use strict';
	$( document ).ready(function() {
		$('#show_rating_stars_check').on('click', function(){
			$('.rating-stars').toggleClass('show-rating');
		 });
	});
})( jQuery );
