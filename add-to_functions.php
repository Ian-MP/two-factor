<?php

// ADD THE FOLLOWING TO YOUR CHILD-THEME'S FUNCTIONS.PHP

/**
 * Remove specific Two-Factor providers from frontend shortcode.
 *
 * @param array   $providers List of available providers.
 * @param WP_User $user The user object.
 * @return array Modified providers list.
 */
function custom_filter_frontend_two_factor_providers( $providers, $user ) {
	// Only filter on frontend (not in admin)
	if ( is_admin() ) {
		return $providers;
	}
	// Remove FIDO U2F
	if ( isset( $providers['Two_Factor_FIDO_U2F'] ) ) {
		unset( $providers['Two_Factor_FIDO_U2F'] );
	}
	// You can also remove other providers: 
	unset( $providers['Two_Factor_Dummy'] );
	// unset( $providers['Two_Factor_Email'] );	
	return $providers;
}
add_filter( 'two_factor_providers_for_user', 'custom_filter_frontend_two_factor_providers', 10, 2 );
