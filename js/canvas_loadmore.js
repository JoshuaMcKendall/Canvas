jQuery(function($){

	var options = {

			'infinite' : false,
			'interval' : 1

		},
		canBeLoaded = true,
		bottomOffset = 2000,
		$settings = $('#loadmore-settings'),
		$loadMore = $('#canvas-loadmore');

	if( $loadMore.hasClass('hidden') ) {

		$loadMore.removeClass('hidden');

	}


	$(window).scroll( function() {



	} );

	$('.canvas-loadmore').click(function( e ){

		e.preventDefault();
 
		var button = $(this),
			data = {
				'action': 'loadmore',
				'query': canvas_loadmore_params.posts, // that's how we get params from wp_localize_script() function
				'page' : canvas_loadmore_params.current_page
			};

		$settings.prop('disabled', true);

 
		$.ajax({
			url : canvas_loadmore_params.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			beforeSend : function ( xhr ) {

				button.text( canvas_loadmore_params.loading_text ); // change the button text, you can also add a preloader image
				button.addClass('loading');
				button.prepend( $( '<span></span>' ).addClass('icon icon-left loading-spinner rotating') );
			},
			success : function( data, status ){

				//console.log(data);
				if( data ) { 
					button.text( canvas_loadmore_params.trigger_text ).parent().before(data); // insert new posts
					canvas_loadmore_params.current_page++;
 
					if ( canvas_loadmore_params.current_page == canvas_loadmore_params.max_page ) { 
						
						button.remove(); // if last page, remove the button
						$settings.remove();

						$( document.body ).trigger( 'post-load' );

					}

					$settings.prop('disabled', false);

				} else {
					console.log( 'No data: ', data );
					button.remove(); // if no data, remove the button as well
					$settings.remove();
				}
			}
		});
	});

});