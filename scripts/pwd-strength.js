function checkPasswordStrength( pass1,
								pass2,
								strengthResult,
								submitButton,
								blacklistArray ) {
	var pass1 = pass1.val();
	var pass2 = pass2.val();

	// Reset the form & meter
	submitButton.attr( 'disabled', 'disabled' );
	strengthResult.removeClass( 'short bad good strong' );

	// Extend our blacklist array with those from the inputs & site data
	blacklistArray = blacklistArray.concat( wp.passwordStrength.userInputBlacklist() )

	// Get the password strength
	var strength = wp.passwordStrength.meter( pass1, blacklistArray, pass2 );

	// Add the strength meter results
	switch ( strength )
	{
		case -1:
			break;
		case 2:
			strengthResult.addClass( 'bad' ).html( pwsL10n.bad );
			break;
		case 3:
		case 6:
			strengthResult.addClass( 'good' ).html( pwsL10n.good );
			break;
		case 4:
			strengthResult.addClass( 'strong' ).html( pwsL10n.strong );
			break;
		case 5:
			strengthResult.addClass( 'short' ).html( pwsL10n.mismatch );
			break;
		default:
			strengthResult.addClass( 'short' ).html( pwsL10n.short );
	}

	// The meter function returns a result even if pass2 is empty,
	// enable only the submit button if the password is strong and
	// both passwords are filled up
	if ( strength == -1 || (minStrength <= strength && '' !== pass2.trim() )) {
		submitButton.removeAttr( 'disabled' );
	}

	return strength;
}

jQuery( document ).ready( function()
{
	// Binding to trigger checkPasswordStrength
	jQuery( 'body' ).on( 'keyup', 'input[name=pass1], input[name=pass2]',
		function( event ) {
			checkPasswordStrength(
				jQuery('input[name=pass1]'),         // First password field
				jQuery('input[name=pass2]'), // Second password field
				jQuery('#pass-strength-result'),           // Strength meter
				jQuery('input[type=submit]'),           // Submit button
				[]        // Blacklisted words
			);
		}
	);
});