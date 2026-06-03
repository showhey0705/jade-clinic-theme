<?php
/**
 * Load Ollie (parent theme) translations for the current locale.
 *
 * @package vip2026
 */

namespace VIP2026\OllieI18n;

add_action(
	'after_setup_theme',
	static function (): void {
		$mofile = get_stylesheet_directory() . '/languages/ollie-' . determine_locale() . '.mo';

		if ( is_readable( $mofile ) ) {
			load_textdomain( 'ollie', $mofile );
		}
	},
	9
);
