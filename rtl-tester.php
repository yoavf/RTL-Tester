<?php
/**
 * @package RTL_Tester
 * @version 1.0.1
 */
/*
Plugin Name: RTL Tester
Plugin URI: http://wordpress.org/extend/plugins/rtl-tester/
Description: This plugin adds a button to the admin bar that allow super admins to switch the text direction of the site. It can be used to test WordPress themes and plugins with Right To Left (RTL) text direction.
Author: <a href="http://blog.yoavfarhi.com">Yoav Farhi</a>, <a href="http://automattic.com">Automattic</a>
Version: 1.0.1
*/


class RTLTester {

	function __construct() {

		load_plugin_textdomain( 'rtl-tester', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		add_action( 'admin_bar_menu', array( $this, 'admin_bar_rtl_switcher' ), 999 );
		add_action( 'init', array( $this, 'set_direction' ) );
	}

	function admin_bar_rtl_switcher() {
		global $wp_admin_bar;

		if ( !is_super_admin() || !is_admin_bar_showing() )
	      return;

		$direction = $this->get_direction();
		$direction = $direction == 'rtl' ? 'ltr' : 'rtl';

		$wp_admin_bar->add_menu(array(
			'id' => 'RTL',
		 	'title' => sprintf( __('Switch to %s', 'rtl-tester'), strtoupper( $direction ) ),
		 	'href' => add_query_arg( array( 'd' => $direction ) )
		) );
	}

	function set_direction() {
		global $wp_locale;

		$_user_id = get_current_user_id();

		if ( isset( $_GET['d'] ) ) {
			$direction = $_GET['d'] == 'rtl' ? 'rtl' : 'ltr';
			update_user_meta( $_user_id, 'rtladminbar', $direction );
		} else {
			$direction = get_user_meta( $_user_id, 'rtladminbar', true );
			if ( false === $direction )
				$direction = $wp_locale->text_direction;
		}

		$wp_locale->text_direction = $direction;

	}

	function get_direction() {
		$direction = is_rtl() ? 'rtl' : 'ltr';
		return $direction;
	}
}

new RTLTester;

?>