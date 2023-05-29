(function( $ ) {
	'use strict';
	//Stars Rating
	jQuery(document).ready(function($){
		function initialiseField( $el ) {
			var container = $el.closest('.rating-container');
			var starList = $("ul", container);
			var starListItems = $("li", starList);
			var starListItemStars = $("i", starListItems);
			var starField = $("input", container);
			var clearButton = $("a.clear-button", container);
			var allowHalf = (starField.data('allow-half') == 1);
			var emptyClass = window.starClasses[0];
			var halfClass = window.starClasses[1];
			var fullClass = window.starClasses[2];
	
			starListItems.bind("click", function(e){
				e.preventDefault();
	
				var starValue = $(this).index();
				starField.val(starValue + 1);
				
				if (allowHalf) {
					var width = $(this).innerWidth();
					var offset = $(this).offset(); 
					var leftSideClicked = (width / 2) > (e.pageX - offset.left);
	
					if (leftSideClicked) {
						starField.val(starField.val() - 0.5);
					}
				}
	
				clearActiveStarClassesFromList();
	
				starListItems.each(function(index){
					var icon = $('i', $(this));
					var starValue = starField.val();
	
					if (index < starValue) {
						icon.removeClass(emptyClass)
							.removeClass(halfClass)
							.addClass(fullClass);
	
						if (allowHalf && (index + .5 == starValue)) {
							icon.removeClass(fullClass);
							icon.addClass(halfClass);
						}
					}
				});
	
				starField.trigger("change");
			});
	
			clearButton.bind("click", function(e){
				e.preventDefault();
	
				clearActiveStarClassesFromList();
	
				starField.val(0);
	
				starField.trigger("change");
			});
	
			function clearActiveStarClassesFromList()
			{
				starListItemStars
					.removeClass(fullClass)
					.removeClass(halfClass)
					.addClass(emptyClass);
			}	
		}
		var rateId= $(document).find('.star-rating').attr('id'),
			stored = localStorage.getItem('saved-' + rateId);
		if (true) {
	
			$('.star-rating').on('click', function(){
				initialiseField($(this));
			});
			$('.add-rating').on('click', function(e){
				var $this = $(this);
				e.preventDefault();
				if ($('#star-rating-hidden').val() != 0) {
					const stars = e.target.getAttribute('data-stars'),
					updatedStars = $('#star-rating-hidden').val();
					e.target.setAttribute('data-stars', updatedStars);
				}
				
				$.ajax({
					type : "POST",
					dataType : 'text',
					url : ajax_object.url,
					data : {
						'action': 'rating_ajax',
						'post_id': $this.data('postid'),
						'stars': $this.data('stars')
					},
					success: function(response) {
						if ($('#star-rating-hidden').val() != 0) {
							const rateId = $this.closest('.store-info').find('.star-rating').attr('id');
							localStorage.setItem('saved-' + rateId, true);
							$this.removeClass('add-rating').addClass('rating-sent').text('Thank you!');
							setTimeout(function(){
								$('.rating-sent').hide();
							}, 5000);
						} else {
							$('<div class="select-rating-validation">Te rugam sa selectezi numarul de stele</div>').insertAfter('.add-rating');
							setTimeout(function(){
								$('.select-rating-validation').hide();
							}, 3000);
						}
						
					},
					error :function(error) {
						alert('Nu s-a putut valida feedbackul');
						console.log(error);
					}
				});
			});
		} else {
			$('.add-rating').hide();
		}
	});

})( jQuery );
