//contact.js
(function() {

	var errors = [];

	// Messages
	var missingContent = "Please fill in all fields.";
	var emailInvalid   = "Invalid email address.";
	var messageUnsent  = "Message was not sent, try again.";
	var messageSent    = "Your message has been sent.";

	// Contact form element ID's
	var contactForm = document.getElementById("contactform");
	var spamInput = document.getElementById("contact-title");
	var nameInput = document.getElementById("name");
	var emailInput = document.getElementById("email");
	var messageTextArea = document.getElementById("message");
	var submitButton = document.getElementById("button");

	var nameField = nameInput.name;
	var emailField = emailInput.name;
	var messageField = messageTextArea.name;

	// Generate Error or Success messages
	function generateResponse ( success, message ) {
		//console.log(message);
		if (success === "success") {
			submitButton.value = message;
			submitButton.style.width = "100%";
			submitButton.style.color = "#06B614";
			submitButton.style.border = "1px solid #14D116";
		} else {
			submitButton.value = message;
			submitButton.style.width = "100%";
			submitButton.style.color = "#B60606";
			submitButton.style.border = "1px solid #D11414";
		}
	}
	// Test if string is a valid email
	function isAnEmail ( email ) {
		var tryToMatch = /([\w\.\-_]+)?\w+@[\w-_]+(\.\w+){1,}/igm;
		if ( tryToMatch.test(email) ) {
			return true;
		}
		return false;
	}

	// Sends message to server
	function sendMessageToServer (name, email, message) {
		var request,
		params = nameField + "=" + name + "&" + emailField + "=" + email + "&" + messageField + "=" + message + "&action=canvas_contact_send_email";
		if (window.XMLHttpRequest) {
			request = new XMLHttpRequest();
		}

		// contact.ajaxurl javascript namespace and key for admin-ajax.php url : see functions.php
		request.open("POST", contact.ajaxurl, true);

		request.onreadystatechange = function () {
			if (request.readyState === 4 && request.status === 200 && request.responseText == "success") {
				generateResponse( request.responseText, messageSent );
				console.log("Message sent.");
				return true;
			} else {
				generateResponse( false, messageUnsent );
				return false;
			}
		};
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		request.send(params);
	}

	// Validate contact form
	function validateContactForm () {
	  contactForm.onsubmit = function () {
			submitButton.value = "...";
	    // If the email, message, or name field(s) are empty
	    if ( nameInput.value == "" || messageTextArea.value == "" || emailInput.value == "" ) {
	      generateResponse( false, missingContent );
	      return false;
			// Else if email is not a valid email
	    } else if ( !isAnEmail(emailInput.value) ) {
				generateResponse( false, emailInvalid );
				return false;
			// Else if the spam field is not empty
			} else if ( spamInput.value != "") {
				return false;
			} else {
				sendMessageToServer(nameInput.value, emailInput.value, messageTextArea.value);
				return false;
	    }
	  };
	}

	validateContactForm();

})();
