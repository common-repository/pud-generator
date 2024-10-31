<?php

/**
 * Fired during plugin activation
 *
 * @link       http://www.pudsoft.in/
 * @since      1.0.0
 *
 * @package    Pud_Generator
 * @subpackage Pud_Generator/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pud_Generator
 * @subpackage Pud_Generator/includes
 * @author     Pud Quiz <pudquiz@gmail.com>
 */
class Pud_Generator_Activator {

	/**
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		global $wpdb;
		$structure = "CREATE TABLE  IF NOT EXISTS ".PUD_GENERATOR_TABLE." (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `user_id` int(11) DEFAULT NULL,
					  `name` varchar(255) DEFAULT NULL,
					  `page_title` varchar(500) DEFAULT NULL,
					  `page_excerpt` varchar(500) DEFAULT NULL,
					  `page_content` text,
					  `page_combination` int(11) DEFAULT '0',
					  `max_page` int(11) DEFAULT '999',
					  `author_id` int(11) DEFAULT NULL,
					  `post_status` varchar(20) DEFAULT 'publish',
					  `visibility` varchar(20) DEFAULT 'public',
					  `status` varchar(20) DEFAULT 'pending',
					  `post_type` varchar(20) DEFAULT 'page',
					  `last_log_id` int(11) DEFAULT '0',
					  `created` datetime DEFAULT NULL,
					  `updated` datetime DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  KEY `IDX_user_id` (`user_id`),
					  KEY `IDX_status` (`status`)
					) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
		$wpdb->query($structure);

		$structure = "CREATE TABLE  IF NOT EXISTS ".PUD_PLACEHOLDER_TABLE." (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `name` varchar(255) DEFAULT NULL,
					  `placeholder` varchar(255) DEFAULT NULL,
					  `tags` text,
					  `created` datetime DEFAULT NULL,
					  `updated` datetime DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `IDX_placeholder` (`placeholder`),
					  KEY `IDX_name` (`name`)
					) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
		$wpdb->query($structure);

		$structure = "CREATE TABLE IF NOT EXISTS ".PUD_GENERATOR_RELATION." 		 (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `generator_id` int(10) unsigned DEFAULT NULL,
					  `placeholder_id` int(10) unsigned DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  UNIQUE KEY `IDX_generator_placeholder` (`generator_id`,`placeholder_id`),
					  KEY `Const_Placeholder_id` (`placeholder_id`),
					  CONSTRAINT `Const_Generator_Id` FOREIGN KEY (`generator_id`) REFERENCES `".PUD_GENERATOR_TABLE."` (`id`) ON DELETE CASCADE,
					  CONSTRAINT `Const_Placeholder_id` FOREIGN KEY (`placeholder_id`) REFERENCES `".PUD_PLACEHOLDER_TABLE."` (`id`) ON DELETE CASCADE
					) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
		$wpdb->query($structure);

		$structure = "CREATE TABLE IF NOT EXISTS ".PUD_GENERATOR_LOG_TABLE." (
					  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					  `generator_id` int(10) unsigned DEFAULT NULL,
					  `content` text,
					  `page_id` int(11) DEFAULT NULL,
					  `created` datetime DEFAULT NULL,
					  PRIMARY KEY (`id`),
					  KEY `IDX_gen_log` (`generator_id`),
					  CONSTRAINT `CONT_gen_log_id` FOREIGN KEY (`generator_id`) REFERENCES `".PUD_GENERATOR_TABLE."` (`id`) ON DELETE CASCADE
					) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
		$wpdb->query($structure);

		$fields = array(
	        'pud_max_page' => 999,
	        'pud_default_author' => get_current_user_id(), 
	        'pud_page_status' => 'publish', 
	        'pud_page_visibility' => 'public',
	        'pud_tour_generator' => '1',
	        'pud_tour_manage' => '1',
	        'pud_tour_placeholder' => '1',
	    );
	    foreach( $fields as $field => $val ) {
	        $data = get_option( $field );
	        if ( $data == FALSE ) {
	            update_option( $field, $val);
	        }
	    }
	    
	  //  if( !wp_next_scheduled( 'pudcronjob' ) ) {  
		//   wp_schedule_event( time(), 'everyminute', 'pudcronjob' );  
		//}
	}

}
