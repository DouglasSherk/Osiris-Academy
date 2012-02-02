function shareMessage() {
	alert( "This file is secure. The password on it is 'megatrons'. Please do not share this file with people outside of the class or inform professors/TA's that you have it." );
}

function alertSecure() {
	shareMessage();
	while ( confirm( "I agree to the previous message." ) == false ) {
		shareMessage();
	}
}
