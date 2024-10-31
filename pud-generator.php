<?php
/**
 * Plugin Name:       PUD Generator
 * Description:       Bulk Page/Post generator.You can generate thousands of Pages/Posts with in few steps. 
 * Version:           1.0.0
 * Author:            PudSoft
 * Author URI:        http://www.pudsoft.in/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pud_generator
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
global $wpdb;
define('PUD_GENERATOR_VERSION', '1.0.0' );
define('PUD_GENERATOR_TABLE_PREFIX', $wpdb->prefix);
define('PUD_GENERATOR_TABLE', $wpdb->prefix.'pud_generator');
define('PUD_GENERATOR_RELATION', $wpdb->prefix.'pud_generator_placeholders');
define('PUD_PLACEHOLDER_TABLE', $wpdb->prefix.'pud_placeholders');
define('PUD_GENERATOR_LOG_TABLE', $wpdb->prefix.'pud_generator_logs');

/**
 * The code that runs during plugin activation.
 */
function activate_pud_generator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pud-generator-activator.php';
	Pud_Generator_Activator::activate();
}

function pud_generator()
{
    require_once plugin_dir_path( __FILE__ ) . 'admin/partials/pud-generator-admin-generator.php';
}

function pud_general()
{
	require_once plugin_dir_path( __FILE__ ) . 'admin/partials/pud-generator-admin-general.php';
}
function pud_manage()
{
	require_once plugin_dir_path( __FILE__ ) . 'admin/partials/pud-generator-admin-manage.php';
}
function pud_placeholder()
{
    require_once plugin_dir_path( __FILE__ ) . 'admin/partials/pud-generator-admin-placeholder.php';
}
 

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_pud_generator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pud-generator-deactivator.php';
	Pud_Generator_Deactivator::deactivate();
}
register_activation_hook( __FILE__, 'activate_pud_generator' );
register_deactivation_hook( __FILE__, 'deactivate_pud_generator' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pud-generator.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_pud_generator() {
	$plugin = new Pud_Generator();
	$plugin->run();
}
run_pud_generator();

function page_tabs( $current = 'pud_general' ) {
    $tabs = array(
        'pud_generator' => __( 'New Generator', 'pud_generator'),
        'pud_general'   => __( 'General', 'pud_generator' ), 
        'pud_manage'  => __( 'All Generator', 'pud_generator' ),
        'pud_placeholder'  => __( 'Placeholders', 'pud_generator' ),
    );
    $html = '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? 'nav-tab-active' : '';
        $html .= '<a class="nav-tab ' . $class . '" href="?page='.$tab.'">' . $name . '</a>';
    }
    $html .= '</h2><div class="pud-loader"></div>';
    return $html;
}
function alert_message()
{
    return '<div class="pud-alert success">
          <span class="pud-closebtn" onclick="this.parentElement.style.display=\'none\';">&times;</span> 
          <span class="pud-alert-msg" ></span>
        </div>';
}

add_filter('page_row_actions','add_page_action_menu', 10, 2);
add_filter('post_row_actions','add_post_action_menu', 10, 2);

function add_page_action_menu($actions, $page){
      $actions['pud_generator'] = '<a href="admin.php?page=pud_generator&type=page&id='.$page->ID.'">Generator</a>';
    return $actions;
}

function add_post_action_menu($actions, $post){
      $actions['pud_generator'] = '<a href="admin.php?page=pud_generator&type=post&id='.$post->ID.'">Generator</a>';
    return $actions;
}

function pud_generator_settings(){
    //register our settings
    register_setting( 'pud-generator-settings-group', 'pud_max_page' );
    register_setting( 'pud-generator-settings-group', 'pud_default_author' );
    register_setting( 'pud-generator-settings-group', 'pud_page_status' );
    register_setting( 'pud-generator-settings-group', 'pud_page_visibility' );
}

function get_pud_statuses()
{
    $statuses = array(
        'draft'     => __( 'Draft', 'pud_generator' ),
        'publish'   => __( 'Publish', 'pud_generator' ),
    );
    return $statuses;
}
function get_pud_status($key)
{
    $statuses = get_pud_statuses();
    return isset($statuses[$key])?$statuses[$key]:'';
}

function get_pud_visibilities()
{
    $visibilities = array(
        'public'     => __( 'Public', 'pud_generator' ),
        'private'   => __( 'Private', 'pud_generator' ),
    );
    return $visibilities;
}
function get_pud_visibility($key)
{
    $visibilities = get_pud_visibilities();
    return isset($visibilities[$key])?$visibilities[$key]:'';
}

function get_generator_status($key)
{
    $status = array(
        'pending'     => __( 'Pending', 'pud_generator' ),
        'progress'   => __( 'Progress', 'pud_generator' ),
        'completed'   => __( 'Completed', 'pud_generator' ),
        'error'   => __( 'Error', 'pud_generator' ),    );
    return isset($status[$key])?$status[$key]:'';
}

// add another interval
function cron_add_minute( $schedules ) {
    // Adds once every minute to the existing schedules.
    $schedules['everyminute'] = array(
        'interval' => 60,
        'display' => __( 'Once Every Minute' )
    );
    return $schedules;
}
//add_filter( 'cron_schedules', 'cron_add_minute' );