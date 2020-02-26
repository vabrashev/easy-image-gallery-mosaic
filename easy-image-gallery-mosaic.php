<?php
/*
Plugin Name: Easy Image Gallery Mosaic Addon
Plugin URI: http://devrix.com/
Description: Easy Image Gallery Mosaic Addon
Version: 1.0.0
Author: DevriX
Author URI: http://devrix.com/
Text Domain: easy-image-gallery
License: GPL-2.0+
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//if ( class_exists( 'Easy_Image_Gallery' ) ) {

	/**
	 * PHP5 constructor method.
	 *
	 * @since 1.0
	 */
	class EIG_Mosaic {
		private $addon_slug = 'easy-image-gallery-mosaic-master';

		public function __construct() {
			//add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
			add_action( 'plugins_loaded', array( $this, 'constants' ) );
			//add_action( 'plugins_loaded', array( $this, 'includes' ) );
			add_action( 'init', array( $this, 'init_mosaic_addon' ) );
			// add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'easy_image_gallery_plugin_action_links' );
			register_deactivation_hook( __FILE__, array( $this, 'deactivate_plugin' ) );
		}

		/**
		 * Internationalization
		 *
		 * @since 1.0.0
		 */
		public function deactivate_plugin() {
			$eig_options = get_option( 'easy-image-gallery' );

			//Alter the options array appropriately
			$eig_options['grid_view'] = 'easy-image-gallery';

			update_option( 'easy-image-gallery', $eig_options );
		}

		/**
		 * Internationalization
		 *
		 * @since 1.0.0
		 */
		public function add_mosaic_grid( $arr_grid_views ) {
			$arr_grid_views = array_merge(
				$arr_grid_views,
				array(
					$this->addon_slug => 'Mosaic',
				)
			);

			return $arr_grid_views;
		}

		/**
		 * Internationalization
		 *
		 * @since 1.0.0
		 */
		public function change_image_size() {
			return 'large';
		}

		/**
		 * Internationalization
		 *
		 * @since 1.0.0
		 */
		public function load_mosaic_scripts() {
			wp_enqueue_script( $this->addon_slug, EIG_MOSAIC_URL . 'assets/js/jquery.mosaic.min.js', array( 'jquery' ), EIG_MOSAIC_VERSION, false );
			wp_enqueue_style( $this->addon_slug, EIG_MOSAIC_URL . 'assets/css/jquery.mosaic.min.css', '', EIG_MOSAIC_VERSION, 'screen' );
		}

		/**
		 * Internationalization
		 *
		 * @since 1.0.0
		 */
		public function init_mosaic_addon() {
			add_filter( 'add_more_grids_support', array( $this, 'add_mosaic_grid' ) );

			$eig_options = get_option( 'easy-image-gallery' );

			if ( $this->addon_slug == $eig_options['grid_view'] ) {
				add_filter( 'easy_image_gallery_thumbnail_image_size', array( $this, 'change_image_size' ) );
				add_action( 'easy_image_gallery_scripts', array( $this, 'load_mosaic_scripts' ) );
			}
		}

		/**
		 * Constants
		 *
		 * @since 1.0
		 */
		public function constants() {

			if ( ! defined( 'EIG_MOSAIC_DIR' ) ) {
				define( 'EIG_MOSAIC_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
			}

			if ( ! defined( 'EIG_MOSAIC_URL' ) ) {
				define( 'EIG_MOSAIC_URL', trailingslashit( plugin_dir_url( __FILE__ ) ) );
			}

			if ( ! defined( 'EIG_MOSAIC_VERSION' ) ) {
				define( 'EIG_MOSAIC_VERSION', '1.0.0' );
			}

			if ( ! defined( 'EIG_MOSAIC_INCLUDES' ) ) {
				define( 'EIG_MOSAIC_INCLUDES', EIG_MOSAIC_DIR . trailingslashit( 'includes' ) );
			}

		}

		/**
		 * Loads the initial files needed by the plugin.
		 *
		 * @since 1.0
		 */
		public function includes() {
			require_once EASY_IMAGE_GALLERY_INCLUDES . 'template-functions.php';
		}

	}
//}

new EIG_Mosaic();
