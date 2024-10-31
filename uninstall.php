<?php
/**
 * Uninstall the plugin.
 * Remove plugin options.
 *
 * @package Neptune Style Element
 * @author  NeptuneTheme
 */

// If uninstall is not called from WordPress, exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option( 'neptune-style-element' );
delete_option( 'Neptune_Style_Element_Customize' );
