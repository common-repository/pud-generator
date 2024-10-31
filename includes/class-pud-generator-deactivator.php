<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://www.pudsoft.in/
 * @since      1.0.0
 *
 * @package    Pud_Generator
 * @subpackage Pud_Generator/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Pud_Generator
 * @subpackage Pud_Generator/includes 
 * @author     Pud Quiz <pudquiz@gmail.com>
 */
class Pud_Generator_Deactivator {

	/**
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_option('pud_max_page');
		delete_option('pud_default_author');
		delete_option('pud_page_status');
		delete_option('pud_page_visibility');
		delete_option('pud_tour_generator');
		delete_option('pud_tour_manage');
		delete_option('pud_tour_placeholder');

		// find out when the last event was scheduled
		//$timestamp = wp_next_scheduled ('pudcronjob');
		// unschedule previous event if any
		//wp_unschedule_event ($timestamp, 'pudcronjob');
	}
}
