jQuery.extend(jQuery.fn, {
	/*
	 * check if field value length more than 3 symbols ( for name and comment ) 
	 */
	validate: function () {

		if (jQuery(this).val().length < 3 ) {jQuery(this).addClass('error');return false} else {jQuery(this).removeClass('error');return true}
	},
	/*
	 * check if email is correct
	 * add to your CSS the styles of .error field, for example border-color:red;
	 */
	validateEmail: function () {

		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/,
		    emailToValidate = jQuery(this).val(),
		    name_email_req = canvas_ajax_comment_params.req;

		if ( !emailReg.test( emailToValidate ) || ( name_email_req && emailToValidate == "" ) ) {

			jQuery(this).addClass('error');return false

		} else {

			jQuery(this).removeClass('error');return true

		}

	},
});
 
jQuery(function($){
 
	/*
	 * On comment form submit
	 */
	$( '#commentform' ).submit(function(e){

		e.preventDefault();
 
		// define some vars
		var button = $('#submit'), // submit button
		    respond = $('#respond'), // comment form container
		    commentlist = $('.comments-area'), // comment list container
		    loader = $('#commentform .ajax-loader'),
		    cancelreplylink = $('#cancel-comment-reply-link'),
		    $feedbackAlert = $('#comment-form-feedback'),
		    post_id = canvas_ajax_comment_params.post_id,
		    name_email_req = canvas_ajax_comment_params.req,
		    btn_text = canvas_ajax_comment_params.btn_text,
		    loading_text = canvas_ajax_comment_params.loading_text;
 
		// if user is logged in, do not validate author and email fields
		if( $( '#author' ).val() || $('#author').hasClass('error') )
			$( '#author' ).validate();

		if( $( '#email' ).val() || $('#email').hasClass('error') )
			$( '#email' ).validateEmail();

		// validate comment in any case
		$( '#comment' ).validate();
 
		// if comment form isn't in process, submit it
		if ( !button.hasClass( 'loadingform' ) && !$( '#author' ).hasClass( 'error' ) && !$( '#email' ).hasClass( 'error' ) && !$( '#comment' ).hasClass( 'error' ) ){

			// ajax request
			$.ajax({
				type : 'POST',
				url : canvas_ajax_comment_params.ajaxurl, // admin-ajax.php URL
				data: $(this).serialize() + '&action=ajax_comments&post_id=' + post_id, // send form data + action parameter
				beforeSend: function(xhr){

					// what to do just after the form has been submitted
					button.addClass('loadingform').val( loading_text );

					loader.addClass('is-active loading');

					if( ! $feedbackAlert.hasClass( 'hidden' ) ) {

						$feedbackAlert.addClass( 'hidden' );

					}

				},
				error: function (request, status, error) {

					loader.removeClass('is-active loading');

					if( status == 500 ){

						$feedbackAlert.addClass( 'alert-warning' );

						$feedbackAlert.html( 'Error while adding comment' );

						$feedbackAlert.removeClass( 'hidden' );


					} else if( status == 'timeout' ){

						$feedbackAlert.addClass( 'alert-warning' );

						$feedbackAlert.html( 'Error: Server doesn\'t respond.' );

						$feedbackAlert.removeClass( 'hidden' );

					} else {

						// process WordPress errors
						var wpErrorHtml = request.responseText.split("<p>"),
							wpErrorStr = wpErrorHtml[1].split("</p>");
 
						// alert( wpErrorStr[0] );

						$feedbackAlert.addClass( 'alert-warning' );

						$feedbackAlert.html( wpErrorStr[0] );

						$feedbackAlert.removeClass( 'hidden' );
					}
				},
				success: function ( comment ) {

					var addedCommentHTML = $( comment ).addClass( 'new-comment' ),
						comment_parent = respond.parent().parent(),
						storedComment;
 
					// if this post already has comments
					if( commentlist.length > 0 ){
 
						// if in reply to another comment
						if( comment_parent.hasClass( 'comment' ) ){
 
							// if the other replies exist
							if( comment_parent.children( '.children' ).length ){	
								storedComment = comment_parent.children( '.children' ).prepend( addedCommentHTML );
							} else {
								// if no replies, add <ol class="children">
								comment_parent.addClass('parent');
								addedCommentHTML = '<ol class="children">' + addedCommentHTML.prop('outerHTML') + '</ol>';
								storedComment = comment_parent.append( addedCommentHTML );
							}
							// close respond form
							cancelreplylink.trigger("click");
						} else {
							// simple comment
							storedComment = commentlist.prepend( addedCommentHTML );
						}
					}else{

						var comment_parent = respond.parent().parent();
						// if no comments yet
						//console.log( addedCommentHTML );

						addedCommentHTML = '<ol class="comments-area">' + addedCommentHTML.prop('outerHTML') + '</ol>';
						storedComment = comment_parent.append( addedCommentHTML );
					}
					// clear textarea field
					$('#comment').val('');

					setTimeout( function( storedComment ) {

							storedComment.find( '.new-comment' ).removeClass( 'new-comment' );

					}, 6000, storedComment );

					storedComment = undefined;


				},
				complete: function(){
					// what to do after a comment has been added
					button.removeClass( 'loadingform' ).val( btn_text );

					loader.removeClass('is-active loading');

				}
			});
		}
		return false;
	});
});