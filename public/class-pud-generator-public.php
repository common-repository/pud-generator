<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.pudsoft.in/
 * @since      1.0.0

 * @package    Pud_Generator
 * @subpackage Pud_Generator/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pud_Generator
 * @subpackage Pud_Generator/public
 */
class Pud_Generator_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $pud_generator    The ID of this plugin.
	 */
	private $pud_generator;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $pud_generator       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $pud_generator, $version ) {

		$this->pud_generator = $pud_generator;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->pud_generator, plugin_dir_url( __FILE__ ) . 'css/pud-generator-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->pud_generator, plugin_dir_url( __FILE__ ) . 'js/pud-generator-public.js', array( 'jquery' ), $this->version, false );
	}

}
