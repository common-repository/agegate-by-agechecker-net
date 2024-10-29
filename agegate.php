<?php
/*
Plugin Name: AgeGate by AgeChecker.Net
Plugin URI:  https://agechecker.net/age-gate
Description: An age gate is the popup that you see when you enter an age-restricted website, such as one that sells tobacco products, alcohol, weapons, adult-related products, etc. It lets your site visitors know that you have a minimum age requirement, and asks them to self-certify that they are of legal age to enter. If they aren’t of age, the age gate will automatically block entry to your site and redirect them to a site of your choosing.
Version:     1.0.0
Author:      AgeChecker.Net
Author URI:  https://agechecker.net
License: 	 GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (!class_exists('AgeCheckerNet_AgeGate')):
	class AgeCheckerNet_AgeGate {

		public function __construct() {
			add_action('plugins_loaded', array(
				$this,
				'init'
			));
		}

		public function init() {
			include_once 'class-agegate-popup.php';
			include_once 'class-agegate-settings.php';

			$settings = new AgeCheckerNet_AgeGate_Settings();

			new AgeCheckerNet_AgeGate_Popup( $settings );
		}
	}

	$AgeCheckerNet_AgeGate = new AgeCheckerNet_AgeGate();
endif;
