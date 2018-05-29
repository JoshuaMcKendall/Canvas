jQuery(function($){

	var $comments_nav = $('.navigation.comments'),
	post_id = canvas_comments_loadmore_params.post_id,
	order = canvas_comments_loadmore_params.order,
	default_page = canvas_comments_loadmore_params.default_page,
	sort_by = canvas_comments_loadmore_params.sort_by,
	first_page;

	if ( default_page == 'oldest' ) {

		first_page = 'Default page is first page';

	} else if( default_page == 'newest' ) {

		first_page = 'Default page is last page';

		cpage = 1;

	}

	console.log( first_page );

	console.log( 'Total pages: ', page_count );

	console.log('Page: ', cpage );

	if( sort_by ) {

		console.log( 'Sort by: ', sort_by );

	}

	

	$comments_nav.html( $('.canvas_comment_loadmore') );
 
	// load more button click event
	$('.canvas_comment_loadmore .btn').click( function(e){
		var button = $(this);


		// decrease the current comment page value
		if( cpage >= 1 && cpage < page_count ) {

			cpage++;

		}
		
 
		$.ajax({
			url : ajaxurl, // AJAX handler, declared before
			data : {
				'action': 'cloadmore', // wp_ajax_cloadmore
				'post_id': post_id, // the current post
				'cpage' : cpage, // current comment page
				'csort' : sort_by,
				'default_page' : default_page
			},
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.attr('disabled', 'disabled');
				button.addClass('loading');
				button.text('Loading...'); // preloader here
				button.prepend( $( '<span></span>' ).addClass('icon icon-left loading-spinner rotating') );
			},
			success : function( data ){

				console.log( 'Page: ', cpage );

				if( data ) {

					button.removeAttr('disabled');

					button.removeClass('loading');

					$('.comments-area').append( data );
					button.text('Load More Comments'); 
					 // if the last page, remove the button
					if ( cpage == page_count  )
						button.remove();
				} else {
					button.remove();
				}
			}
		});
		return false;
	});
 
});