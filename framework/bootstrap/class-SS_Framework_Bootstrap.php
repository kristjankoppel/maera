<?php

if ( ! class_exists( 'SS_Framework_Bootstrap' ) ) {

	/**
	* The Bootstrap Framework module
	*/
	class SS_Framework_Bootstrap extends SS_Framework_Core {

		private static $instance;

		/**
		 * Class constructor
		 */
		public function __construct() {

			if ( ! defined( 'SS_FRAMEWORK_PATH' ) ) {
				define( 'SS_FRAMEWORK_PATH', dirname( __FILE__ ) );
			}

			// CAUTION: THE BELOW IS SIMPLY FOR DEVELOPMENT
			// STYLESHEETS WILL GET RECOMPILED ON EACH PAGE LOAD
			$compiler = new Shoestrap_Compiler( array(
				'compiler'     => 'less_php',
				'minimize_css' => true,
				'less_path'    => dirname( __FILE__ ) . '/assets/less/',
			) );

		}

		/**
		 * Singleton
		 */
		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}
	}
}
